<?php
session_start();

$nomeGuardado = isset($_SESSION['nome_usuario']) || $_SESSION['nome_usuario'];

echo $nomeGuardado;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <text>
        <?php echo $nomeGuardado ?>
    <text>
</body>
</html>