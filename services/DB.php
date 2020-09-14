<?php

namespace Services;

class DB
{
    private static $DB_HOST = null;
    private static $DB_USERNAME = '';
    private static $DB_PASSWORD = null;
    private static $DB_DATABASE = null;
    private static $DB_PORT = null;

    private static $connection = null;

    private function __construct()
    {
        self::$connection = new \mysqli(self::$DB_HOST, self::$DB_USERNAME, self::$DB_PASSWORD, self::$DB_DATABASE, self::$DB_PORT);
        if (self::$connection->connect_error) {
            throw new \Exception('Error: ' . self::$connection->error . PHP_EOL);
        }

        self::$connection->set_charset("utf8");
        self::$connection->query("SET SQL_MODE = ''");
    }

    public static function connect($hostname, $username, $password, $database, $port = '3306')
    {
        if (self::$connection === null) {
            self::$DB_HOST = $hostname;
            self::$DB_USERNAME = $username;
            self::$DB_PASSWORD = $password;
            self::$DB_DATABASE = $database;
            self::$DB_PORT = $port;
            new self;
        }
    }

    public static function getInstance()
    {
        if (self::$connection != null) {
            return self::$connection;
        }

        return new self;
    }

    public static function query($sql)
    {
        $query = self::$connection->query($sql);
        if (!self::$connection->errno) {
            if ($query instanceof \mysqli_result) {
                $data = array();

                while ($row = $query->fetch_assoc()) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : array();
                $result->rows = $data;

                $query->close();

                return $result;
            } else {
                return true;
            }
        } else {
            throw new \Exception('Error: ' . self::$connection->error . PHP_EOL);
        }
    }

    public static function escape($value)
    {
        return self::$connection->real_escape_string($value);
    }

    public static function countAffected()
    {
        return self::$connection->affected_rows;
    }

    public static function getLastId()
    {
        return self::$connection->insert_id;
    }

    public static function connected()
    {
        return self::$connection->ping();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /*private function __destruct()
    {
        self::$connection->close();
    }*/
}
