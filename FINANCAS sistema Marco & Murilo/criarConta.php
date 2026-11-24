<?php
include_once('inc/conexao.php');
session_start();

$mensagemErro = "";
$mensagemSucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeUsuario = $_POST['nomeUsuarioEnviar'] ?? '';
    $emailUsuario = $_POST['emailUsuarioEnviar'] ?? '';
    $senhaUsuario = $_POST['senhaUsuarioEnviar'] ?? '';
    $confirmSenhaUsuario = $_POST['confirmarSenhaUsuarioEnviar'] ?? '';
    $cpfUsuario = $_POST['cpfUsuarioEnviar'] ?? '';
    $dataNascimentoUsuario = $_POST['dataNascimentoUsuarioEnviar'] ?? '';

    $bancoEscolhido = $_POST['bancoEscolhidoEnviar'] ?? 'Conta Principal';
    $idCategoria = $_POST['categoriaBanco'] ?? null;

    if ($senhaUsuario != $confirmSenhaUsuario) {
        $mensagemErro = "As senhas não coincidem!";
    } else {
        try {
            $senhaHash = password_hash($senhaUsuario, PASSWORD_DEFAULT);

            $sql = "INSERT INTO tb_usuarios (nm_usuario, ds_email_usuario, ds_senha_usuario, ds_cpf_usuario, dt_nascimento_usuario) VALUES (:nome, :email, :senha, :cpf, :dataNascimento)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nomeUsuario,
                ':email' => $emailUsuario,
                ':senha' => $senhaHash,
                ':cpf' => $cpfUsuario,
                ':dataNascimento' => $dataNascimentoUsuario
            ]);

            $idNovoUsuario = $pdo->lastInsertId();
            
            $sqlBanco = "INSERT INTO tb_bancos (nm_banco, ds_dinheiro_banco, id_usuario, id_categoria) VALUES (:nomeBanco, :saldoInicial, :usuario, :categoria)";
            $stmtBanco = $pdo->prepare($sqlBanco);
            $stmtBanco->execute([
                ':nomeBanco' => $bancoEscolhido,
                ':saldoInicial' => 0.00,
                ':usuario' => $idNovoUsuario,
                ':categoria' => $idCategoria 
            ]);

            $_SESSION['usuario_id'] = $idNovoUsuario;
            $_SESSION['usuario_nome'] = $nomeUsuario;
            $_SESSION['usuario_email'] = $emailUsuario;

            header('Location: inicial.php');
            exit;

        } catch (PDOException $e) {
            $mensagemErro = "Erro ao inserir dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - GERENCI MONEY</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0d0e12;
            font-family: 'Inter', sans-serif;
            color: #ffffff;
            padding: 20px 0;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 30px 25px;
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
            margin-bottom: 20px;
            text-align: center;
        }

        .avatar-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #1e222d;
            border: 2px solid #2d3342;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .image-perfil {
            width: 100%;
            height: 100%;
            object-fit: cover;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            width: 100%;
            margin-bottom: 12px;
            background-color: #000;
            color: #fff;
            border: 1px solid #2d3342;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #000;
            color: #fff;
            border-color: #a3e635;
            box-shadow: none;
        }

        .form-select option {
            background-color: #14161d;
            color: #fff;
        }

        .form-control::placeholder {
            color: white;
            opacity: 0.3;
        }

        .botaoEnviar {
            background-color: #8fe028;
            color: black;
            font-size: 18px;
            font-weight: 600;
            border: none;
            margin-top: 10px;
            cursor: pointer;
        }

        .botaoEnviar:hover {
            background-color: #a3e635;
        }

        label {
            color: #ffffff !important;
            margin-bottom: 4px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3 class="header-title">Criar Conta</h3>

        <div class="avatar-wrapper">
            <img src="assets/imagemAnonima2.png" alt="Perfil" class="image-perfil">
        </div>

        <form action="" method="POST" class="w-100">
            <label>Nome de Usuário:</label>
            <input type="text" name="nomeUsuarioEnviar" class="form-control" required>

            <label>E-mail:</label>
            <input type="email" name="emailUsuarioEnviar" onblur="this.value = this.value.trim()" class="form-control"
                placeholder="seu_email@gmail.com" required>

            <label>Senha:</label>
            <input type="password" name="senhaUsuarioEnviar" onblur="this.value = this.value.trim()"
                class="form-control" placeholder="123456" minlength="6" required>

            <label>Confirmar senha:</label>
            <input type="password" onblur="this.value = this.value.trim()" name="confirmarSenhaUsuarioEnviar"
                class="form-control" minlength="6" required>

            <label>CPF:</label>
            <input type="text" onblur="this.value = this.value.trim()" name="cpfUsuarioEnviar" class="form-control"
                placeholder="000.000.000-00" required>

            <label>Data de Nascimento:</label>
            <input type="date" name="dataNascimentoUsuarioEnviar" class="form-control" required>

            <label>Banco Principal:</label>
            <select name="bancoEscolhidoEnviar" class="form-select" required>
                <option value="Nubank">Nubank</option>
                <option value="Mercado Pago">Mercado Pago</option>
                <option value="Bradesco">Bradesco</option>
                <option value="Itaú">Itaú</option>
                <option value="Banco do Brasil">Banco do Brasil</option>
                <option value="Caixa Econômica">Caixa Econômica</option>
                <option value="Santander">Santander</option>
                <option value="Inter">Inter</option>
            </select>

            <label>Tipo de Conta (Categoria):</label>
            <select name="categoriaBanco" class="form-select mb-3" required>
                <option value="">Selecione o tipo de conta...</option>
                <?php
                try {
                    $stmtCat = $pdo->query("SELECT cd_categoria, nm_categoria FROM tb_categoria_contas ORDER BY nm_categoria ASC");
                    $categorias = $stmtCat->fetchAll();
                    foreach ($categorias as $cat) {
                        echo '<option value="' . $cat['cd_categoria'] . '">' . htmlspecialchars($cat['nm_categoria']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Erro ao carregar categorias</option>';
                }
                ?>
            </select>

            <input type="submit" value="Criar Conta" class="botaoEnviar form-control">
        </form>

        <?php if (!empty($mensagemErro)): ?>
            <div class="alert alert-danger w-100 text-center mt-3 py-2" role="alert" style="font-size: 0.9rem;">
                <?= $mensagemErro; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>