<?php
    $servidor = "localhost";
    $banco = "bancoLab";
    $usuario = "root";
    $senha = "";

    $conexao = new mysqli(
        $servidor,
        $usuario,
        $senha,
        $banco
    );

    if($conexao->connect_error){
        die("Erro na conexão: " . $conexao->connect_error);
    }
?>