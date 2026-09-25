<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sqlLab = "SELECT * FROM laboratorios WHERE cd_laboratorio = ?";

    $stmt = $conexao->prepare($sqlLab);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $laboratorio = $resultado->fetch_assoc();

    } else {

        echo "Laboratório não encontrado!";
        exit;

    }

} else {

    echo "Código do laboratório não informado!";
    exit;

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeLaboratorio = $_POST['nm_laboratorio'];
    $quantidadeComputadores = $_POST['ds_quantidade_computadores_laboratorio'];
    $disponibilidade = $_POST['ds_disponibilidade_laboratorio'];

    $sqlUpdate = "UPDATE laboratorios SET
                    nm_laboratorio = ?,
                    ds_quantidade_computadores_laboratorio = ?,
                    ds_disponibilidade_laboratorio = ?
                  WHERE cd_laboratorio = ?";

    $stmtUpdate = $conexao->prepare($sqlUpdate);

    $stmtUpdate->bind_param(
        "siii",
        $nomeLaboratorio,
        $quantidadeComputadores,
        $disponibilidade,
        $cd
    );

    $stmtUpdate->execute();

    header("Location: LABS.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Laboratório</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: black;
            min-height: 100vh;
        }

        .divPrincipal {
            background-color: white;
            width: 70%;
            margin: 5% auto;
            padding: 25px;
            border-radius: 10px;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">TecLAB's</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="divItensNavBar collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Outros
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="CadasProfessor.php">Cadastrar Professor</a></li>
                            <li><a class="dropdown-item" href="CadasLaboratorio.php">Cadastrar Laboratorio</a></li>
                            <li><a class="dropdown-item" href="CadasTurma.php">Cadastrar Turma</a></li>
                            <li><a class="dropdown-item" href="fazerReserva.php">Fazer Reserva</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="LABS.php">Laboratorios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PROFS.php">Professores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="TURMS.php">Turmas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="RESERVS.php">Reservas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="divPrincipal">

        <h2>Editar Laboratório</h2>

        <hr style="opacity: 1;">

        <form method="post">

            <label>Nome do laboratório:</label>

            <input type="text" name="nm_laboratorio" class="form-control"
                value="<?php echo $laboratorio['nm_laboratorio']; ?>" required>

            <br>

            <label>Quantidade de computadores:</label>

            <input type="number" name="ds_quantidade_computadores_laboratorio" class="form-control"
                value="<?php echo $laboratorio['ds_quantidade_computadores_laboratorio']; ?>" required>

            <br>

            <label>Disponibilidade:</label>

            <select name="ds_disponibilidade_laboratorio" class="form-select" required>

                <option value="1" <?php if ($laboratorio['ds_disponibilidade_laboratorio'] == 1)
                    echo "selected"; ?>>
                    Disponível
                </option>

                <option value="0" <?php if ($laboratorio['ds_disponibilidade_laboratorio'] == 0)
                    echo "selected"; ?>>
                    Indisponível
                </option>

            </select>

            <br>

            <button type="submit" class="btn btn-dark">
                Salvar alterações
            </button>

            <a href="LABS.php" class="btn btn-warning">
                Cancelar
            </a>

        </form>

    </div>

</body>

</html>