<?php
session_start();
include_once('inc/conexao.php');

$mensagemErro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailLogin = trim($_POST['emailUsuarioBuscar'] ?? '');
    $senhaLogin = $_POST['senhaUsuarioBuscar'] ?? '';

    if (empty($emailLogin) || empty($senhaLogin)) {
        $mensagemErro = "Preencha todos os campos!";
    } else {
        try {
            $sql = "SELECT * FROM tb_usuarios WHERE ds_email_usuario = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $emailLogin]);

            $usuario = $stmt->fetch();

            if ($usuario) {
                if (password_verify($senhaLogin, $usuario['ds_senha_usuario'])) {

                    $_SESSION['usuario_id'] = $usuario['cd_usuario'];
                    $_SESSION['usuario_nome'] = $usuario['nm_usuario'];
                    $_SESSION['usuario_email'] = $usuario['ds_email_usuario'];
                    $_SESSION['usuario_foto'] = $usuario['ds_foto_usuario'] ?? '';

                    header('Location: inicial.php');
                    exit;

                } else {
                    $mensagemErro = "Senha incorreta!";
                }
            } else {
                $mensagemErro = "E-mail não encontrado!";
            }

        } catch (PDOException $e) {
            $mensagemErro = "Erro no banco de dados: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - GERENCI MONEY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0d0e12;
            font-family: 'Inter', sans-serif;
            color: #ffffff;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            background-color: #14161d;
            border: 1px solid #222630;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }

        .header-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 24px;
            text-align: center;
        }

        .avatar-wrapper {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background-color: #1e222d;
            border: 2px solid #2d3342;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .image-perfil {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-control {
            width: 100%;
            margin-bottom: 15px;
            background-color: black;
            color: white;
            border: 1px solid #2d3342;
        }

        .form-control:focus {
            background-color: black;
            color: white;
            border-color: #8fe028;
            box-shadow: none;
        }

        .botaoEnviar {
            background-color: #8fe028;
            color: black;
            font-size: 18px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .botaoEnviar:hover {
            background-color: #a3e635;
        }

        .form-control::placeholder {
            color: white;
            opacity: 0.3;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3 class="header-title">Entrar na Conta</h3>

        <div class="avatar-wrapper">
            <img src="assets/imagemAnonima2.png" alt="Perfil" class="image-perfil">
        </div>

        <?php if (!empty($mensagemErro)): ?>
            <div class="alert alert-danger w-100 text-center py-2 mb-3" role="alert" style="font-size: 0.9rem;">
                <?= htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="w-100">
            <label>E-mail:</label>
            <input type="email" name="emailUsuarioBuscar" class="form-control" placeholder="seu_email@gmail.com"
                required>

            <label>Senha:</label>
            <input type="password" name="senhaUsuarioBuscar" class="form-control" placeholder="12345678" required>


            <input type="submit" value="Entrar" class="botaoEnviar form-control">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
