<?php

namespace Models;

use Interfaces\Locavel;

class Vestido_l extends Roupa implements Locavel
{
    private ?string $imagem;

    public function __construct(string $nome, string $marca, ?string $imagem = null)
    {
        parent::__construct($nome, $marca);
        $this->imagem = $imagem;
    }

    public function setImagem(?string $imagem): void
    {
        $this->imagem = $imagem;
    }

    public function getImagem(): ?string
    {
        return $this->imagem;
    }
    public function calcularAluguel(int $dias): float
    {
        return $dias * DIARIA_VESTIDO_L;
    }

    public function alugar(): string
    {
        if ($this->isDisponivel()) {
            $this->setDisponivel(false);
            return "Vestido longo '{$this->getNome()}' alugado com sucesso!";
        }
        return "Vestido longo '{$this->getNome()}' não está disponível.";
    }

    public function devolver(): string
    {
        if (!$this->isDisponivel()) {
            $this->setDisponivel(true);
            return "Vestido longo '{$this->getNome()}' devolvido com sucesso!";
        }
        return "Vestido longo '{$this->getNome()}' já está disponível.";
    }
}
