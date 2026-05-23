<?php
    session_start();

    
$emailCerto = "Marco001@gmail.com";
$senhaCerta = "67";

    if ($_POST['email'] == $emailCerto) && ($_POST['senha'] == $senhaCerta) {

    }


    if(isset($_POST['email']) == $emailCerto && isset($_POST['senha']) == $senhaCerta){

        $_SESSION['login'] = true;
        $_SESSION['senha'] = true;
        header('location: result.php');

    }

    else {
        $_SESSION['login'] = false;
        $_SESSION['senha'] = false;

    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP</title>
</head>
<body>
    <form method="post">
        <label for="">cadastro </label>
        <input type="email" name="email"><br><br>

        <label for="">senha</label>
        <input type="password" name="senha"><br><Br><br>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>