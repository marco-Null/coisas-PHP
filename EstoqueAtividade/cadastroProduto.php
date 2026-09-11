<?php

include('inc/conexao.php');

if (isset($_POST['nm_produto'])) {

    $nome = $_POST['nm_produto'];
    $quantidade = $_POST['ds_quantidade_produto'];
    $descricaoProduto = $_POST['ds_descricao_produto'];

    $valor = $_POST['ds_valor_produto'];
    $venda = $_POST['ds_valor_venda_produto'];
    $sql = "INSERT INTO produtos (nm_produto, ds_quantidade_produto, ds_descricao_produto, ds_valor_produto, ds_valor_venda_produto) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);

    $stmt->bind_param("sisss", $nome, $quantidade, $descricaoProduto, $valor, $venda);
    if ($stmt->execute()) {
        header("Location: cadastroProduto.php");
        exit();
    } else {
        echo "Erro ao adicionar produto: " . $stmt->error;
    }
}

$sql = "SELECT * FROM produtos";
$resultado = $conexao->query($sql);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sistema de Produtos</title>

    <style>
        .bodyStyle{
            background-color: black;
        }

        .textoBancos{
            color: white;
        }

        .textoBancosNav{
            color: white;
        }
        
        .table{
            border-radius: 5px;
            border-width: 5px;
            
        }

        .divNavBar{
            background-color: black;
        }

        .buttonEditar{
            color: black;
        }

        .container{
            background-color: black;
            border-radius: 15px;
            width: 100%;
            padding: 30px;
            margin-top: 5%;
            border: 2px solid white;
        }

        .hr{
            border: none;
            height: 3px;
            background-color: white;
        }   

        .textoNavNome{
            color: white;
            margin-left: 20px;
        }
        

        .tabela {
            background-color: black;
            color: white;
            border: none;
        }

        .navbar{
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

        
        .textoBancosNav:hover {
            color: white;
        }

    </style>



</head>

<body class="bodyStyle">

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="divNavBar container-fluid">
            <a class="textoBancosNav navbar-brand" href="#" style="margin-left: 3%">Cadastro de produtos <br>& vendas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="position:absolute; right: 2%">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="textoBancosNav nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Produtos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class=" dropdown-item" href="compras.php">Comprar</a></li>
                            <li><a class=" dropdown-item" href="vendas.php">Vender</a></li>
                            <li><a class=" dropdown-item" href="cadastroProduto.php">Cadastrar</a></li>
                            <li><a class=" dropdown-item" href="relatorio.php">Relatorio</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="textoBancosNav nav-link" href="index.php">Criar/c</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <h1 class="textoBancos mt-4 mb-4">Cadastro de Produtos<br><hr class="hr"></h1>
        
        <form action="cadastroProduto.php" method="post">
            <div class="row">
                <div class="textoBancos col-sm-3">
                    <label>Nome do Produto:</label>
                    <input
                        type="text"
                        class="form-control"
                        name="nm_produto"
                        required>
                </div>
                <div class="textoBancos col-sm-3">
                    <label>Quantidade do Produto:</label>
                    <input
                        type="number"
                        class="form-control"
                        name="ds_quantidade_produto"
                        required>
                </div>
                <div class="textoBancos col-sm-3">
                    <label>Descrição Produto:</label>
                    <input
                        type="text"
                        class="form-control"
                        name="ds_descricao_produto"
                        required>
                </div>
                <div class="textoBancos col-sm-3">
                    <label>Valor Produto:</label>
                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        name="ds_valor_produto"
                        required>
                </div>
                <div class="textoBancos col-sm-3 mt-3">
                    <label>Para Venda:</label>
                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        name="ds_valor_venda_produto"
                        required>
                </div>
                <div class="textoBancos col-sm-3 mt-3">
                    <input
                        style="margin-top: 24px;"
                        type="submit"
                        class="btn btn-outline-info"
                        value="Adicionar">
                </div>
            </div>
        </form>

        <div class="row mt-5">
            <div class="col-sm-12">
                <table class="tabela table table-striped table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>QUANTIDADE</th>
                        <th>DESCRIÇÃO</th>
                        <th>VALOR</th>
                        <th>VENDA</th>
                        <th>AÇÃO</th>
                    </tr>

                    <?php
                    if ($resultado->num_rows > 0) {
                        while ($produto = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $produto['cd_produto'] . "</td>";
                            echo "<td>" . $produto['nm_produto'] . "</td>";
                            echo "<td>" . $produto['ds_quantidade_produto'] . "</td>";
                            echo "<td>" . $produto['ds_descricao_produto'] . "</td>";
                            echo "<td>R$ " . $produto['ds_valor_produto'] . "</td>";
                            echo "<td>R$ " . $produto['ds_valor_venda_produto'] . "</td>";
                            echo "<td>";
                            echo "<a class='btn btn-danger' href='excluirCadastro.php?cd=" . $produto['cd_produto'] . "'>";
                            echo "Excluir";
                            echo "</a>";
                            echo "<a class='buttonEditar btn btn-warning' style='margin-left: 5%;' href='editarCadastro.php?cd=" . $produto['cd_produto'] . "'>";
                            echo "Editar";
                            echo "</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<td colspan='7' class='text-center'>Nenhum produto cadastrado.</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>

</html>