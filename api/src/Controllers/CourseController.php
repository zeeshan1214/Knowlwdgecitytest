<?php
namespace Assesment\Test\Controllers;

use Assesment\Test\Database\Connection;
use Assesment\Test\DTOs\CourseDTO;

class CourseController
{
    public function list()
    {
        try {
            $pdo = Connection::getConnection();
            $categoryId = $_GET['category'] ?? null;

            $sql = "SELECT 
                        c.course_id,
                        c.title,
                        c.description,
                        c.image_preview,
                        c.category_id,
                        cat.name as category_name
                    FROM courses c
                    JOIN categories cat ON c.category_id = cat.id";

            $params = [];
            
            if ($categoryId) {
                // Get all child categories
                $sql = "WITH RECURSIVE CategoryTree AS (
                            SELECT id FROM categories WHERE id = ?
                            UNION ALL
                            SELECT c.id FROM categories c
                            INNER JOIN CategoryTree ct ON c.parent_id = ct.id
                        )
                        SELECT 
                            c.course_id,
                            c.title,
                            c.description,
                            c.image_preview,
                            c.category_id,
                            cat.name as category_name
                        FROM courses c
                        JOIN categories cat ON c.category_id = cat.id
                        WHERE c.category_id IN (SELECT id FROM CategoryTree)";
                
                $params = [$categoryId];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $courses = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Convert to DTOs
            $result = array_map(function($course) {
                return CourseDTO::fromArray($course);
            }, $courses);

            header('Content-Type: application/json');
            echo json_encode($result);
            
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }
}