<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "SELECT *
            FROM reservas
            WHERE cd_reserva = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $reserva = $resultado->fetch_assoc();

    } else {

        echo "Reserva não encontrada.";
        exit;

    }

} else {

    echo "Código da reserva não informado.";
    exit;

}

$sqlProfessores = "SELECT cd_professor, nm_professor
                   FROM professores
                   ORDER BY nm_professor";

$resultadoProfessores = $conexao->query($sqlProfessores);


$sqlLaboratorios = "SELECT cd_laboratorio, nm_laboratorio
                    FROM laboratorios
                    ORDER BY nm_laboratorio";

$resultadoLaboratorios = $conexao->query($sqlLaboratorios);


$sqlTurmas = "SELECT cd_turma, nm_turma
              FROM turmas
              ORDER BY nm_turma";

$resultadoTurmas = $conexao->query($sqlTurmas);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $quantidadeReserva = $_POST['ds_quantidade_reserva'];
    $tempoInicialReserva = $_POST['ds_tempo_inicial_reserva'];
    $tempoFinalReserva = $_POST['ds_tempo_final_reserva'];

    $idLaboratorio = $_POST['id_laboratorio'];
    $idProfessor = $_POST['id_professor'];
    $idTurma = $_POST['id_turma'];


    if ($tempoFinalReserva <= $tempoInicialReserva) {

        $erro = "O horário final deve ser maior que o horário inicial.";

    } else {


        $sqlConflito = "SELECT cd_reserva
                        FROM reservas
                        WHERE id_laboratorio = ?
                        AND ds_tempo_inicial_reserva < ?
                        AND ds_tempo_final_reserva > ?
                        AND cd_reserva != ?";

        $stmtConflito = $conexao->prepare($sqlConflito);

        $stmtConflito->bind_param(
            "issi",
            $idLaboratorio,
            $tempoFinalReserva,
            $tempoInicialReserva,
            $cd
        );

        $stmtConflito->execute();

        $resultadoConflito = $stmtConflito->get_result();


        if ($resultadoConflito->num_rows > 0) {

            $erro = "Este laboratório já está reservado nesse horário.";

        } else {


            $sqlUpdate = "UPDATE reservas SET
                            ds_quantidade_reserva = ?,
                            ds_tempo_inicial_reserva = ?,
                            ds_tempo_final_reserva = ?,
                            id_laboratorio = ?,
                            id_professor = ?,
                            id_turma = ?
                          WHERE cd_reserva = ?";

            $stmtUpdate = $conexao->prepare($sqlUpdate);

            $stmtUpdate->bind_param(
                "issiiii",
                $quantidadeReserva,
                $tempoInicialReserva,
                $tempoFinalReserva,
                $idLaboratorio,
                $idProfessor,
                $idTurma,
                $cd
            );


            if ($stmtUpdate->execute()) {

                header("Location: RESERVS.php");
                exit();

            } else {

                $erro = "Erro ao editar reserva: " . $stmtUpdate->error;

            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Reserva</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body {
            background-color: black;
            min-height: 100vh;
            margin: 0;
        }

        .navbar {
            width: 100%;
        }

        .conteudo {
            min-height: calc(100vh - 56px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .divPrincipal {
            background-color: white;
            width: 55%;
            padding: 30px;
            border-radius: 10px;
        }

        .divHorarios {
            display: flex;
            gap: 30px;
        }

        .divHorario {
            flex: 1;
        }

    </style>

</head>

<body>


    <nav class="navbar navbar-expand-lg bg-body-tertiary">

        <div class="container-fluid">

            <a class="navbar-brand" href="index.php">
                TecLAB's
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown"
                aria-expanded="false"
                aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse" id="navbarNavDropdown">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item dropdown">

                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
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

                        <a class="nav-link" href="RESERVS.php">
                            Reservas
                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <div class="conteudo">

        <div class="divPrincipal">

            <h2>Editar Reserva</h2>

            <hr style="opacity: 1;">


            <?php if (isset($erro)) { ?>

                <div class="alert alert-danger">

                    <?php echo $erro; ?>

                </div>

            <?php } ?>


            <form method="post">

                <label class="form-label">
                    Quantidade:
                </label>

                <input
                    type="number"
                    name="ds_quantidade_reserva"
                    class="form-control"
                    value="<?php echo $reserva['ds_quantidade_reserva']; ?>"
                    min="1"
                    required>


                <br>


                <div class="divHorarios">

                    <div class="divHorario">

                        <label class="form-label">
                            Horário inicial:
                        </label>

                        <input
                            type="time"
                            name="ds_tempo_inicial_reserva"
                            class="form-control"
                            value="<?php echo $reserva['ds_tempo_inicial_reserva']; ?>"
                            required>

                    </div>


                    <div class="divHorario">

                        <label class="form-label">
                            Horário final:
                        </label>

                        <input
                            type="time"
                            name="ds_tempo_final_reserva"
                            class="form-control"
                            value="<?php echo $reserva['ds_tempo_final_reserva']; ?>"
                            required>

                    </div>

                </div>


                <br>

                <label class="form-label">
                    Laboratório:
                </label>

                <select
                    name="id_laboratorio"
                    class="form-select"
                    required>

                    <option value="">
                        Selecione um laboratório
                    </option>

                    <?php while ($laboratorio = $resultadoLaboratorios->fetch_assoc()) { ?>

                        <option
                            value="<?php echo $laboratorio['cd_laboratorio']; ?>"
                            <?php
                            if ($laboratorio['cd_laboratorio'] == $reserva['id_laboratorio']) {
                                echo "selected";
                            }
                            ?>>

                            <?php echo $laboratorio['nm_laboratorio']; ?>

                        </option>

                    <?php } ?>

                </select>


                <br>

                <label class="form-label">
                    Professor:
                </label>

                <select
                    name="id_professor"
                    class="form-select"
                    required>

                    <option value="">
                        Selecione um professor
                    </option>

                    <?php while ($professor = $resultadoProfessores->fetch_assoc()) { ?>

                        <option
                            value="<?php echo $professor['cd_professor']; ?>"
                            <?php
                            if ($professor['cd_professor'] == $reserva['id_professor']) {
                                echo "selected";
                            }
                            ?>>

                            <?php echo $professor['nm_professor']; ?>

                        </option>

                    <?php } ?>

                </select>


                <br>

                <label class="form-label">
                    Turma:
                </label>

                <select
                    name="id_turma"
                    class="form-select"
                    required>

                    <option value="">
                        Selecione uma turma
                    </option>

                    <?php while ($turma = $resultadoTurmas->fetch_assoc()) { ?>

                        <option
                            value="<?php echo $turma['cd_turma']; ?>"
                            <?php
                            if ($turma['cd_turma'] == $reserva['id_turma']) {
                                echo "selected";
                            }
                            ?>>

                            <?php echo $turma['nm_turma']; ?>

                        </option>

                    <?php } ?>

                </select>


                <br>

                <button
                    type="submit"
                    class="btn btn-dark">

                    Salvar alterações

                </button>


                <a
                    href="RESERVS.php"
                    class="btn btn-warning">

                    Cancelar

                </a>

            </form>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>