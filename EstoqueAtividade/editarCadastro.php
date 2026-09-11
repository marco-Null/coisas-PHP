<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "SELECT * FROM produtos WHERE cd_produto = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $produto = $resultado->fetch_assoc();
    } else {
        echo "Produto não encontrado.";
        exit();
    }

} else {
    echo "Código do produto não informado.";
    exit();
}

if (isset($_POST['editar'])) {

    $nome = $_POST['nm_produto'];
    $quantidade = $_POST['ds_quantidade_produto'];
    $descricao = $_POST['ds_descricao_produto'];
    $valor = $_POST['ds_valor_produto'];
    $venda = $_POST['ds_valor_venda_produto'];

    $sql = "UPDATE produtos 
            SET nm_produto = ?,
                ds_quantidade_produto = ?,
                ds_descricao_produto = ?,
                ds_valor_produto = ?,
                ds_valor_venda_produto = ?
            WHERE cd_produto = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sisssi",
        $nome,
        $quantidade,
        $descricao,
        $valor,
        $venda,
        $cd
    );

    if ($stmt->execute()) {

        header("Location: cadastroProduto.php");
        exit();

    } else {
        echo "Erro ao editar produto: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Editar Produto</title>

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
            color:#0dcaf0;
        }

        .navbar{
            background-color:black !important;
            border-bottom:2px solid white;
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

        .container-principal{
            background-color:black;
            border-radius:15px;
            width:90%;
            padding:30px;
            margin:5% auto;
            border:2px solid white;
        }

        .titulo{
            color:white;
            font-weight:700;
            margin-bottom:5px;
        }

        .subtitulo{
            color:white;
            margin-bottom:30px;
        }

        .hr{
            border:none;
            height:3px;
            background-color:white;
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

        .btn-salvar{
            margin-top:31.9px;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg">

        <div class="divNavBar container-fluid">

            <a class="textoNavNome navbar-brand" href="cadastroProduto.php" style="margin-left:3%">
                Cadastro de produtos
                <br>
                & vendas
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse"
                id="navbarSupportedContent"
                style="position:absolute; right:2%">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown">

                        <a class="textoBancosNav nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

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

                        <a class="textoBancosNav nav-link" href="index.php">
                            Criar/c
                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <div class="container-principal">

        <h1 class="titulo">
            Editar Produto
        </h1>

        <hr class="hr">

        <p class="subtitulo">
            Altere as informações do produto abaixo.
        </p>

        <form method="post">

            <div class="row">

                <div class="col-sm-3">

                    <label class="form-label">
                        Nome:
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="nm_produto"
                        value="<?php echo $produto['nm_produto']; ?>"
                        required>

                </div>

                <div class="col-sm-3">

                    <label class="form-label">
                        Quantidade:
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        name="ds_quantidade_produto"
                        value="<?php echo $produto['ds_quantidade_produto']; ?>"
                        required>

                </div>

                <div class="col-sm-3">

                    <label class="form-label">
                        Descrição:
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="ds_descricao_produto"
                        value="<?php echo $produto['ds_descricao_produto']; ?>"
                        required>

                </div>

                <div class="col-sm-3">

                    <label class="form-label">
                        Valor:
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        name="ds_valor_produto"
                        value="<?php echo $produto['ds_valor_produto']; ?>"
                        required>

                </div>

                <div class="col-sm-3 mt-3">

                    <label class="form-label">
                        Venda:
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        name="ds_valor_venda_produto"
                        value="<?php echo $produto['ds_valor_venda_produto']; ?>"
                        required>

                </div>

                <div class="col-sm-3 mt-3">

                    <button
                        type="submit"
                        name="editar"
                        class="btn btn-outline-warning btn-salvar">

                        Salvar alterações

                    </button>

                </div>

            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>