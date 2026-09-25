<?php

include('inc/conexao.php');

$sqlReservas = "SELECT
                    reservas.cd_reserva,
                    reservas.ds_quantidade_reserva,
                    reservas.ds_tempo_inicial_reserva,
                    reservas.ds_tempo_final_reserva,
                    laboratorios.nm_laboratorio,
                    professores.nm_professor,
                    turmas.nm_turma

                FROM reservas

                INNER JOIN laboratorios
                    ON reservas.id_laboratorio = laboratorios.cd_laboratorio

                INNER JOIN professores
                    ON reservas.id_professor = professores.cd_professor

                INNER JOIN turmas
                    ON reservas.id_turma = turmas.cd_turma

                ORDER BY reservas.cd_reserva DESC";

$resultadoReservas = $conexao->query($sqlReservas);

$mensagem = "";

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'excluido') {

    $mensagem = "<div class='alert alert-success'>
                    Reserva excluída com sucesso!
                 </div>";

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: black;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .divPrincipal {
            background-color: white;
            width: 95%;
            margin: 5% auto;
            padding: 25px;
            border-radius: 10px;
        }

        .titulo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .divItensNavBar {
            margin-left: 65%;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">

        <div class="container-fluid">

            <a class="navbar-brand" href="index.php">
                TecLAB's
            </a>

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

                            <li>
                                <a class="dropdown-item" href="CadasProfessor.php">
                                    Cadastrar Professor
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="CadasLaboratorio.php">
                                    Cadastrar Laboratorio
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="CadasTurma.php">
                                    Cadastrar Turma
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="fazerReserva.php">
                                    Fazer Reserva
                                </a>
                            </li>

                        </ul>

                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="LABS.php">
                            Laboratorios
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="PROFS.php">
                            Professores
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="TURMS.php">
                            Turmas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="RESERVS.php">
                            Reservas
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <div class="divPrincipal">

        <div class="titulo">

            <h2>Reservas</h2>

            <a href="fazerReserva.php" class="btn btn-dark">
                Fazer Reserva
            </a>

        </div>

        <hr style="opacity: 1;">

        <?php echo $mensagem; ?>

        <table class="table table-dark table-hover">

            <thead>

                <tr>

                    <th>Código</th>
                    <th>Quantidade</th>
                    <th>Horário Inicial</th>
                    <th>Horário Final</th>
                    <th>Laboratório</th>
                    <th>Professor</th>
                    <th>Turma</th>
                    <th>Ações</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($reserva = $resultadoReservas->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $reserva['cd_reserva']; ?>
                        </td>

                        <td>
                            <?php echo $reserva['ds_quantidade_reserva']; ?>
                        </td>

                        <td>
                            <?php echo date('H:i', strtotime($reserva['ds_tempo_inicial_reserva'])); ?>
                        </td>

                        <td>
                            <?php echo date('H:i', strtotime($reserva['ds_tempo_final_reserva'])); ?>
                        </td>

                        <td>
                            <?php echo $reserva['nm_laboratorio']; ?>
                        </td>

                        <td>
                            <?php echo $reserva['nm_professor']; ?>
                        </td>

                        <td>
                            <?php echo $reserva['nm_turma']; ?>
                        </td>

                        <td>

                            <a href="editReserva.php?cd=<?php echo $reserva['cd_reserva']; ?>"
                                class="btn btn-warning btn-sm">

                                Editar

                            </a>

                            <a href="excluirReserva.php?cd=<?php echo $reserva['cd_reserva']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir esta reserva?');">

                                Excluir

                            </a>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>