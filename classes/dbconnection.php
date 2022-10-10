<?php

class dbconnection
{
    private static $instance = null;
    private $connection;

    private function __construct($hostname, $username, $password, $db)
    {
        $connection = new mysqli($hostname, $username, $password, $db);
        if ($connection->connect_error) {
            echo "fail " . $connection->connect_error;
            exit();
        }
        $this->connection = $connection;
    }

    public static function getDbConnection()
    {
        if (self::$instance === null) {
            self::$instance = new dbconnection(
                conf::getParam("dbhost"),
                conf::getParam("dbuser"),
                conf::getParam("dbpass"),
                conf::getParam("db")
            );
        }
        return self::$instance->connection;
    }
    public static function executeMySQLQuery($query)
    {
        if (self::$instance === null) {
            self::$instance = new dbconnection(
                conf::getParam("dbhost"),
                conf::getParam("dbuser"),
                conf::getParam("dbpass"),
                conf::getParam("db")
            );
        }
        $result = mysqli_query(dbconnection::getDbConnection(), $query);
        $error = mysqli_error(dbconnection::getDbConnection());
        if (!empty($error)) {
            echo "<h1>Error with query: " . $query . " <br> Error:" . $error . "</h1>";
        }
        #echo $query."<br>";
        return $result;
    }
}