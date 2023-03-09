<?php

class DB_Connection
{
    function connect()
    {
        $host = "localhost";
        $dbname = "chatbot";
        $user = "postgres";
        $pass = "1337";

        $connect = new PDO("pgsql:host=$host; dbname=$dbname", $user, $pass);
        return $connect;
    }
}

?>
