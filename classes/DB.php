<?php
class DB {

    private static $host = "localhost";
    private static $database = "db201801625";
    private static $username = "u201801625";
    private static $password = "u201801625";
    
    public static function getConnection() {
        $dsn = 'mysql:host=' . DB::$host . ';dbname=' . DB::$database;

        $connection = new PDO($dsn, DB::$username, DB::$password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    }

}