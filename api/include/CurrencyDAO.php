<?php
require_once(__DIR__ . "/Currency.php");

class CurrencyDAO
{
    private PDO $conn;

    function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    function buildCurrency(?int $code, ?string $abbreviation, ?int $decimalPlaces) : Currency
    {
        $currency = new Currency();

        $currency->setCode($code);
        $currency->setAbbreviation($abbreviation);
        $currency->setDecimalPlaces($decimalPlaces);

        return $currency;
    }

    function getAll() : array
    {
        $stmt = $this->conn->prepare("SELECT MOEDA_CODIGO, MOEDA_SIGLA, MOEDA_QDEC FROM moedas");
        $stmt->execute();

        $data = $stmt->fetchAll();

        $currencies = [];

        foreach ($data as $currency)
        {
            $currencies[] = $this->buildCurrency($currency["MOEDA_CODIGO"], $currency["MOEDA_SIGLA"], $currency["MOEDA_QDEC"]);
        }

        return $currencies;
    }

    function getCurrency(int $code) : Currency|null
    {
        $stmt = $this->conn->prepare("SELECT MOEDA_SIGLA, MOEDA_QDEC FROM moedas WHERE MOEDA_CODIGO = :code");
        $stmt->bindParam(":code", $code);
        $stmt->execute();

        if($stmt->rowCount() === 0)
        {
            return null;
        }

        $data = $stmt->fetch();

        return $this->buildCurrency($code, $data["MOEDA_SIGLA"], $data["MOEDA_QDEC"]);
    }
}
?>
