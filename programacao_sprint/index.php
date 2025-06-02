<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exemplo Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="assets/logo-lv.png" type="image/x-icon">
    <!-- Link ícones do bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
    .left-side {
      background-image: url('assets/landing-background.jpg'); /* Substitua pelo nome da sua imagem */
        background-size: cover;
        background-position: center;
        height: 100vh;
    }
    .right-side {
    background-color: white;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    .logo {
        width: 100px;
        height: auto;
        margin-bottom: 20px;
    }
    .brand-name {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .btn-group {
        display: flex;
        gap: 10px;
    }
</style>
</head>
<body>

<div class="container-fluid">
<div class="row">
    <div class="col-md-8 left-side">
    <!-- Aqui vai a imagem de fundo -->
    </div>

        <div class="col-md-4 right-side text-center" style="background-color:rgb(169, 149, 134);">
            <img src="assets/logo-lavie.png" alt="Logo" class="w-50"> <!-- Substitua pelo nome da sua logo -->
        <div class="brand-name"><h1 style="color: white;">La Vie Elegance</h1></div>
        <div class="btn-group">
            <a href="public/login.php" class="btn custom-btn" style="text-transform: uppercase;">Entrar</a>
            <a href="public/signin.php" class="btn custom-btn" style="text-transform: uppercase;">Cadastrar</a>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
