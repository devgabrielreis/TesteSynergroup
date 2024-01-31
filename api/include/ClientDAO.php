<?php
require_once(__DIR__ . "/Client.php");

class ClientDAO
{
    private PDO $conn;

    function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function buildClient(?string $code, ?string $clientName, ?int $currencyCode, ?string $creationDate, ?string $lastSaleDate, ?float $totalSales) : Client
    {
        $client = new Client();

        $client->setCode($code);
        $client->setClientName($clientName);
        $client->setCurrencyCode($currencyCode);
        $client->setCreationDate($creationDate);
        $client->setLastSaleDate($lastSaleDate);
        $client->setTotalSales($totalSales);

        return $client;
    }

    function getAll() : array
    {
        $stmt = $this->conn->prepare("SELECT CLI_CODIGO, CLI_RZSOC, CLI_MOEDA, CLI_DTCRE, CLI_DTULTVDA, CLI_VRVDA FROM clientes");
        $stmt->execute();

        $data = $stmt->fetchAll();

        $clients = [];

        foreach ($data as $client)
        {
            $clients[] = $this->buildClient(
                $client["CLI_CODIGO"],
                $client["CLI_RZSOC"],
                $client["CLI_MOEDA"],
                $client["CLI_DTCRE"],
                $client["CLI_DTULTVDA"],
                $client["CLI_VRVDA"]
            );
        }

        return $clients;
    }
}
?>
