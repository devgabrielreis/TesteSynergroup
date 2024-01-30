<?php
class Cliente
{
    private ?string $codigo;
    private ?string $razaoSocial;
    private ?int $codigoMoeda;
    private ?string $dataCriacao;
    private ?string $dataUltimaVenda;
    private ?float $valorVendas;

    public function setCodigo(string $codigo) : void
    {
        $this->codigo = $codigo;
    }

    public function getCodigo() : ?string
    {
        return $this->codigo;
    }

    public function setRazaoSocial(string $razaoSocial) : void
    {
        $this->razaoSocial = $razaoSocial;
    }

    public function getRazaoSocial() : ?string
    {
        return $this->razaoSocial;
    }

    public function setCodigoMoeda(int $codigoMoeda) : void
    {
        $this->codigoMoeda = $codigoMoeda;
    }

    public function getCodigoMoeda() : ?int
    {
        return $this->codigoMoeda;
    }

    public function setDataCriacao(int $dataCriacao) : void
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function getDataCriacao() : ?string
    {
        return $this->dataCriacao;
    }

    public function setDataUltimaVenda(int $dataUltimaVenda) : void
    {
        $this->dataUltimaVenda = $dataUltimaVenda;
    }

    public function getDataUltimaVenda() : ?string
    {
        return $this->dataUltimaVenda;
    }

    public function setValorVendas(int $valorVendas) : void
    {
        $this->valorVendas = $valorVendas;
    }

    public function getValorVendas() : ?float
    {
        return $this->valorVendas;
    }
}
?>
