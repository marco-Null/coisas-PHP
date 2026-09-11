<?php
include('inc/conexao.php');

if (isset($_GET['cd'])) {
    $cd = $_GET['cd'];
    $sql = "DELETE FROM produtos WHERE cd_produto = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    if ($stmt->execute()) {
        header("Location: cadastroProduto.php");
        exit();
    } else {
        echo "Erro ao excluir produto: " . $stmt->error;
    }
} else {
    echo "Código do produto não informado.";
}
?>