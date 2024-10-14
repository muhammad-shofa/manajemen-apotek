<?php

class database
{
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "manajemen_apotek";
    private $connection;

    // try connection
    function __construct()
    {
        $this->connection = mysqli_connect($this->hostname, $this->username, $this->password, $this->db_name);
    }

    // connected
    public function connected()
    {
        return $this->connection;
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}

$db = new database();
$connected = $db->connected();