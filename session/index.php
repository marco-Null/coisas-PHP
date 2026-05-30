<?php
session_start();

$emailCorreto = "juninho123@gmail.com";
$senhaCorreta = "10199967";

if (isset($_POST['emailAdicionado']) && $_POST['emailAdicionado'] != $emailCorreto && $_POST['senhaAdicionada'] != $senhaCorreta){
    echo "Email ou Senha incorretos!";
    return;
}

else{
    header('Location:result.php');
    session_destroy();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadastro</title>
</head>
<body>
    <form action="index.php" method="post">

    <label for=""> E-mail: </label>
        <input type="email" name="emailAdicionado"><br><br>

    <label for="">Senha: </label>
        <input type="password" name="senhaAdicionada"><br><br>

    <input type="submit" value="Cadastrar">

    </form>
</body>
</html>