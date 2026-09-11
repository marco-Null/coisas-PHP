<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {
    $cd = $_GET['cd'];
    $sql = "SELECT cd_produto, ds_quantidade_venda FROM vendas WHERE cd_venda = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $venda = $resultado->fetch_assoc();

    if ($venda) {

        $produto = $venda['cd_produto'];
        $quantidade = $venda['ds_quantidade_venda'];
        $sqlEstoque = "UPDATE produtos SET ds_quantidade_produto = ds_quantidade_produto + ? WHERE cd_produto = ?";
        $stmtEstoque = $conexao->prepare($sqlEstoque);
        $stmtEstoque->bind_param(
            "ii",
            $quantidade,
            $produto
        );

        $stmtEstoque->execute();

        $sql = "DELETE FROM vendas
                WHERE cd_venda = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $cd);

        if ($stmt->execute()) {

            header("Location: vendas.php");
            exit();
        } else {
            echo "Erro ao excluir venda: " . $stmt->error;
        }
    } else {
        echo "Venda não encontrada.";
    }
} else {
    echo "Código da venda não informado.";
}

?>