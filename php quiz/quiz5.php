<?php
session_start();

$nomeGuardado = $_SESSION['nome_usuario'] ?? '';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $respostaCorreta = $_POST['qst1'];


    if ($respostaCorreta == "1") {
        $_SESSION['pontos'] = ($_SESSION['pontos'] ?? 0) + 1;
    }
    else{
        $_SESSION['pontos'] = ($_SESSION['pontos'] ?? 0) + 0;
    }

    header("Location: quiz6.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZ</title>

    <style>
        .corpo {
            background-image: url('imagens/terra.jfif');
        }

        .primeiraQST {
            background-image: url('imagens/obsidia.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-size: 180px;

            color: white;
            width: 650px;
            border-radius: 20px;
            font-size: 30px;
            padding: 10px;
            margin-top: 10px;
            transform: translateY(100px);

            justify-self: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .botaoProximo {
            font-size: 30px;
            transform: translateY(130px) translateX(650px);
            color: white;
            background-color: black;
            border-color: green;

        }
    </style>
</head>

<body class="corpo">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <form action="quiz5.php" method="post">
        <div class="primeiraQST">
            <h2>Qual picareta minera Obsidia mais rapido ?</h2><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qst1" value="1" id="RespostaCerta">
                <label class="form-check-label" for="radioDefault1">
                    Picareta de Netherite
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qst1" value="2" id="RespostaErrada">
                <label class="form-check-label" for="radioDefault2">
                    Picareta de Diamante
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qst1" value="3" id="RespostaErrada2">
                <label class="form-check-label" for="radioDefault3">
                    Picareta de Ouro + eficiencia V
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="qst1" value="4" id="RespostaErrada3">
                <label class="form-check-label" for="radioDefault4">
                    Picareta de madeira
                </label>
            </div>
        </div>

        <input type="submit" value="Proxima Questão ->" class="botaoProximo">

    </form>

</body>

</html>