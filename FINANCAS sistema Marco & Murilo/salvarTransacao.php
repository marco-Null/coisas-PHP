<?php
session_start();
include_once('inc/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: entrarConta.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUsuario = $_SESSION['usuario_id'];
    $tipo = $_POST['tipoMovimentacao'] ?? '';
    $valor = $_POST['valorMovimentacao'] ?? 0;
    $descricao = trim($_POST['descricaoMovimentacao'] ?? '');
    $data = $_POST['dataMovimentacao'] ?? date('Y-m-d');
    $idBanco = $_POST['idBanco'] ?? null;
    $formaPagamento = $_POST['formaPagamento'] ?? 'Pix';

    try {
        if ($tipo == 'deposito') {
            $sql = "INSERT INTO tb_depositos (ds_valor_deposito, dt_deposito, ds_forma_pagamento, id_usuario, id_banco) VALUES (:valor, :data, :forma, :usuario, :banco)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':valor' => $valor,
                ':data' => $data,
                ':forma' => $formaPagamento,
                ':usuario' => $idUsuario,
                ':banco' => $idBanco
            ]);
        } else if ($tipo == 'saque') {
            $idCategoria = $_POST['idCategoria'] ?? null;

            $sql = "INSERT INTO tb_saques (nm_saque, ds_valor_saque, dt_saque, ds_tipo_saque, id_usuario, id_banco, id_categoria) VALUES (:nome, :valor, :data, :forma, :usuario, :banco, :categoria)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $descricao,
                ':valor' => $valor,
                ':data' => $data,
                ':forma' => $formaPagamento,
                ':usuario' => $idUsuario,
                ':banco' => $idBanco,
                ':categoria' => $idCategoria
            ]);
        }

        header('Location: inicial.php');
        exit;

    } catch (PDOException $e) {
        echo "Erro ao salvar transação: " . $e->getMessage();
    }
}
?>