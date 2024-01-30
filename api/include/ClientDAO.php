<?php
class ClientDAO
{
    private PDO $conn;

    function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function getAll() : array
    {
        return [];
    }
}
?>
