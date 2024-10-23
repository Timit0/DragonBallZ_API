<?php
class DB extends PDO
{
    private static ?DB $instance = null;

    protected string $hostname = "localhost";
    protected string $dbname = "dbz_database";
    protected string $username = "root";
    protected string $password = "123456";

    private function __construct()
    {
        parent::__construct("mysql:host=$this->hostname;dbname=$this->dbname", $this->username, $this->password);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): DB
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
