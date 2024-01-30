<?php
class ClienteDAO
{
    private PDO $conn;

    function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function getTodosClientes() : array
    {
        echo "Método ClienteDAO->getTodosClientes ainda não implementado";
        return [];
    }
}
?>
