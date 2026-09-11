<?php

include('inc/conexao.php');

if (isset($_POST['cadastrar'])) {

    $produto = $_POST['cd_produto'];
    $quantidade = $_POST['ds_quantidade_compra'];
    $valor = $_POST['ds_valor_compra'];
    $data = $_POST['ds_data_compra'];

    $sqlProduto = "SELECT ds_valor_produto
                   FROM produtos
                   WHERE cd_produto = ?";

    $stmtProduto = $conexao->prepare($sqlProduto);
    $stmtProduto->bind_param("i", $produto);
    $stmtProduto->execute();

    $resultadoProduto = $stmtProduto->get_result();
    $dadosProduto = $resultadoProduto->fetch_assoc();

    $sql = "INSERT INTO compras (cd_produto, ds_quantidade_compra, ds_valor_compra, ds_data_compra) VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iids",
        $produto,
        $quantidade,
        $valor,
        $data
    );

    if ($stmt->execute()) {

        $sqlEstoque = "UPDATE produtos
                       SET ds_quantidade_produto =
                       ds_quantidade_produto + ?
                       WHERE cd_produto = ?";

        $stmtEstoque = $conexao->prepare($sqlEstoque);

        $stmtEstoque->bind_param(
            "ii",
            $quantidade,
            $produto
        );

        $stmtEstoque->execute();

        header("Location: compras.php");
        exit();

    } 
    
    else {

        echo "Erro ao fazer a compra: " . $stmt->error;

    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras - Sistema de Estoque</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        body {
            background-color: black;
        }

        .divNavBar {
            background-color: black;
        }

        .textoNavNome {
            color: white;
            margin-left: 20px;
        }

        .textoBancosNav {
            color: white;
        }

        .textoBancosNav:hover {
            color: white;
        }

        .navbar {
            background-color: black !important;
            border-bottom: 2px solid white;
        }

        .dropdown-menu {
            background-color: black;
            border: 1px solid white;
        }

        .dropdown-item {
            color: white;
        }

        .dropdown-item:hover {
            background-color: white;
            color: black;
        }

        .container-principal {
            background-color: black;
            border-radius: 15px;
            width: 90%;
            padding: 30px;
            margin-top: 5%;
            margin-left: auto;
            margin-right: auto;
            border: 2px solid white;
        }

        .titulo {
            color: white;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subtitulo {
            color: white;
            margin-bottom: 30px;
        }

        .titulo-card {
            color: white;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .tabela-titulo {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-label {
            color: white;
        }

        .card-compra {
            background-color: black;
            border-radius: 15px;
            padding: 30px;
            border: 2px solid white;
            margin-bottom: 35px;
        }

        .form-control,
        .form-select {
            background-color: black;
            color: white;
            border: 1px solid white;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: black;
            color: white;
            border-color: white;
            box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.25);
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-select option {
            background-color: black;
            color: white;
        }

        .btn-compra {
            width: 100%;
            padding: 11px;
            border-radius: 9px;
            font-weight: 600;
        }

        .card-tabela {
            background-color: black;
            border-radius: 15px;
            padding: 25px;
            border: 2px solid white;
        }

        .table {
            vertical-align: middle;
            color: white;
            background-color: black;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: black;
            color: white;
            border: 1px solid white;
        }

        .table tbody td {
            background-color: black;
            color: white;
            border: 1px solid white;
        }

        .table-hover tbody tr:hover td {
            background-color: #222;
            color: white;
        }

        .badge-quantidade {
            color: white;
            padding: 5px;
        }

        .valor {
            font-weight: 600;
            color: white;
        }

        .btn-editar {
            margin-right: 5px;
        }

        .hr {
            border: none;
            height: 3px;
            background-color: white;
        }
    </style>

</head>
<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="divNavBar container-fluid">
            <a class="textoNavNome navbar-brand" href="cadastroProduto.php">
                Cadastro de produtos
                <br>
                & vendas
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbar" style="position:absolute; right: 2%">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="textoBancosNav nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">

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
            Compras
        </h1>


        <p class="subtitulo">
            Registre e acompanhe as compras realizadas para o estoque.
        </p>


        <hr class="hr">
        <div class="card-compra">

            <div class="titulo-card">
                Registrar nova compra
            </div>

            <form method="POST">
                <div class="row g-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">
                            Produto
                        </label>
                        <select class="form-select" name="cd_produto" id="produto" required>

                            <option value="" selected disabled>
                                Selecione um produto
                            </option>

                            <?php

                            $sqlProdutos = "SELECT cd_produto, nm_produto, ds_valor_produto FROM produtos ORDER BY nm_produto";
                            $resultadoProdutos = $conexao->query($sqlProdutos);

                            while ($produto = $resultadoProdutos->fetch_assoc()) {
                                echo "<option value='{$produto['cd_produto']}' data-valor='{$produto['ds_valor_produto']}'>
                                {$produto['nm_produto']}
                            </option>";

                            }

                            ?>

                        </select>

                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            Quantidade
                        </label>

                        <input type="number" class="form-control" name="ds_quantidade_compra" min="1" placeholder="0"
                            required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            Valor da compra
                        </label>

                        <input type="number" step="0.01" class="form-control" name="ds_valor_compra" placeholder="0,00"
                            required>

                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Data da compra
                        </label>
                        <input type="date" class="form-control" name="ds_data_compra" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="cadastrar" class="btn btn-info text-white btn-compra">
                            Registrar compra
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-tabela">
            <div class="tabela-titulo">

                Histórico de compras

            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        $sqlCompras = "SELECT compras.cd_compra, produtos.nm_produto, compras.ds_quantidade_compra, compras.ds_valor_compra, compras.ds_data_compra
                                   FROM compras
                                   INNER JOIN produtos
                                   ON compras.cd_produto = produtos.cd_produto
                                   ORDER BY compras.cd_compra DESC";

                        $resultadoCompras = $conexao->query($sqlCompras);


                        while ($compra = $resultadoCompras->fetch_assoc()) {

                            echo "<tr>";

                            echo "<td>{$compra['cd_compra']}</td>";

                            echo "<td>{$compra['nm_produto']}</td>";

                            echo "<td>
                                <span class='badge-quantidade'>
                                    {$compra['ds_quantidade_compra']}
                                </span>
                              </td>";

                            echo "<td class='valor'>
                                R$ " . number_format(
                                $compra['ds_valor_compra'],
                                2,
                                ',',
                                '.'
                            ) . "
                              </td>";

                            echo "<td>
                                " . date(
                                'd/m/Y',
                                strtotime($compra['ds_data_compra'])
                            ) . "
                              </td>";

                            echo "<td>

                                <a
                                    href='editarCompra.php?cd={$compra['cd_compra']}'
                                    class='btn btn-warning btn-sm btn-editar'>

                                    Editar

                                </a>

                                <a
                                    href='excluirCompra.php?cd={$compra['cd_compra']}'
                                    class='btn btn-danger btn-sm'>

                                    Excluir

                                </a>

                              </td>";

                            echo "</tr>";

                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>

</html>