<?php

namespace Models;

// Classe abstrata para todos os tipos de Roupas
abstract class Roupa
{
    private string $nome;
    private string $marca;
    private bool   $disponivel;
    private ?string $imagem;  // Adiciona a variável para armazenar o nome do arquivo da imagem

    public function __construct(string $nome, string $marca, ?string $imagem = null)
    {
        $this->nome       = $nome;
        $this->marca      = $marca;
        $this->disponivel = true;
        $this->imagem     = $imagem;  // Inicializa a imagem
    }

    // Função para cálculo de aluguel
    abstract public function calcularAluguel(int $dias): float;

    public function isDisponivel(): bool
    {
        return $this->disponivel;
    }

    // Em Roupa.php
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setMarca(string $marca): void
    {
        $this->marca = $marca;
    }


    public function getNome(): string
    {
        return $this->nome;
    }

    public function getMarca(): string
    {
        return $this->marca;
    }

    public function setDisponivel(bool $disponivel): void
    {
        $this->disponivel = $disponivel;
    }

    // Método para obter a imagem
    public function getImagem(): ?string
    {
        return $this->imagem;
    }

    // Método para definir a imagem
    public function setImagem(?string $imagem): void
    {
        $this->imagem = $imagem;
    }
}
