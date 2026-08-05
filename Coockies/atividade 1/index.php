<?php 

    if(isset($_POST['TextoCidade'])){

        $nomeCidade = $_POST['TextoCidade'];
        
        setcookie(
            "cidade",
            $nomeCidade,
            time() + 3670,
            "/"   
        );  

        echo "a cidade adicionada! atualize a página";
    }

    else if(isset($_COOKIE['cidade'])){
        echo "a cidade escolhida é: " . $_COOKIE['cidade'];
    }

    else{
        echo "nenhuma cidade foi adicionada";
    }

    if(isset($_POST['LimparCoockies'])){
        setcookie(
            "cidade",
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
    <title>ATIVIDADES COOCKIES</title>
</head>
<body>
    
    <form action="" method="post">
        <br><br>
        <label for="">Escreva o nome da cidade</label><br><br><br>

        <input type="text" name="TextoCidade"><br><br>
        <input type="submit" value="Enviar">

        <input type="submit" value="Limpar" name="LimparCoockies">
    </form>
</body>
</html>