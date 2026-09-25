<?php
session_start();
include('inc/conexao.php');

$sqlProfessores = "SELECT cd_professor, nm_professor FROM professores";
$resultadoProfessores = $conexao->query($sqlProfessores);

$sqlLaboratorios = "SELECT cd_laboratorio, nm_laboratorio FROM laboratorios";
$resultadoLaboratorios = $conexao->query($sqlLaboratorios);

$sqlTurmas = "SELECT cd_turma, nm_turma FROM turmas";
$resultadoTurmas = $conexao->query($sqlTurmas);

$mensagem = "";

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idProfessor = $_POST['id_professor'];
    $idLaboratorio = $_POST['id_laboratorio'];
    $idTurma = $_POST['id_turma'];

    $tempoInicialReserva = $_POST['ds_tempo_inicial_reserva'];
    $tempoFinalReserva = $_POST['ds_tempo_final_reserva'];

    if (empty($idProfessor)) {

        $mensagem = "<div class='alert alert-danger'>
                        Selecione um professor!
                    </div>";

    } elseif (empty($idLaboratorio)) {

        $mensagem = "<div class='alert alert-danger'>
                        Selecione um laboratório!
                    </div>";

    } elseif (empty($idTurma)) {

        $mensagem = "<div class='alert alert-danger'>
                        Selecione uma turma!
                    </div>";

    } elseif (empty($tempoInicialReserva)) {

        $mensagem = "<div class='alert alert-danger'>
                        Digite o tempo inicial de entrada!
                    </div>";

    } elseif (empty($tempoFinalReserva)) {

        $mensagem = "<div class='alert alert-danger'>
                        Digite o tempo final de saída!
                    </div>";

    } elseif ($tempoInicialReserva >= $tempoFinalReserva) {

        $mensagem = "<div class='alert alert-danger'>
                        O horário final deve ser depois do horário inicial!
                    </div>";

    } else {

        $sqlVerifica = "SELECT cd_reserva 
                        FROM reservas
                        WHERE id_laboratorio = ?
                        AND ds_tempo_inicial_reserva < ?
                        AND ds_tempo_final_reserva > ?";

        $stmtVerifica = $conexao->prepare($sqlVerifica);

        $stmtVerifica->bind_param(
            "iss",
            $idLaboratorio,
            $tempoFinalReserva,
            $tempoInicialReserva
        );

        $stmtVerifica->execute();

        $resultado = $stmtVerifica->get_result();

        if ($resultado->num_rows > 0) {

            $mensagem = "<div class='alert alert-danger'>
                            Não é possível fazer a reserva! 
                            Esse laboratório já está reservado nesse horário.
                        </div>";

        } else {

            $reservasFeitas = 1;

            $sqlReserva = "INSERT INTO reservas 
                (ds_quantidade_reserva,
                 ds_tempo_inicial_reserva,
                 ds_tempo_final_reserva,
                 id_laboratorio,
                 id_professor,
                 id_turma)
                VALUES (?, ?, ?, ?, ?, ?)";

            $stmtReserva = $conexao->prepare($sqlReserva);

            $stmtReserva->bind_param(
                "issiii",
                $reservasFeitas,
                $tempoInicialReserva,
                $tempoFinalReserva,
                $idLaboratorio,
                $idProfessor,
                $idTurma
            );

            $stmtReserva->execute();

            $_SESSION['mensagem'] = "<div class='AlertaCadastro alert alert-success' role='alert'>
                            Reserva feita com sucesso!
                         </div>";

            header("Location: fazerReserva.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Reserva</title>

    <style>
        .body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            background-color: black;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .divPrincipal {
            background-color: white;
            color: black;
            padding: 15px;
            width: 70%;
            border-radius: 10px;
            margin-top: 5%;
        }

        .navbar {
            width: 100%;
        }

        .divItensNavBar {
            margin-left: 65%;
        }

        .AlertaCadastro {
            text-align: center;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="body">
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
        <h2>Fazer Reserva</h2>
        <hr style="opacity: 1;">

        <form method="post">
            <label for="">professor que quer a reserva: </label>

            <select class="form-select" name="id_professor" required>

                <option value="" selected>Selecione um professor</option>

                <?php while ($professor = $resultadoProfessores->fetch_assoc()) { ?>

                    <option value="<?php echo $professor['cd_professor']; ?>">
                        <?php echo $professor['nm_professor']; ?>
                    </option>

                <?php } ?>

            </select><br>

            <label for="">Qual Laboratorio será usado:</label>

            <select class="form-select" name="id_laboratorio" required>

                <option value="" selected>Selecione um laboratório</option>

                <?php while ($laboratorio = $resultadoLaboratorios->fetch_assoc()) { ?>

                    <option value="<?php echo $laboratorio['cd_laboratorio']; ?>">
                        <?php echo $laboratorio['nm_laboratorio']; ?>
                    </option>

                <?php } ?>

            </select><br>

            <label for="">Qual Turma irá ao LAB: </label>

            <select class="form-select" aria-label="Default select example" name="id_turma">
                <option selected>Selecione uma turma</option>

                <?php while ($Turma = $resultadoTurmas->fetch_assoc()) { ?>

                    <option value="<?php echo $Turma['cd_turma']; ?>">
                        <?php echo $Turma['nm_turma']; ?>
                    </option>

                <?php } ?>
            </select><br>

            <label for="">que horas deseja entrar: </label>
            <input type="time" name="ds_tempo_inicial_reserva" id="tempo inicial" placeholder="tempo inicial"
                required><br><br>

            <label for="">que horas deseja sair: </label>
            <input type="time" name="ds_tempo_final_reserva" id="tempo final" placeholder="tempo final"
                required><br><br>


            <button type="submit" class="btn btn-dark">Reservar</button>

        </form>
    </div>

    <?php echo "<br>" . $mensagem ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>