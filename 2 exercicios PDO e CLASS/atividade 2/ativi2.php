<?php
    $user = 'root';
    $pass = 'usbw';

    try{
        $pdo = new PDO('mysql:host=localhost;dbname=bancopdo', $user, $pass);
    }
    catch(PDOException $e){
        echo "Erro de conexão";
    }

    $std = $pdo->query('SELECT nm_instrumento FROM instrumentos');

?>