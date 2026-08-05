<?php

    if(isset($_COOKIE['Acesso'])){
        $AcessosAtivos = $_COOKIE['Acesso'] + 1;   
    }

    else{
        $AcessosAtivos = 1;
    }

    setcookie(
        "Acesso",
        $AcessosAtivos,
        time() + 3670,
        "/"
    );

    echo "Numero de acessos atual é: " . $AcessosAtivos;

    if(isset($_POST['LimparAcessos'])){
        setcookie(
        "Acesso",
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
    <title>Segunda atividade coockies</title>
</head>
<body>
    <form action="" method="POST">
        <br>
        <input type="submit" value="Limpar os Acessos" name="LimparAcessos">
    </form>
</body>
</html>