<?php

namespace Models;

use Interfaces\Locavel;

class Terno_c extends Roupa implements Locavel
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
        return $dias * DIARIA_TERNO_C;
    }

    public function alugar(): string
    {
        if ($this->isDisponivel()) {
            $this->setDisponivel(false);
            return "Terno '{$this->getNome()}' alugado com sucesso!";
        }
        return "Terno '{$this->getNome()}' não está disponível.";
    }

    public function devolver(): string
    {
        if (!$this->isDisponivel()) {
            $this->setDisponivel(true);
            return "Terno '{$this->getNome()}' devolvido com sucesso!";
        }
        return "Terno '{$this->getNome()}' já está disponível.";
    }
}
