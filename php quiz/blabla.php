<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nomeEscrito'])) {
    $_SESSION['nome_usuario'] = htmlspecialchars($_POST['nomeEscrito']);
}


?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        .form-control {
            width: 600px;
        }

        .btn-warning {
            width: 100px;
            align-self: center;
        }

        .formulario-execucao {
            background-color: rgb(84, 84, 84);
            padding: 10px;
            border-radius: 15px;

            position: absolute;
            left: 50%;
            top: 40%;
            transform: translateX(-50%);
        }

        .form-label {
            color: white;
        }

        .corpo {
            background-color: black;
        }

        .botaoEnviar {
            background-color: yellow;
            border: none;
        }
    </style>

</head>

<body class="corpo">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <div class="formulario-execucao">
        <form action="quiz.php" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Digite seu nome: </label>
                <input type="text" name="nomeEscrito" class="form-control" id="nomeUsuarioEscrito">
            </div>
            <input type="submit" value="Enviar Nome" class="botaoEnviar">
        </form>
    </div>


</body>

</html>