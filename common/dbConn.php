<?php
class dbConn
{
    protected static $db = null;
    
    private function __clone()
    {
    }
	
	private function __wakeup()
    {
    }
    
    private function __construct()
    {
        include_once(__DIR__ . '/../conf/conf.php');
        try {
            self::$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    public static function getConnection()
    {
        if (!self::$db) {
            new dbConn();
        }
        return self::$db;
    }
}