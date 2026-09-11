<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "SELECT * FROM compras WHERE cd_compra = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $compra = $resultado->fetch_assoc();

    if (!$compra) {
        echo "Compra não encontrada.";
        exit();
    }

} else {

    echo "Código da compra não informado.";
    exit();

}


if (isset($_POST['editar'])) {

    $produto = $_POST['cd_produto'];
    $quantidade = $_POST['ds_quantidade_compra'];
    $valor = $_POST['ds_valor_compra'];
    $data = $_POST['ds_data_compra'];

    $sql = "UPDATE compras 
            SET cd_produto = ?, 
                ds_quantidade_compra = ?, 
                ds_valor_compra = ?, 
                ds_data_compra = ? 
            WHERE cd_compra = ?";

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

        header("Location: compras.php");
        exit();

    } else {

        echo "Erro ao editar compra: " . $stmt->error;

    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <title>Editar Compra</title>


    <style>

        .bodyStyle {
            background-color: black;
        }


        .textoBancos {
            color: white;
        }


        .textoBancosNav {
            color: white;
        }


        .divNavBar {
            background-color: black;
        }


        .navbar {
            background-color: black !important;
            border-bottom: 2px solid white;
        }


        .container {
            background-color: black;
            border-radius: 15px;
            width: 100%;
            padding: 30px;
            margin-top: 5%;
            border: 2px solid white;
        }


        .hr {
            border: none;
            height: 3px;
            background-color: white;
        }


        .textoNavNome {
            color: white;
            margin-left: 20px;
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


        .textoBancosNav:hover {
            color: white;
        }


        .textoBancos label {
            margin-bottom: 5px;
        }


        .form-control,
        .form-select {
            border-radius: 5px;
        }


        .botaoEditar {
            margin-top: 24px;
        }


        .botaoVoltar {
            margin-top: 24px;
        }

    </style>

</head>


<body class="bodyStyle">

    <nav class="navbar navbar-expand-lg bg-body-tertiary">

        <div class="divNavBar container-fluid">

            <a 
                class="textoBancosNav navbar-brand" 
                href="cadastroProduto.php" 
                style="margin-left: 3%"
            >
                Cadastro de produtos
                <br>
                & vendas
            </a>


            <button 
                class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" 
                aria-expanded="false" 
                aria-label="Toggle navigation"
            >

                <span class="navbar-toggler-icon"></span>

            </button>


            <div 
                class="collapse navbar-collapse" 
                id="navbarSupportedContent" 
                style="position:absolute; right: 2%"
            >

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown">

                        <a 
                            class="textoBancosNav nav-link dropdown-toggle" 
                            href="#" 
                            role="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false"
                        >
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

                        <a 
                            class="textoBancosNav nav-link" 
                            href="index.php"
                        >
                            Criar/c
                        </a>

                    </li>


                </ul>

            </div>

        </div>

    </nav>

    <div class="container">


        <h1 class="textoBancos mt-4 mb-4">

            Editar Compra

            <br>

            <hr class="hr">

        </h1>

        <form method="POST">


            <div class="row">

                <div class="textoBancos col-sm-5">

                    <label>
                        Produto:
                    </label>


                    <select 
                        class="form-select" 
                        name="cd_produto" 
                        required
                    >

                        <?php

                        $sqlProdutos = "
                            SELECT cd_produto, nm_produto 
                            FROM produtos 
                            ORDER BY nm_produto
                        ";

                        $resultadoProdutos = $conexao->query($sqlProdutos);


                        while ($produto = $resultadoProdutos->fetch_assoc()) {

                            if ($produto['cd_produto'] == $compra['cd_produto']) {

                                echo "
                                    <option 
                                        value='{$produto['cd_produto']}' 
                                        selected
                                    >
                                        {$produto['nm_produto']}
                                    </option>
                                ";

                            } else {

                                echo "
                                    <option 
                                        value='{$produto['cd_produto']}'
                                    >
                                        {$produto['nm_produto']}
                                    </option>
                                ";

                            }

                        }

                        ?>

                    </select>

                </div>

                <div class="textoBancos col-sm-2">

                    <label>
                        Quantidade:
                    </label>


                    <input
                        type="number"
                        class="form-control"
                        name="ds_quantidade_compra"
                        min="1"
                        value="<?php echo $compra['ds_quantidade_compra']; ?>"
                        required
                    >

                </div>

                <div class="textoBancos col-sm-2">

                    <label>
                        Valor da Compra:
                    </label>


                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        class="form-control"
                        name="ds_valor_compra"
                        value="<?php echo $compra['ds_valor_compra']; ?>"
                        required
                    >

                </div>

                <div class="textoBancos col-sm-3">

                    <label>
                        Data da Compra:
                    </label>


                    <input
                        type="date"
                        class="form-control"
                        name="ds_data_compra"
                        value="<?php echo $compra['ds_data_compra']; ?>"
                        required
                    >

                </div>

                <div class="textoBancos col-sm-3">

                    <button
                        type="submit"
                        name="editar"
                        class="btn btn-outline-info botaoEditar"
                    >
                        Salvar alterações
                    </button>

                </div>

                <div class="textoBancos col-sm-3">

                    <a
                        href="compras.php"
                        class="btn btn-outline-light botaoVoltar"
                    >
                        Voltar
                    </a>

                </div>


            </div>

        </form>


    </div>



    <script 
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
    </script>


    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous">
    </script>


</body>

</html>