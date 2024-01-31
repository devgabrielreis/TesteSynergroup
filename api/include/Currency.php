<?php
class Currency implements \JsonSerializable
{
    private ?int $code;
    private ?string $abbreviation;
    private ?int $decimalPlaces;

    public function setCode(?int $code) : void
    {
        $this->code = $code;
    }

    public function getCode() : ?int
    {
        return $this->code;
    }

    public function setAbbreviation(?string $abbreviation) : void
    {
        $this->abbreviation = $abbreviation;
    }

    public function getAbbreviation() : ?string
    {
        return $this->abbreviation;
    }

    public function setDecimalPlaces(?int $decimalPlaces) : void
    {
        $this->decimalPlaces = $decimalPlaces;
    }

    public function getDecimalPlaces() : ?int
    {
        return $this->decimalPlaces;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
?>
