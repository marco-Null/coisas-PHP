<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante</title>

    <style>
        .divComprovante{
            width: 30%; 
            height: 440px; 
            background-color: white;
            position: absolute;
            left: 50%;
            top: 10%;
            transform: translateX(-50%);
            border-radius: 20px;
            border: 3px solid black;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="divComprovante">
    
     <h2 style="text-align: center; margin-top: 10px; margin-bottom: 40px;"> informações fornecidas</h2>
     
    <?php

    $nome = $_POST['nomeComplet'];
    $idade = $_POST['idadeUser'];
    $email = $_POST['emailUser'];
    $cursoSelect = $_POST['opcoesCurso'];
    $etecSelect = $_POST['opcoesEtec'];
    $cpf = $_POST['cpfUser'];

    function vlCpf($cpf){
    $cpf = preg_replace("/[^0-9]/", "", $cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    $digito1 = 0;
    $digito2 = 0;

    for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
        $digito1 += $cpf[$i] * $x;
    }

    for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
        $digito2 += $cpf[$i] * $x;
    }

    $calculo1 = (($digito1 % 11) < 2) ? 0 : 11 - ($digito1 % 11);
    $calculo2 = (($digito2 % 11) < 2) ? 0 : 11 - ($digito2 % 11);

    return ($calculo1 == $cpf[9] && $calculo2 == $cpf[10]);
}

        if (vlCpf($cpf)){
            echo "<h3>Seu nome é: $nome </h3>";
            echo "<h3>Sua idade é: $idade </h3>";
            echo "<h3>Seu E-mail é: $email </h3>";
            echo "<h3>O curso escolhido foi: $cursoSelect</h3>";
            echo "<h3>A etec escolhida foi: $etecSelect</h3>";
            echo "<h3>Seu CPF é: $cpf </h3>";
        }
        else{
            echo "<h2>seu cpf foi invalido!</h2>";
        }

    ?>
    
    <form action="index.html">
        <input type="submit" value="voltar ao site" style="width: 50%; height: 50px;  position: absolute; top: 75%; left: 50%; transform: translateX(-50%);">
    </form>

    <h2 style="position: absolute; top: 85%; left: 25%;"> NOS VEMOS NA ETEC! </h2>

    </div>
</body>
</html>