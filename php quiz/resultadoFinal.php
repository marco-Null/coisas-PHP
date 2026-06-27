<?php
session_start();

$pontos = $_SESSION['pontos'] ?? 0;
$tempo = $_SESSION['tempoFinalizado'] ?? 0;
$nome = $_SESSION['nome_usuario'] ?? 'Anônimo';

if (!isset($_SESSION['salvouRanking'])) {

    $informacoes = $nome . "|" . $pontos . "|" . $tempo . "\n";

    file_put_contents("ranking.txt", $informacoes, FILE_APPEND);

    $_SESSION['salvouRanking'] = true;
}

$arquivo = file("ranking.txt");

$ranking = [];

foreach ($arquivo as $linha) {

    $linha = trim($linha);

    list($nomeJogador, $pontosJogador, $tempoJogador) = explode("|", $linha);

    $ranking[] = [
        "nome" => $nomeJogador,
        "pontos" => $pontosJogador,
        "tempo" => $tempoJogador
    ];
}
usort($ranking, function ($a, $b) {

    if ($a["pontos"] == $b["pontos"]) {
        return $a["tempo"] - $b["tempo"];
    }

    return $b["pontos"] - $a["pontos"];

});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do quiz</title>

    <style>
        .corpo {
            background-image: url('imagens/terra.jfif');
            overflow: hidden;
        }

        .tabela_Informacao {
            background-image: url('imagens/obsidia.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-size: 160px;
            border-radius: 5px;
            font-size: 4px;
            color: white;
            padding: 20px;

            justify-self: center;
            transform: translateY(20px);
        }

        .tabela_RANK{
            background-image: url('imagens/obsidia.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-size: 180px;
            border-radius: 5px;
            color: white;
            padding: 15px;

            justify-self: center;
            transform: translateY(-40px);
            height: 530px;
        }

        .botao_reiniciar{
            font-size: 40px;
            position: absolute;
            top: 88%;
            right: 10%;
            background-color: black;
            color: white;
            border-color: purple;
        }
    </style>
</head>

<body class="corpo">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

        <form action="reset.php">
            <input type="submit" value="Reiniciar Quiz" class="botao_reiniciar">
        </form>

    <div class="tabela_Informacao">
        <h2>Nome: <?php echo $nome ?> </h2><br>
        <h2>Acertos: <?php echo $pontos ?>/10 </h2><br>
        <h2>Tempo feito: <?php echo $tempo ?> </h2>
    </div>

    <div class="tabela_RANK" style="margin-top:80px;">

        <h2>TOP 10</h2>

        <table class="table table-dark table-striped">

            <tr>
                <th>Posição</th>
                <th>Nome</th>
                <th>Pontos</th>
                <th>Tempo</th>
            </tr>

            <?php

            for ($i = 0; $i < min(10, count($ranking)); $i++) {

                echo "<tr>";

                echo "<td>" . ($i + 1) . "º</td>";

                echo "<td>" . $ranking[$i]["nome"] . "</td>";

                echo "<td>" . $ranking[$i]["pontos"] . "/10</td>";

                echo "<td>" . $ranking[$i]["tempo"] . " s</td>";

                echo "</tr>";

            }

            ?>

        </table>

    </div>

</body>

</html>