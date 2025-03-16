<?php
namespace Assesment\Test\Database;
class Connection
{
    private static $instance;
    public static function getConnection(): \PDO
    {
        if (!self::$instance) {
            self::$instance = new \PDO(
                'mysql:host=db;dbname=course_catalog',
                'test_user',
                'test_password'
            );
        }
        return self::$instance;
    }
}