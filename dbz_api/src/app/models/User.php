<?php
// namespace dbz_api;
class User
{
    public string $username;
    public string $password;

    function __construct($username = "", $password = "")
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function add_in_db() : void
    {
        $query = "INSERT INTO dbz_database.Users (username,password) VALUES (:username,:password);";
        $stmt = DB::getInstance()->prepare($query);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);

        $stmt->execute();
    }
}