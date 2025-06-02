<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir o autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Incluir o arquivo com as variáveis
require_once __DIR__ . '/../config/config.php';

session_start();

use Services\{Locadora, Auth};
use Models\{Terno_c, Smoking, Blazer, Vestido_l, Vestido_c, Vestido_d};

// Verificar se o usuário está logado
if (!Auth::verificarLogin()) {
    header('Location: login.php');
    exit;
}

// Condição para logout
if (isset($_GET['logout'])) {
    (new Auth())->logout();
    header('Location: login.php');
    exit;
}

$locadora = new Locadora();
$mensagem = '';
$usuario = Auth::getUsuario();

// Verificar dados do formulário via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifica permissão de admin para ações restritas
    if (isset($_POST['adicionar']) || isset($_POST['deletar']) || isset($_POST['devolver'])) {
        if (!Auth::isAdmin()) {
            $mensagem = "Você não tem permissão para realizar essa ação";
            goto renderizar;
        }
    }

    // Adicionar roupa
    if (isset($_POST['adicionar'])) {
        $nome  = $_POST['nome']  ?? '';
        $marca = $_POST['marca'] ?? '';
        $tipo  = $_POST['tipo']  ?? '';
        $imagem = null;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagemNome = uniqid() . '.' . $ext;
            $caminhoDestino = __DIR__ . '/../img/' . $imagemNome;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoDestino)) {
                $imagem = '../img/' . $imagemNome; // Salva o caminho da imagem
            }
        }

        switch ($tipo) {
            case 'Terno_c':
                $roupa = new Terno_c($nome, $marca, $imagem);
                break;
            case 'Smoking':
                $roupa = new Smoking($nome, $marca, $imagem);
                break;
            case 'Blazer':
                $roupa = new Blazer($nome, $marca, $imagem);
                break;
            case 'Vestido_l':
                $roupa = new Vestido_l($nome, $marca, $imagem);
                break;
            case 'Vestido_c':
                $roupa = new Vestido_c($nome, $marca, $imagem);
                break;
            case 'Vestido_d':
                $roupa = new Vestido_d($nome, $marca, $imagem);
                break;
            default:
                $mensagem = "Tipo de roupa inválido.";
                goto renderizar;
        }

        // Adiciona e armazena a mensagem de retorno
        $mensagem = $locadora->adicionarRoupa($roupa);
    }

    // Alugar
    elseif (isset($_POST['alugar'])) {
        $dias = isset($_POST['dias']) ? (int)$_POST['dias'] : 1;
        $mensagem = $locadora->alugarRoupa($_POST['nome'], $dias);
    }

    // Devolver
    elseif (isset($_POST['devolver'])) {
        $mensagem = $locadora->devolverRoupa($_POST['nome']);
    }

    // Deletar
    elseif (isset($_POST['deletar'])) {
        $mensagem = $locadora->deletarRoupa($_POST['nome'], $_POST['marca']);
    }

    // Calcular previsão de valor
    elseif (isset($_POST['calcular'])) {
        $dias = (int)$_POST['dias_calculo'];
        $tipo = $_POST['tipo_calculo'];
        $roupinha = match ($tipo) {
            'Terno_c' => 'Terno Completo',
            'Smoking' => 'Smoking',
            'Blazer' => 'Blazer',
            'Vestido_l' => 'Vestido Longo',
            'Vestido_c' => 'Vestido Curto',
            'Vestido_d' => 'Vestido de Debutante',
            default => null
        };
        $quantidade = (int)($_POST['quantidade_pecas'] ?? 1); // Pegando a quantidade, padrão 1

        $valor = $locadora->calcularPrevisaoAluguel($tipo, $dias, $quantidade);

        $mensagem = "Previsão de valor para {$quantidade} peça(s) de {$roupinha} por {$dias} dia(s): R$" . number_format($valor, 2, ',', '.');
    }


    // Editar roupa
    elseif (isset($_POST['editar'])) {
        $nomeOriginal = $_POST['nome_original'];  // O nome da roupa a ser editada
        $novoNome     = $_POST['nome'];  // O novo nome
        $novaMarca    = $_POST['marca'];  // A nova marca
        $novoTipo     = $_POST['tipo'];  // O novo tipo da roupa
        $imagemAtual  = $_POST['imagem_atual'] ?? null;  // Se tiver imagem atual
        $imagemNova   = null;  // Nova imagem, caso enviada

        // Se uma nova imagem foi enviada, usa ela; senão mantém a antiga
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagemNome = uniqid() . '.' . $ext;
            $caminhoDestino = __DIR__ . '/../img/' . $imagemNome;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoDestino)) {
                $imagemNova = '../img/' . $imagemNome;
            }
        }

        // Se não tiver nova imagem, usa a imagem atual
        $imagemFinal = $imagemNova ?: $imagemAtual;

        // Criação da nova roupa com as informações editadas
        switch ($novoTipo) {
            case 'Terno_c':
                $roupa = new Terno_c($novoNome, $novaMarca, $imagemFinal);
                break;
            case 'Smoking':
                $roupa = new Smoking($novoNome, $novaMarca, $imagemFinal);
                break;
            case 'Blazer':
                $roupa = new Blazer($novoNome, $novaMarca, $imagemFinal);
                break;
            case 'Vestido_l':
                $roupa = new Vestido_l($novoNome, $novaMarca, $imagemFinal);
                break;
            case 'Vestido_c':
                $roupa = new Vestido_c($novoNome, $novaMarca, $imagemFinal);
                break;
            case 'Vestido_d':
                $roupa = new Vestido_d($novoNome, $novaMarca, $imagemFinal);
                break;
            default:
                $mensagem = "Tipo de roupa inválido.";
                goto renderizar;
        }

        // Agora chamando o método corretamente, com todos os parâmetros
        $mensagem = $locadora->editarRoupa(
            $nomeOriginal,
            $novoNome,
            $novaMarca,
            $novoTipo,
            $imagemFinal
        );
    }
}
renderizar:
require_once __DIR__ . '/../views/template.php';
