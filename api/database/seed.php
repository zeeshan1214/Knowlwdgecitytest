<?php
require __DIR__ . '/../vendor/autoload.php';

use Assesment\Test\Database\Connection;

$pdo = Connection::getConnection();

// Seed categories (updated path)
$categories = json_decode(file_get_contents(__DIR__ . '/../data/categories.json'), true);
foreach ($categories as $cat) {
    $stmt = $pdo->prepare("INSERT INTO categories (id, name, parent_id) VALUES (?, ?, ?)");
    $stmt->execute([$cat['id'], $cat['name'], $cat['parent'] ?? null]);  // Handle null parent
}

// Seed courses (updated path)
$courses = json_decode(file_get_contents(__DIR__ . '/../data/course_list.json'), true);
foreach ($courses as $course) {
    $stmt = $pdo->prepare("INSERT INTO courses VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $course['course_id'],
        $course['title'],
        $course['description'],
        $course['image_preview'],
        $course['category_id']
    ]);
}

echo "Seeding completed!\n";