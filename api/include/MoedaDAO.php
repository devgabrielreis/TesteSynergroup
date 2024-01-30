<?php
class MoedaDAO
{
    private PDO $conn;

    function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function getTodasMoedas() : array
    {
        echo "Método MoedaDAO->getTodasMoedas ainda não implementado";
        return [];
    }
}
?>