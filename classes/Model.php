<?php
namespace Classes;
use Services\DB;
class Model extends DB {
    public function __construct()
    {
        DB::connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        DB::getInstance();
    }
    public function __destruct()
    {

    }
}