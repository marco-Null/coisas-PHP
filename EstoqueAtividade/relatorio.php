<?php

include('inc/conexao.php');

$sqlProdutos = "SELECT COUNT(*) AS total FROM produtos";
$resultadoProdutos = $conexao->query($sqlProdutos);
$totalProdutos = $resultadoProdutos->fetch_assoc()['total'];

$sqlVendas = "SELECT COUNT(*) AS total FROM vendas";
$resultadoVendas = $conexao->query($sqlVendas);
$totalVendas = $resultadoVendas->fetch_assoc()['total'];

$sqlQuantidade = "SELECT SUM(ds_quantidade_venda) AS total FROM vendas";
$resultadoQuantidade = $conexao->query($sqlQuantidade);
$dadosQuantidade = $resultadoQuantidade->fetch_assoc();
$totalQuantidade = $dadosQuantidade['total'] ?? 0;

$sqlMaisVendidos = "SELECT 
                        produtos.nm_produto,
                        SUM(vendas.ds_quantidade_venda) AS total_vendido
                    FROM vendas
                    INNER JOIN produtos
                        ON vendas.cd_produto = produtos.cd_produto
                    GROUP BY produtos.cd_produto
                    ORDER BY total_vendido DESC";

$resultadoMaisVendidos = $conexao->query($sqlMaisVendidos);
$nomesProdutos = [];
$quantidadesProdutos = [];

while ($produto = $resultadoMaisVendidos->fetch_assoc()) {
    $nomesProdutos[] = $produto['nm_produto'];
    $quantidadesProdutos[] = $produto['total_vendido'];
}

$sqlDatas = "SELECT 
                ds_data_venda,
                SUM(ds_quantidade_venda) AS quantidade
             FROM vendas
             GROUP BY ds_data_venda
             ORDER BY ds_data_venda ASC";

$resultadoDatas = $conexao->query($sqlDatas);
$datasVendas = [];
$quantidadesDatas = [];

while ($venda = $resultadoDatas->fetch_assoc()) {
    $datasVendas[] = date('d/m/Y', strtotime($venda['ds_data_venda']));
    $quantidadesDatas[] = $venda['quantidade'];
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .hr {
            border: none;
            height: 3px;
            background-color: white;
        }

        .card-resumo {
            background-color: black;
            border: 2px solid white;
            border-radius: 15px;
            padding: 25px;
            height: 100%;
        }

        .titulo-resumo {
            color: white;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .numero-resumo {
            color: white;
            font-size: 32px;
            font-weight: 700;
        }

        .card-grafico {
            background-color: black;
            border: 2px solid white;
            border-radius: 15px;
            padding: 25px;
        }

        .titulo-card {
            color: white;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .grafico {
            position: relative;
            height: 350px;
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

            <div class="collapse navbar-collapse" id="navbar" style="position:absolute;right:2%">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown">
                        <a class="textoBancosNav nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Produtos
                        </a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="compras.php">Comprar</a></li>
                            <li><a class="dropdown-item" href="vendas.php">Vender</a></li>
                            <li><a class="dropdown-item" href="cadastroProduto.php">Cadastrar</a></li>
                            <li><a class="dropdown-item" href="relatorio.php">Relatorio</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="textoBancosNav nav-link" href="login.php">Criar/c</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container-principal">

        <h1 class="titulo">
            Relatórios
        </h1>

        <p class="subtitulo">
            Acompanhe o desempenho das vendas e do estoque.
        </p>

        <hr class="hr">

        <div class="row g-4 mb-4">

            <div class="col-md-4">
                <div class="card-resumo">
                    <div class="titulo-resumo">
                        Produtos cadastrados
                    </div>

                    <div class="numero-resumo">
                        <?php echo $totalProdutos; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-resumo">
                    <div class="titulo-resumo">
                        Vendas realizadas
                    </div>

                    <div class="numero-resumo">
                        <?php echo $totalVendas; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-resumo">
                    <div class="titulo-resumo">
                        Produtos vendidos
                    </div>

                    <div class="numero-resumo">
                        <?php echo $totalQuantidade; ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-grafico mb-4">

            <div class="titulo-card">
                Produtos mais vendidos
            </div>

            <div class="grafico">
                <canvas id="graficoProdutos"></canvas>
            </div>

        </div>

    </div>

    <script>

        const nomesProdutos = <?php echo json_encode($nomesProdutos); ?>;
        const quantidadesProdutos = <?php echo json_encode($quantidadesProdutos); ?>;

        new Chart(document.getElementById('graficoProdutos'), {
            type: 'bar',
            data: {
                labels: nomesProdutos,
                datasets: [{
                    label: 'Quantidade vendida',
                    data: quantidadesProdutos
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>

</body>

</html>