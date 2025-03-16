<?php

namespace Assesment\Test\Controllers;

use Assesment\Test\DTOs\CategoryDTO;
use Assesment\Test\Database\Connection;

class CategoryController
{
    public function list()
    {
        try {
            $pdo = Connection::getConnection();

            // Recursive CTE query with course counts
            $sql = "
                WITH RECURSIVE CategoryTree AS (
                    SELECT 
                        id, 
                        name, 
                        parent_id,
                        id as root_id
                    FROM categories
                    WHERE parent_id IS NULL
                    
                    UNION ALL
                    
                    SELECT 
                        c.id,
                        c.name,
                        c.parent_id,
                        ct.root_id
                    FROM categories c
                    INNER JOIN CategoryTree ct ON c.parent_id = ct.id
                )
                SELECT
                    ct.id,
                    ct.name,
                    ct.parent_id,
                    COUNT(c.course_id) as course_count,
                    GROUP_CONCAT(DISTINCT ct.root_id) as tree_path
                FROM CategoryTree ct
                LEFT JOIN courses c ON c.category_id = ct.id
                GROUP BY ct.id
                ORDER BY ct.parent_id, ct.name
            ";

            $stmt = $pdo->query($sql);
            $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Build hierarchical tree
            $categoryTree = $this->buildCategoryTree($categories);

            // Convert to DTOs
            $result = array_map(function ($category) {
                return CategoryDTO::fromArray($category);
            }, $categoryTree);

            header('Content-Type: application/json');
            echo json_encode($result);

        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    private function buildCategoryTree(array $categories, $parentId = null): array
    {
        $tree = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] === $parentId) {
                $children = $this->buildCategoryTree($categories, $category['id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }
        return $tree;
    }

}