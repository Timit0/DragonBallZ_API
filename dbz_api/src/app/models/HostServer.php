<?php

require __DIR__ . '/../../vendor/autoload.php';

class HostServer
{
    public int $id;
    public User $user_host;
    public User $user_guest;
    public string $host_ip;

    function __construct(User $user_host = null, $host_ip = "")
    {
        $this->user_host = $user_host;
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
}