<?php
// Caminho correto do arquivo JSON
$file = __DIR__ . '/../data/usuarios.json';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $perfil = trim($_POST['perfil'] ?? 'usuario');

    // Validação básica
    if (empty($username) || empty($password)) {
        $message = "Preencha todos os campos!";
    } else {
        // Lê os dados existentes ou cria um array vazio
        $usuarios = [];
        if (file_exists($file)) {
            $usuarios = json_decode(file_get_contents($file), true) ?? [];
        }

        // Verifica se o nome de usuário já existe
        $existe = false;
        foreach ($usuarios as $usuario) {
            if ($usuario['username'] === $username) {
                $existe = true;
                break;
            }
        }

        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email) {
                $mensagem = 'Este e-mail já está cadastrado';
                break;
            }
        }

        if ($existe) {
            $message = "Este nome de usuário já está cadastrado";
        } else {
            // Criptografa a senha
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Adiciona o novo usuário (mantendo o padrão do JSON)
            $usuarios[] = [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'perfil' => 'usuario'
            ];

            // Salva de volta no JSON
            file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT));
            $message = 'Cadastro realizado com sucesso! Clique aqui para retornar!';
        }
    }
}
?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="../assets/logo-lv.png" type="image/x-icon">
    <!-- Link ícones do bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Signin - Locadora de Roupas</title>
    
    

    <link rel="stylesheet" href="load-css.php">
    <style>
        .linkcad{
            text-decoration: none;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-bottom: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        header img {
            max-height: 200px;
        }

        header {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-header {
            background-color: #735943;
            text-align: center;
            color: #FFFFFF;
        }

        h4 {
            font-weight: 600;
            font-size: 1.3rem;
        }

        .card {
            border: none;
            border-radius: 10px;
        }

        .card-body {
            background-color: #9D9383;
            border-radius: 0px 0px 10px 10px;
        }

        .form-label {
            color: #FFFFFF;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .custom-btn {
            background-color: #A27E60 !important;
            /* Sobrescreve o Bootstrap */
            color: white;
            border: none;
            transition: ease 0.3s;
            font-size: 1.2rem;
        }

        input {
            height: 45px;
            background-color: #E1DDD9;
            opacity: 80%;
        }

        input::placeholder {
            color: #735943 !important;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .card {
                margin-left: 20px;
                margin-right: 20px;
            }
        }
    </style>
    <title>Document</title>
</head>
<body>
    <header class="header">
        <img src="../assets/logo-lavie.png" alt="Logo Lavie" class="logo">
    </header>
    <div class="login-container">

    <!-- Criação de Cards Bootstrap -->
        <div class="card">
            <!-- Título do card -->
            <div class="card-header p-3">
                <h4 class="mb-1" style="text-transform: uppercase;">cadastrar</h4>
            </div>

            <!-- Corpo do card -->
            <div class="card-body">

            <?php if ($mensagem): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($mensagem) ?></div>
                <?php endif; ?>
                <form method="post" class="needs-validation" novalidate action="signin.php">
                    <!-- Input - hidden | não aparece -->
                    <!-- Ele busca informações no JSON para validação, o usuário não vê -->
                    <!-- <input type="hidden"> -->

                    <div class="mb-4">
                        <label for="username" class="form-label">
                            Usuário:
                        </label>
                        <input type="text" name="username" class="form-control" required autocomplete="off" placeholder="Digite seu usuário aqui..." id="username">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            E-Mail:
                        </label>
                        <input type="email" name="email" class="form-control"  autocomplete="off" placeholder="Digite seu e-mail aqui..." id="email">
                    </div>
                    <div class="mb-4 position-relative">
                        <label for="password" class="form-label">
                            Senha:
                        </label>
                        <input type="password" name="password" class="form-control" required placeholder="Digite sua senha aqui..." id="password">

                        <span class="password-toggle mt-3" onclick="togglePassword()">
                            <i class="bi bi-eye-slash" id="olho"></i>
                        </span>
                    </div>
                    <div class="d-flex justify-content-center gap-2 mb-2 mt-2">
                        <button type="submit" class="btn custom-btn text-nowrap">Cadastrar</button>
                        <a href="../index.php" class="btn custom-btn text-nowrap">Voltar</a>
                    </div>
                    <div style="text-align: center; text-transform: uppercase;" class="mt-3 ">
                        <?php if (!empty($message)): ?>
                            <a href="login.php" class="link-custom-line linkcad"><?= htmlspecialchars($message) ?></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function togglePassword() {
            // Alterna o tipo do campo de senha entre "password" e "text"
            let passwordInput = document.getElementById('password');
            passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';

            // Alterna a classe do ícone
            let olho = document.getElementById('olho');
            if (olho.classList.contains('bi-eye')) {
                olho.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                olho.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>

</body>
</html>