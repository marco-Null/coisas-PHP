<?php
include('inc/conexao.php');

if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nm_usuario'];
    $senha = $_POST['ds_senha_usuario'];
    $senhaConfirmation = $_POST['senhaConfirmacao'];
    $dataNascimento = $_POST['ds_data_nascimento_usuario'];
    $email = $_POST['ds_email_usuario'];

    if ($senhaConfirmation == $senha) {

        $senha = password_hash($senha, PASSWORD_DEFAULT);
        $senhaConfirmation = password_hash($senhaConfirmation, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios 
            (nm_usuario, ds_senha_usuario, ds_data_nascimento_usuario, ds_email_usuario)
            VALUES (?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "ssss",
            $nome,
            $senha,
            $dataNascimento,
            $email
        );

        if ($stmt->execute()) {

            header("Location: cadastroProduto.php");
            exit();

        } else {

            echo "Erro ao cadastrar usuário: " . $stmt->error;

        }

    } else {

        echo "<div class='alert alert-warning' style='width: 50%; position: absolute; top: 80%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 20px;' role='alert'>Senhas não se igualam!</div>";

    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background-color:black;
        }

        .divNavBar{
            background-color:black;
        }

        .textoNavNome{
            color:white;
            margin-left:20px;
        }

        .textoBancosNav{
            color:white;
        }

        .textoBancosNav:hover{
            color: white;
        }

        .navbar{
            background-color:black !important;
            border-bottom: 2px solid white;
        }

        .navbar-toggler{
            background-color:white;
        }

        .dropdown-menu{
            background-color:black;
            border:1px solid white;
        }

        .dropdown-item{
            color:white;
        }

        .dropdown-item:hover{
            background-color:white;
            color:black;
        }

        .container-2{
            background-color:black;
            border:2px solid white;
            border-radius:15px;
            width:50%;
            padding:40px;
            margin:5% auto;
        }

        .form-label{
            color:white;
        }

        .form-control{
            background-color:black;
            color:white;
            border:1px solid white;
        }

        .form-control:focus{
            background-color:black;
            color:white;
            border-color:#0dcaf0;
            box-shadow:0 0 0 0.15rem rgba(13,202,240,.15);
        }

        .form-control::placeholder{
            color:#aaa;
        }

        .botaoCriar{
            width:48%;
        }

        .titulo{
            color:white;
            font-weight:700;
            font-size:28px;
            margin-bottom:25px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="divNavBar container-fluid">

            <a class="textoNavNome navbar-brand" href="cadastroProduto.php" style="margin-left:3%">
                Cadastro de produtos
                <br>
                & vendas
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent"
                style="position:absolute; right:2%">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown">

                        <a class="textoBancosNav nav-link dropdown-toggle" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            Produtos

                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a class="dropdown-item" href="compras.php">
                                    Comprar
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="vendas.php">
                                    Vender
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="cadastroProduto.php">
                                    Cadastrar
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="relatorio.php">
                                    Relatorio
                                </a>
                            </li>

                        </ul>

                    </li>

                    <li class="nav-item">

                        <a class="textoBancosNav nav-link" href="login.php">
                            Criar/c
                        </a>

                    </li>

                </ul>

            </div>

        </div>
    </nav>

    <div class="container-2">

        <div class="titulo">
            Cadastro de usuário
        </div>

        <form class="row g-3" method="POST">

            <div class="col-md-6">

                <label for="inputEmail4" class="form-label">
                    Email
                </label>

                <input type="email"
                    class="form-control"
                    id="inputEmail4"
                    placeholder="joãozinho67@gmail.com"
                    name="ds_email_usuario">

            </div>

            <div class="col-md-6">

                <label for="inputPassword4" class="form-label">
                    Senha
                </label>

                <input type="password"
                    class="form-control"
                    id="inputPassword4"
                    name="ds_senha_usuario">

            </div>

            <div class="col-6">

                <label for="inputAddress" class="form-label">
                    Nome usuario
                </label>

                <input type="text"
                    class="form-control"
                    id="inputAddress"
                    name="nm_usuario">

            </div>

            <div class="col-6">

                <label class="form-label">
                    Confirme a Senha
                </label>

                <input type="password"
                    class="form-control"
                    name="senhaConfirmacao">

            </div>

            <div class="col-md-6">

                <label for="inputCity" class="form-label">
                    Data de nascimento
                </label>

                <input type="date"
                    class="form-control"
                    id="inputCity"
                    name="ds_data_nascimento_usuario">

            </div>

            <div class="col-12">

                <button type="submit"
                    class="botaoCriar btn btn-success"
                    name="cadastrar">

                    Criar

                </button>

            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>