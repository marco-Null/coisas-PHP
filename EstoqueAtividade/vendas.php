<?php

include('inc/conexao.php');

if (isset($_POST['cadastrar'])) {

    $produto = $_POST['cd_produto'];
    $quantidade = $_POST['ds_quantidade_venda'];
    $valor = $_POST['ds_valor_venda'];
    $data = $_POST['ds_data_venda'];

    $sqlEstoque = "SELECT ds_quantidade_produto
                   FROM produtos
                   WHERE cd_produto = ?";

    $stmtEstoque = $conexao->prepare($sqlEstoque);
    $stmtEstoque->bind_param("i", $produto);
    $stmtEstoque->execute();

    $resultadoEstoque = $stmtEstoque->get_result();
    $dadosEstoque = $resultadoEstoque->fetch_assoc();

    if ($dadosEstoque['ds_quantidade_produto'] < $quantidade) {

        echo "Quantidade insuficiente no estoque.";

    } else {

        $sql = "INSERT INTO vendas
                (cd_produto, ds_quantidade_venda, ds_valor_venda, ds_data_venda)
                VALUES (?, ?, ?, ?)";

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
                           ds_quantidade_produto - ?
                           WHERE cd_produto = ?";

            $stmtEstoque = $conexao->prepare($sqlEstoque);

            $stmtEstoque->bind_param(
                "ii",
                $quantidade,
                $produto
            );

            $stmtEstoque->execute();

            header("Location: vendas.php");
            exit();

        } else {

            echo "Erro ao cadastrar venda: " . $stmt->error;

        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vendas</title>

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
            margin: 5% auto;
            border: 2px solid white;
        }

        .card {
            background-color: black;
            border: 2px solid white;
            border-radius: 15px;
            color: white;
        }

        .card-header {
            background-color: black;
            color: white;
            border-bottom: 2px solid white;
            font-weight: bold;
            padding: 18px;
            border-radius: 13px 13px 0 0;
        }

        .card-body {
            color: white;
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
            box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, .25);
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-select option {
            background-color: black;
            color: white;
        }

        .form-label {
            color: white;
        }

        .btn {
            border-radius: 8px;
        }

        .table {
            margin-bottom: 0;
            background-color: black;
            color: white;
        }

        .table thead {
            background-color: black;
            color: white;
        }

        .table th {
            padding: 14px;
            color: white;
            background-color: black;
            border-color: white;
        }

        .table td {
            vertical-align: middle;
            padding: 14px;
            color: white;
            background-color: black;
            border-color: #444;
        }

        .table-hover tbody tr:hover td {
            background-color: #222;
            color: white;
        }

        .badge-quantidade {
            color: white;
            padding: 6px 10px;
            font-weight: 600;
        }

        .valor {
            font-weight: 600;
        }

        .downDrop {
            margin-right: 5px;
        }

        .itemDrop {
            width: 5px;
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
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="position:absolute; right:2%">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="downDrop nav-item dropdown">

                        <a class="textoBancosNav nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Produtos
                        </a>

                        <ul class="itemDrop dropdown-menu">

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

        <div class="card mb-4">

            <div class="card-header">
                Registrar venda
            </div>

            <div class="card-body">

                <form method="POST">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Produto
                            </label>

                            <select class="form-select" name="cd_produto" required>

                                <option value="">
                                    Selecione um produto
                                </option>

                                <?php

                                $sqlProdutos = "SELECT cd_produto, nm_produto FROM produtos ORDER BY nm_produto";

                                $resultadoProdutos = $conexao->query($sqlProdutos);

                                while ($produto = $resultadoProdutos->fetch_assoc()) {

                                    echo "<option value='{$produto['cd_produto']}'>
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

                            <input type="number" class="form-control" name="ds_quantidade_venda" min="1" placeholder="0"
                                required>

                        </div>

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Valor da venda
                            </label>

                            <input type="number" step="0.01" class="form-control" name="ds_valor_venda"
                                placeholder="0,00" required>

                        </div>

                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Data
                            </label>

                            <input type="date" class="form-control" name="ds_data_venda" required>

                        </div>

                        <div class="col-md-2 d-flex align-items-end">

                            <button type="submit" name="cadastrar" class="btn btn-success w-100">
                                Registrar
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <div class="card">

            <div class="card-header">
                Histórico de vendas
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover">

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

                            $sqlVendas = "SELECT vendas.cd_venda, produtos.nm_produto, vendas.ds_quantidade_venda, vendas.ds_valor_venda, vendas.ds_data_venda
                                           FROM vendas
                                           INNER JOIN produtos ON vendas.cd_produto = produtos.cd_produto
                                           ORDER BY vendas.cd_venda DESC";

                            $resultadoVendas = $conexao->query($sqlVendas);

                            while ($venda = $resultadoVendas->fetch_assoc()) {

                                echo "<tr>

                                    <td>{$venda['cd_venda']}</td>

                                    <td>
                                        {$venda['nm_produto']}
                                    </td>

                                    <td>

                                        <span class='badge-quantidade'>
                                            {$venda['ds_quantidade_venda']}
                                        </span>

                                    </td>

                                    <td class='valor'>
                                        R$ " . number_format($venda['ds_valor_venda'], 2, ',', '.') . "
                                    </td>

                                    <td>
                                        " . date('d/m/Y', strtotime($venda['ds_data_venda'])) . "
                                    </td>

                                    <td>

                                        <a
                                            href='editarVendas.php?cd={$venda['cd_venda']}'
                                            class='btn btn-warning btn-sm'
                                        >
                                            Editar
                                        </a>

                                        <a
                                            href='excluirVendas.php?cd={$venda['cd_venda']}'
                                            class='btn btn-danger btn-sm'
                                        >
                                            Excluir
                                        </a>

                                    </td>

                                </tr>";

                            }

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>

</html>
