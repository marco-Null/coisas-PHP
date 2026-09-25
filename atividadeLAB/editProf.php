<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "SELECT * FROM professores WHERE cd_professor = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $professor = $resultado->fetch_assoc();

    } else {

        echo "Professor não encontrado.";
        exit;

    }

} else {

    echo "Código do professor não informado.";
    exit;

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeProfessor = $_POST['nm_professor'];
    $emailProfessor = $_POST['ds_email_professor'];

    $sqlUpdate = "UPDATE professores SET
                    nm_professor = ?,
                    ds_email_professor = ?
                  WHERE cd_professor = ?";

    $stmtUpdate = $conexao->prepare($sqlUpdate);

    $stmtUpdate->bind_param(
        "ssi",
        $nomeProfessor,
        $emailProfessor,
        $cd
    );

    if ($stmtUpdate->execute()) {

        header("Location: PROFS.php");
        exit();

    } else {

        echo "Erro ao editar professor: " . $stmtUpdate->error;

    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Professor</title>

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
        
        .divItensNavBar {
            margin-left: 65%;
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

        <h2>Editar Professor</h2>

        <hr style="opacity: 1;">

        <form method="post">

            <label>Nome do professor:</label>

            <input
                type="text"
                name="nm_professor"
                class="form-control"
                value="<?php echo $professor['nm_professor']; ?>"
                required
            >

            <br>

            <label>E-mail do professor:</label>

            <input
                type="email"
                name="ds_email_professor"
                class="form-control"
                value="<?php echo $professor['ds_email_professor']; ?>"
                required
            >

            <br>

            <button type="submit" class="btn btn-dark">
                Salvar alterações
            </button>

            <a href="PROFS.php" class="btn btn-warning">
                Cancelar
            </a>

        </form>

    </div>

</body>

</html>