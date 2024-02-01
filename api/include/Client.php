<?php
class Client implements \JsonSerializable
{
    private ?string $code;
    private ?string $clientName;
    private ?int $currencyCode;
    private ?string $creationDate;
    private ?string $lastSaleDate;
    private ?float $totalSales;
    private ?string $currencyAbbreviation;
    private ?int $currencyDecimalPlaces;

    public function setCode(?string $code) : void
    {
        $this->code = $code;
    }

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function setClientName(?string $clientName) : void
    {
        $this->clientName = $clientName;
    }

    public function getClientName() : ?string
    {
        return $this->clientName;
    }

    public function setCurrencyCode(?int $currencyCode) : void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getCurrencyCode() : ?int
    {
        return $this->currencyCode;
    }

    public function setCreationDate(?string $creationDate) : void
    {
        $this->creationDate = $creationDate;
    }

    public function getCreationDate() : ?string
    {
        return $this->creationDate;
    }

    public function setLastSaleDate(?string $lastSaleDate) : void
    {
        $this->lastSaleDate = $lastSaleDate;
    }

    public function getLastSaleDate() : ?string
    {
        return $this->lastSaleDate;
    }

    public function setTotalSales(?float $totalSales) : void
    {
        $this->totalSales = $totalSales;
    }

    public function getTotalSales() : ?float
    {
        return $this->totalSales;
    }

    public function setCurrencyAbbreviation(?string $currencyAbbreviation) : void
    {
        $this->currencyAbbreviation = $currencyAbbreviation;
    }

    public function getCurrencyAbbreviation() : ?string
    {
        return $this->currencyAbbreviation;
    }

    public function setCurrencyDecimalPlaces(?int $currencyDecimalPlaces) : void
    {
        $this->currencyDecimalPlaces = $currencyDecimalPlaces;
    }

    public function getCurrencyDecimalPlaces() : ?int
    {
        return $this->currencyDecimalPlaces;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
?>
