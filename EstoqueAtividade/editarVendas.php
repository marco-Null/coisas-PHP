<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "SELECT * FROM vendas WHERE cd_venda = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $venda = $resultado->fetch_assoc();

    if (!$venda) {
        echo "Venda não encontrada.";
        exit();
    }

} else {

    echo "Código da venda não informado.";
    exit();

}

if (isset($_POST['editar'])) {

    $produto = $_POST['cd_produto'];
    $quantidade = $_POST['ds_quantidade_venda'];
    $valor = $_POST['ds_valor_venda'];
    $data = $_POST['ds_data_venda'];

    $sql = "UPDATE vendas
            SET cd_produto = ?,
                ds_quantidade_venda = ?,
                ds_valor_venda = ?,
                ds_data_venda = ?
            WHERE cd_venda = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iidsi",
        $produto,
        $quantidade,
        $valor,
        $data,
        $cd
    );

    if ($stmt->execute()) {

        header("Location: vendas.php");
        exit();

    } else {

        echo "Erro ao editar venda: " . $stmt->error;

    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Venda</title>

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

        .titulo {
            color: white;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subtitulo {
            color: white;
            margin-bottom: 30px;
        }

        .card-editar {
            background-color: black;
            border: 2px solid white;
            border-radius: 15px;
            padding: 30px;
        }

        .titulo-card {
            color: white;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .form-label {
            color: white;
        }

        .form-control,
        .form-select {
            background-color: black;
            color: white;
            border: 1px solid white;
            border-radius: 9px;
            padding: 10px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: black;
            color: white;
            border-color: #0dcaf0;
            box-shadow: 0 0 0 0.15rem rgba(13, 202, 240, .15);
        }

        .form-select option {
            background-color: black;
            color: white;
        }

        .btn-editar {
            width: 100%;
            padding: 11px;
            border-radius: 9px;
            font-weight: 600;
        }

        .btn-voltar {
            width: 100%;
            padding: 11px;
            border-radius: 9px;
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

        <h1 class="titulo">
            Editar venda
        </h1>

        <p class="subtitulo">
            Altere os dados da venda selecionada.
        </p>

        <div class="card-editar">

            <div class="titulo-card">
                Editar dados da venda
            </div>

            <form method="POST">

                <div class="row g-4">

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            Produto
                        </label>

                        <select class="form-select" name="cd_produto" required>

                            <?php

                            $sqlProdutos = "SELECT cd_produto, nm_produto
                                            FROM produtos
                                            ORDER BY nm_produto";

                            $resultadoProdutos = $conexao->query($sqlProdutos);

                            while ($produto = $resultadoProdutos->fetch_assoc()) {

                                if ($produto['cd_produto'] == $venda['cd_produto']) {

                                    echo "<option value='{$produto['cd_produto']}' selected>
                                        {$produto['nm_produto']}
                                    </option>";

                                } else {

                                    echo "<option value='{$produto['cd_produto']}'>
                                        {$produto['nm_produto']}
                                    </option>";

                                }

                            }

                            ?>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Quantidade
                        </label>

                        <input type="number" class="form-control" name="ds_quantidade_venda" min="1"
                            value="<?php echo $venda['ds_quantidade_venda']; ?>" required>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Valor da venda
                        </label>

                        <input type="number" step="0.01" class="form-control" name="ds_valor_venda"
                            value="<?php echo $venda['ds_valor_venda']; ?>" required>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Data da venda
                        </label>

                        <input type="date" class="form-control" name="ds_data_venda"
                            value="<?php echo $venda['ds_data_venda']; ?>" required>

                    </div>

                    <div class="col-md-3">

                        <button type="submit" name="editar" class="btn btn-warning btn-editar">
                            Salvar alterações
                        </button>

                    </div>

                    <div class="col-md-3">

                        <a href="vendas.php" class="btn btn-light btn-voltar">
                            Voltar
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous"></script>
</body>

</html>