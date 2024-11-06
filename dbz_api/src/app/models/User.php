<?php
// namespace dbz_api;
class User
{
    public string $id;
    public string $username;
    public string $password;

    function __construct($id = 0, $username = "", $password = "")
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public static function get_user_by_name(string $name) : User|null
    {
        $query = "SELECT * FROM dbz_database.Users WHERE dbz_database.Users.username = :name;";
        $stmt = DB::getInstance()->prepare($query);

        $stmt->bindParam(":name", $name);

        $stmt->execute();
        $number_of_line = $stmt->rowCount();
        $result = $stmt->fetch();

        if($number_of_line == 0)
        {
            return null;
        }
        
        return new User(id: $result['id'], username: $result['username'], password: $result['password']);
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
        $pass_hash = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindParam(":password", $pass_hash);

        return $stmt->execute();
    }

    public function can_log() : bool
    {
        $user = $this->get_user_by_name($this->username);
        if($user == null)
        {
            return false;
        }
        return password_verify($this->password, $user->password);
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