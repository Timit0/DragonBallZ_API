<?php

require __DIR__ . '/../../vendor/autoload.php';

class HostServer
{
    public int $id;
    public ?User $user_host;
    public ?User $user_guest;
    public string $host_ip;

    function __construct(int $id = 0, User $user_host = null, User $user_guest = null, $host_ip = "")
    {
        $this->id = $id;
        $this->user_host = $user_host;
        $this->user_guest = $user_guest;
        $this->host_ip = $host_ip;
    }

    public static function add_host_server(string $host_username, string $host_ip) : bool
    {
        $sql = "INSERT INTO dbz_database.HostServers (id_user_host, host_ip) VALUES (:id_user_host, :host_ip);";
        $stmt = DB::getInstance()->prepare($sql);

        $user_with_id = User::get_user_by_name($host_username);
        $user_id = $user_with_id->id;

        $stmt->bindParam(":id_user_host", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":host_ip", $host_ip);

        return $stmt->execute();
    } 

    public static function delete_host_server(string $username) : bool
    {
        $sql = "DELETE FROM dbz_database.HostServers WHERE id_user_host = :id_user_host;";
        $stmt = DB::getInstance()->prepare($sql);

        $user_with_id = User::get_user_by_name($username);
        $user_id = $user_with_id->id;

        $stmt->bindParam(":id_user_host", $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function get_all_available_host_server() : array
    {
        $query = "SELECT 
            UserHost.username AS host_username, 
            H.host_ip 
        FROM 
            dbz_database.HostServers AS H
        JOIN 
            dbz_database.Users AS UserHost ON H.id_user_host = UserHost.id
        WHERE 
            H.id_user_guest IS NULL;";

        $stmt = DB::getInstance()->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $array = [];
        foreach ($results as $result) {
            $user_host = User::get_user_by_name($result['host_username']);
            $user_host_without_password = new User(username: $user_host->username);
            
            $array[] = new HostServer(
                user_host: $user_host_without_password,
                host_ip: $result['host_ip']
            );
        }

        return $array;
    }

    public static function add_guest_user(string $username_of_guest_to_add, string $username_of_host) : bool
    {
        $sql = "UPDATE HostServers
        SET id_user_guest = :id_user_guest
        WHERE id_user_host = :id_user_host;";

        $stmt = DB::getInstance()->prepare($sql);

        $user_guest = User::get_user_by_name($username_of_guest_to_add);
        $user_host = User::get_user_by_name($username_of_host);

        $stmt->bindParam(":id_user_guest", $user_guest->id, PDO::PARAM_INT);
        $stmt->bindParam(":id_user_host", $user_host->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function can_add_guest_user(string $username_of_host) : bool
    {
        $sql = "SELECT *, CASE WHEN id_user_guest IS NULL THEN 1 ELSE 0 END AS is_user_guest_null
        FROM 
            dbz_database.HostServers hs
        WHERE 
            id_user_host = :id_user_host;";


        $stmt = DB::getInstance()->prepare($sql);

        $user_host = User::get_user_by_name($username_of_host);

        $stmt->bindParam(":id_user_host", $user_host->id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // if ($result && $result['is_user_guest_null'] === 'true') {
        //     return true;
        // }
        return (bool)$result['is_user_guest_null'];
    }

    public static function test(string $username_of_host)
    {
        $sql = "SELECT *, CASE WHEN id_user_guest IS NULL THEN 'true' ELSE 'false' END AS is_user_guest_null
        FROM 
            dbz_database.HostServers hs
        WHERE 
            id_user_host = :id_user_host;";


        $stmt = DB::getInstance()->prepare($sql);

        $user_host = User::get_user_by_name($username_of_host);

        $stmt->bindParam(":id_user_host", $user_host->id, PDO::PARAM_INT);

        $stmt->execute();

        return $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}