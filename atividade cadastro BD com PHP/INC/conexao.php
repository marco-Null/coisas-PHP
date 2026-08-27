<?php

    $servidor = "localhost";
    $banco = "Bancoetec";
    $usuario = "root";
    $senha = "";

    $conexao = new mysqli($servidor, $usuario, $senha, $banco );

    if($conexao->connect_error){
        echo "erro de conexão!" . $conexao->connect_error;
    }
    else{
        /* echo "Conectado!"; */
    }

?>