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

    function getClient(string $clientCode) : Client|null
    {
        $stmt = $this->conn->prepare("SELECT
            CLI_RZSOC, CLI_MOEDA, CLI_DTCRE, CLI_DTULTVDA, CLI_VRVDA
            FROM clientes WHERE CLI_CODIGO = :code"
        );
        $stmt->bindParam(":code", $clientCode);
        $stmt->execute();

        if($stmt->rowCount() === 0)
        {
            return null;
        }

        $data = $stmt->fetch();

        return $this->buildClient(
            $clientCode,
            $data["CLI_RZSOC"],
            $data["CLI_MOEDA"],
            $data["CLI_DTCRE"],
            $data["CLI_DTULTVDA"],
            $data["CLI_VRVDA"]
        );
    }

    function removeClient(Client $client)
    {
        $clientCode = $client->getCode();

        if($clientCode === null)
        {
            throw new Exception("Cliente com razão social nula passado como parâmetro para ClientDAO->removeClient");
        }

        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE CLI_RZSOC = :code");
        $stmt->bindParam(":code", $clientCode);
        $stmt->execute();
    }
}
?>
