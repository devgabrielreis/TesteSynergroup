<?php
class Moeda
{
    private ?int $codigo;
    private ?string $sigla;
    private ?int $quantCasasDecimais;

    public function setCodigo(int $codigo) : void
    {
        $this->codigo = $codigo;
    }

    public function getCodigo() : ?int
    {
        return $this->codigo;
    }

    public function setSigla(string $sigla) : void
    {
        $this->sigla = $sigla;
    }

    public function getSigla() : ?string
    {
        return $this->sigla;
    }

    public function setQuantCasasDecimais(int $quantCasasDecimais) : void
    {
        $this->quantCasasDecimais = $quantCasasDecimais;
    }

    public function getQuantCasasDecimais() : ?int
    {
        return $this->quantCasasDecimais;
    }
}
?>
