<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "SELECT cd_produto, ds_quantidade_compra FROM compras WHERE cd_compra = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $compra = $resultado->fetch_assoc();

    if ($compra) {

        $produto = $compra['cd_produto'];
        $quantidade = $compra['ds_quantidade_compra'];

        $sqlEstoque = "UPDATE produtos
                       SET ds_quantidade_produto =
                       ds_quantidade_produto - ?
                       WHERE cd_produto = ?";

        $stmtEstoque = $conexao->prepare($sqlEstoque);
        $stmtEstoque->bind_param("ii", $quantidade, $produto);
        $stmtEstoque->execute();

        $sql = "DELETE FROM compras WHERE cd_compra = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $cd);

        if ($stmt->execute()) {
            header("Location: compras.php");
            exit();
        } 
        
        else {
            echo "Erro ao excluir compra: " . $stmt->error;
        }
    } 
    
    else {
        echo "Compra não encontrada.";
    }
} 

else {
    echo "Código da compra não informado.";
}

?>