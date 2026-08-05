<?php

    if(isset($_POST['NomeTexto']) && ($_POST['EmailTexto'])){
        $nomeDoUsuario = $_POST['NomeTexto'];
        $EmailDoUsuario = $_POST['EmailTexto'];
    

    setcookie(
        "NomeUsuario",
        $nomeDoUsuario,
        time() + 3670,
        "/"
    );
    
    setcookie(
        "EmailUsuario",
        $EmailDoUsuario,
        time() + 3670,
        "/"
    );
    
        echo "Nome e Email Adicionados!";
    }

    else if(isset($_COOKIE['NomeUsuario']) && ($_COOKIE['EmailUsuario'])){
        Echo "Você adicionou o nome: " . $_COOKIE['NomeUsuario'] . " E o Email: " . $_COOKIE['EmailUsuario'];
    }

    else{
        echo "Adicione Nome e Email";
    }

    if(isset($_POST['LimparDados'])){
        setcookie(
        "NomeUsuario",
        "",
        time() - 3670,
        "/"
    );
    
    setcookie(
        "EmailUsuario",
        "",
        time() - 3670,
        "/"
    );
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>terceira atividade coockies</title>
</head>
<body>
    <form action="" method="post">
        <br><br>
        <label for="">Nome</label>
        <input type="text" name="NomeTexto">
        <br><br>
        <label for="">Email: </label>
        <input type="email" name="EmailTexto" id=""><br><br>

        <input type="submit" value="Enviar">
        <input type="submit" value="Limpar" name="LimparDados">

    </form>
</body>
</html>