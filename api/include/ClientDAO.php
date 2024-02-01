<?php
require_once(__DIR__ . "/utils.php");
require_once(__DIR__ . "/Client.php");
require_once(__DIR__ . "/CurrencyDAO.php");

class ClientDAO
{
    private PDO $conn;
    const MAX_TOTAL_SALES_VALUE = 999999.9999;

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

    function validateNewClient(CurrencyDAO $currencyDAO, string $code, string $name, int $currencyCode) : void
    {
        if(strlen($code) === 0)
        {
            throw new Exception("O código do cliente esta vazio");
        }

        if(strlen($code) > 50)
        {
            throw new Exception("O código do cliente deve ter no máximo 50 caracteres");
        }

        if($this->getClient($code) !== null)
        {
            throw new Exception("Já existe um cliente com este código");
        }

        if(strlen($name) === 0)
        {
            throw new Exception("A razão social do cliente esta vazia");
        }

        if(strlen($name) > 150)
        {
            throw new Exception("A razão social do cliente deve ter no máximo 150 caracteres");
        }

        if($currencyDAO->getCurrency($currencyCode) === null)
        {
            throw new Exception("Essa moeda não existe");
        }
    }

    function addClient(CurrencyDAO $currencyDAO, string $code, string $name, int $currencyCode) : void
    {
        $this->validateNewClient($currencyDAO, $code, $name, $currencyCode);

        $creationDate = date("Y-m-d");

        $stmt = $this->conn->prepare("INSERT INTO clientes (
            CLI_CODIGO, CLI_RZSOC, CLI_MOEDA, CLI_DTCRE, CLI_VRVDA
        ) VALUES (
            :code, :name, :currencyCode, :creationDate, 0
        )");

        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":currencyCode", $currencyCode);
        $stmt->bindParam(":creationDate", $creationDate);

        $stmt->execute();
    }

    function validateUpdateClient(CurrencyDAO $currencyDAO, string $oldCode, ?string $newCode, ?string $newName, ?int $newCurrencyCode, ?string $newLastSaleDate, ?float $newTotalSales)
    {
        $client = $this->getClient($oldCode);

        if($client === null)
        {
            throw new Exception("Não existe nenhum cliente com o código " . $oldCode);
        }

        if($newCode !== null)
        {
            if(strlen($newCode) === 0)
            {
                $newCode = null;
            }
            if(strlen($newCode) > 50)
            {
                throw new Exception("O código do cliente deve ter no máximo 50 caracteres");
            }
            if($this->getClient($newCode) !== null)
            {
                throw new Exception("Já existe um cliente com este código");
            }
        }

        if($newName !== null)
        {
            if(strlen($newName) === 0)
            {
                $newName = null;
            }
            if(strlen($newName) > 150)
            {
                throw new Exception("A razão social do cliente deve ter no máximo 150 caracteres");
            }
        }

        if($newCurrencyCode !== null)
        {
            if($newCurrencyCode === "")
            {
                $newCurrencyCode = null;
            }
            else if($currencyDAO->getCurrency($newCurrencyCode) === null)
            {
                throw new Exception("Essa moeda não existe");
            }
        }

        if($newLastSaleDate !== null)
        {
            if($newLastSaleDate === "")
            {
                $newLastSaleDate = null;
            }
            else if(!checkStringDateFormat($newLastSaleDate, "Y-m-d"))
            {
                throw new Exception("A nova data de última venda está no formato incorreto");
            }
        }

        if($newTotalSales !== null)
        {
            if($newTotalSales === "")
            {
                $newTotalSales = null;
            }
            else if($newTotalSales < 0)
            {
                throw new Exception("O total de vendas deve ser um número positivo");
            }
            if($newTotalSales > $this::MAX_TOTAL_SALES_VALUE)
            {
                throw new Exception("O total de vendas deve ser um número menor do que " . strval($this::MAX_TOTAL_SALES_VALUE));
            }

            $currencyCode = ($newCurrencyCode === null) ? $client->getCurrencyCode() : $newCurrencyCode;

            $currency = $currencyDAO->getCurrency($currencyCode);

            if(checkFloatDecimalPlaces($newTotalSales) > $currency->getDecimalPlaces())
            {
                throw new Exception("O valor total de vendas de um cliente com a moeda " . $currency->getAbbreviation() . " ser um número com no máximo " . strval($currency->getDecimalPlaces()) . " casas decimais");
            }
        }
    }

    function updateClient(CurrencyDAO $currencyDAO, string $oldCode, ?string $newCode, ?string $newName, ?int $newCurrencyCode, ?string $newLastSaleDate, ?float $newTotalSales)
    {
        $this->validateUpdateClient($currencyDAO, $oldCode, $newCode, $newName, $newCurrencyCode, $newLastSaleDate, $newTotalSales);

        $client = $this->getClient($oldCode);
        $code = ($newCode !== null) ? $newCode : $oldCode;
        $name = ($newName !== null) ? $newName : $client->getClientName();
        $currencyCode = ($newCurrencyCode !== null) ? $newCurrencyCode : $client->getCurrencyCode();
        $lastSaleDate = ($newLastSaleDate !== null) ? $newLastSaleDate : $client->getLastSaleDate();
        $totalSales = ($newTotalSales !== null) ? $newTotalSales : $client->getTotalSales();

        $stmt = $this->conn->prepare("UPDATE clientes SET
            CLI_CODIGO = :code, CLI_RZSOC = :name, CLI_MOEDA = :currencyCode, CLI_DTULTVDA = :lastSaleDate, CLI_VRVDA = :totalSales
            WHERE CLI_CODIGO = :oldCode"
        );

        $stmt->bindParam(":oldCode", $oldCode);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":currencyCode", $currencyCode);
        $stmt->bindParam(":lastSaleDate", $lastSaleDate);
        $stmt->bindParam(":totalSales", $totalSales);

        $stmt->execute();
    }

    function removeClient(Client $client)
    {
        $clientCode = $client->getCode();

        if($clientCode === null)
        {
            throw new Exception("Cliente com razão social nula passado como parâmetro para ClientDAO->removeClient");
        }

        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE CLI_CODIGO = :code");
        $stmt->bindParam(":code", $clientCode);
        $stmt->execute();
    }
}
?>
