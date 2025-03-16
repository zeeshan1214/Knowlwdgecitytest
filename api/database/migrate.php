<?php
require __DIR__ . '/../vendor/autoload.php';

use Assesment\Test\Database\Connection;

try {
    $db = Connection::getConnection();
    
    $files = glob(__DIR__ . '/migrations/*.sql');
    sort($files);
    
    foreach ($files as $file) {
        $sql = file_get_contents($file);
        $db->exec($sql);
        echo "Migrated: " . basename($file) . "\n";
    }
    
    echo "Migrations completed successfully!\n";
} catch (\PDOException $e) {
    die("Migration failed: " . $e->getMessage() . "\n");
}