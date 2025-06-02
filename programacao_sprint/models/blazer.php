<?php
namespace Models;
use Interfaces\Locavel;

class Blazer extends Roupa implements Locavel {
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

    public function calcularAluguel(int $dias): float {
        return $dias * DIARIA_BLAZER;
    }

    public function alugar(): string {
        if ($this->isDisponivel()) {
            $this->setDisponivel(false);
            return "Blazer '{$this->getNome()}' alugada com sucesso!";
        }
        return "Blazer '{$this->getNome()}' não está disponível.";
    }

    public function devolver(): string {
        if (!$this->isDisponivel()) {
            $this->setDisponivel(true);
            return "Blazer '{$this->getNome()}' devolvida com sucesso!";
        }
        return "Blazer '{$this->getNome()}' já está disponível.";
    }
}
?>
