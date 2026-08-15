<?php

    $servidor = "localhost";
    $banco = "escola";
    $usuario = "root";
    $senha = "usbw";

    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if($conexao->connect_error){
        echo "Erro de conexção!";
    }
    else{
        echo "conectado";
    }

?>
