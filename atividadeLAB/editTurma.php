<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "SELECT *
            FROM turmas
            WHERE cd_turma = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $turma = $resultado->fetch_assoc();

    } else {

        echo "Turma não encontrada.";
        exit;

    }

} else {

    echo "Código da turma não informado.";
    exit;

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeTurma = $_POST['nm_turma'];
    $quantidadeAlunos = $_POST['ds_quantidade_alunos_turma'];
    $cursoTurma = $_POST['ds_curso_turma'];


    $sqlUpdate = "UPDATE turmas SET
                    nm_turma = ?,
                    ds_quantidade_alunos_turma = ?,
                    ds_curso_turma = ?
                  WHERE cd_turma = ?";

    $stmtUpdate = $conexao->prepare($sqlUpdate);

    $stmtUpdate->bind_param(
        "sisi",
        $nomeTurma,
        $quantidadeAlunos,
        $cursoTurma,
        $cd
    );


    if ($stmtUpdate->execute()) {

        header("Location: TURMS.php");
        exit();

    } else {

        echo "Erro ao editar turma: " . $stmtUpdate->error;

    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Turma</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body {
            background-color: black;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .divPrincipal {
            background-color: white;
            width: 50%;
            padding: 30px;
            border-radius: 10px;
        }

    </style>

</head>

<body>
    

<div class="divPrincipal">

    <h2>Editar Turma</h2>

    <hr style="opacity: 1;">

    <form method="post">

        <label class="form-label">
            Nome da turma:
        </label>

        <input
            type="text"
            name="nm_turma"
            class="form-control"
            value="<?php echo $turma['nm_turma']; ?>"
            required
        >

        <br>


        <label class="form-label">
            Quantidade de alunos:
        </label>

        <input
            type="number"
            name="ds_quantidade_alunos_turma"
            class="form-control"
            value="<?php echo $turma['ds_quantidade_alunos_turma']; ?>"
            min="1"
            required
        >

        <br>


        <label class="form-label">
            Curso:
        </label>

        <input
            type="text"
            name="ds_curso_turma"
            class="form-control"
            value="<?php echo $turma['ds_curso_turma']; ?>"
            required
        >

        <br>


        <button type="submit" class="btn btn-dark">
            Salvar alterações
        </button>

        <a href="TURMS.php" class="btn btn-warning">
            Cancelar
        </a>

    </form>

</div>

</body>

</html>