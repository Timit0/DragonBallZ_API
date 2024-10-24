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

    public function add_in_db() : bool
    {
        //Stop if is in db
        if($this->is_in_db())
        {
            return false;
        }

        if($this->username == null || $this->password == null)
        {
            return false;
        }

        $query = "INSERT INTO dbz_database.Users (username,password) VALUES (:username,:password);";
        $stmt = DB::getInstance()->prepare($query);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);

        return $stmt->execute();
    }

    function is_in_db() : bool
    {
        $query = "SELECT COUNT(dbz_database.Users.username) AS number FROM dbz_database.Users WHERE UPPER(dbz_database.Users.username) = UPPER(:username);";
        $stmt = DB::getInstance()->prepare($query);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
        $number_of_this_in_db = $stmt->fetch()['number'];

        //if user is NOT in db
        if($number_of_this_in_db === 0)
        {
            return false;
        }
        return true;
    }
}