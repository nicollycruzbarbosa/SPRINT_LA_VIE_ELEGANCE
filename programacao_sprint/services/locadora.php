<?php

namespace Services;

use Models\{Roupa, Terno_c, Smoking, Blazer, Vestido_l, Vestido_c, Vestido_d};

class Locadora
{
    private array $roupas = [];

    public function __construct()
    {
        $this->carregarRoupas();
    }

    private function carregarRoupas(): void
    {
        if (file_exists(ARQUIVO_JSON)) {
            $dados = json_decode(file_get_contents(ARQUIVO_JSON), true);

            foreach ($dados as $dado) {
                $imagem = $dado['imagem'] ?? null;

                switch ($dado['tipo']) {
                    case 'Terno_c':
                        $roupa = new Terno_c($dado['nome'], $dado['marca'], $imagem);
                        break;
                    case 'Smoking':
                        $roupa = new Smoking($dado['nome'], $dado['marca'], $imagem);
                        break;
                    case 'Blazer':
                        $roupa = new Blazer($dado['nome'], $dado['marca'], $imagem);
                        break;
                    case 'Vestido_l':
                        $roupa = new Vestido_l($dado['nome'], $dado['marca'], $imagem);
                        break;
                    case 'Vestido_c':
                        $roupa = new Vestido_c($dado['nome'], $dado['marca'], $imagem);
                        break;
                    case 'Vestido_d':
                        $roupa = new Vestido_d($dado['nome'], $dado['marca'], $imagem);
                        break;
                    default:
                        continue 2; // pula para o próximo se tipo for inválido
                }

                $roupa->setDisponivel($dado['disponivel']);
                $this->roupas[] = $roupa;
            }
        }
    }

    private function salvarRoupas(): void
    {
        $dados = [];

        foreach ($this->roupas as $roupa) {
            $dados[] = [
                'tipo' => match (true) {
                    $roupa instanceof Terno_c   => 'Terno_c',
                    $roupa instanceof Smoking   => 'Smoking',
                    $roupa instanceof Blazer    => 'Blazer',
                    $roupa instanceof Vestido_l => 'Vestido_l',
                    $roupa instanceof Vestido_c => 'Vestido_c',
                    $roupa instanceof Vestido_d => 'Vestido_d',
                    default                     => 'Desconhecido'
                },
                'nome' => $roupa->getNome(),
                'marca' => $roupa->getMarca(),
                'imagem' => $roupa->getImagem() ?? null,
                'disponivel' => $roupa->isDisponivel()
            ];
        }

        $dir = dirname(ARQUIVO_JSON);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents(ARQUIVO_JSON, json_encode($dados, JSON_PRETTY_PRINT));
    }

    public function adicionarRoupa(Roupa $roupa): string
    {
        foreach ($this->roupas as $r) {
            if ($r->getNome() === $roupa->getNome() && $r->getMarca() === $roupa->getMarca()) {
                return "Roupa já cadastrada!";
            }
        }

        $this->roupas[] = $roupa;
        $this->salvarRoupas();
        return "Roupa '{$roupa->getNome()}' adicionada com sucesso!";
    }

    public function deletarRoupa(string $nome, string $marca): string
    {
        foreach ($this->roupas as $key => $roupa) {
            if ($roupa->getNome() === $nome && $roupa->getMarca() === $marca) {
                unset($this->roupas[$key]);
                $this->roupas = array_values($this->roupas);
                $this->salvarRoupas();
                return "Vestimenta '{$nome}' removida com sucesso!";
            }
        }
        return "Roupa não encontrada!";
    }

    public function alugarRoupa(string $nome, int $dias = 1): string
    {
        foreach ($this->roupas as $roupa) {
            if ($roupa->getNome() === $nome && $roupa->isDisponivel()) {
                $valorAluguel = $roupa->calcularAluguel($dias);
                $mensagem = $roupa->alugar();
                $this->salvarRoupas();
                return $mensagem . " Valor do aluguel: R$" . number_format($valorAluguel, 2, ',', '.');
            }
        }
        return "Roupa não disponível";
    }

    public function devolverRoupa(string $nome): string
    {
        foreach ($this->roupas as $roupa) {
            if ($roupa->getNome() === $nome && !$roupa->isDisponivel()) {
                $mensagem = $roupa->devolver();
                $this->salvarRoupas();
                return $mensagem;
            }
        }
        return "Roupa já disponível ou não encontrada.";
    }

    public function editarRoupa(string $nomeOriginal, string $novoNome, string $novaMarca, string $novoTipo, ?string $imagemNova = null): string
    {
        foreach ($this->roupas as $key => $roupa) {
            // Verifica se a roupa corresponde ao nome original
            if ($roupa->getNome() === $nomeOriginal) {
                // Atualiza os dados da roupa
                $roupa->setNome($novoNome);
                $roupa->setMarca($novaMarca);

                // Atualiza a imagem se fornecida
                if ($imagemNova) {
                    $roupa->setImagem($imagemNova);
                }

                // Verifica qual tipo de roupa será instanciada
                switch ($novoTipo) {
                    case 'Terno_c':
                        $roupaAtualizada = new Terno_c($novoNome, $novaMarca, $imagemNova ?? $roupa->getImagem());
                        break;
                    case 'Smoking':
                        $roupaAtualizada = new Smoking($novoNome, $novaMarca, $imagemNova ?? $roupa->getImagem());
                        break;
                    case 'Blazer':
                        $roupaAtualizada = new Blazer($novoNome, $novaMarca, $imagemNova ?? $roupa->getImagem());
                        break;
                    case 'Vestido_l':
                        $roupaAtualizada = new Vestido_l($novoNome, $novaMarca, $imagemNova ?? $roupa->getImagem());
                        break;
                    case 'Vestido_c':
                        $roupaAtualizada = new Vestido_c($novoNome, $novaMarca, $imagemNova ?? $roupa->getImagem());
                        break;
                    case 'Vestido_d':
                        $roupaAtualizada = new Vestido_d($novoNome, $novaMarca, $imagemNova ?? $roupa->getImagem());
                        break;
                    default:
                        return "Tipo de roupa inválido.";
                }

                // Substitui a roupa original pela atualizada
                $this->roupas[$key] = $roupaAtualizada;
                $this->salvarRoupas();  // Salva as roupas após a atualização
                return "Roupa '{$novoNome}' editada com sucesso!";
            }
        }

        return "Roupa não encontrada!";
    }

    public function listarRoupas(): array
    {
        return $this->roupas;
    }

    public function calcularPrevisaoAluguel($tipo, $dias, $quantidade): float
    {
        return match ($tipo) {
            'Terno_c'   => (new Terno_c('', '', null))->calcularAluguel($dias) * $quantidade,
            'Smoking'   => (new Smoking('', '', null))->calcularAluguel($dias) * $quantidade,
            'Blazer'    => (new Blazer('', '', null))->calcularAluguel($dias) * $quantidade,
            'Vestido_l' => (new Vestido_l('', '', null))->calcularAluguel($dias) * $quantidade,
            'Vestido_c' => (new Vestido_c('', '', null))->calcularAluguel($dias) * $quantidade,
            'Vestido_d' => (new Vestido_d('', '', null))->calcularAluguel($dias) * $quantidade,
            default     => 0.0,
        };
    }
}
