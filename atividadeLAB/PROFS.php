<?php

include('inc/conexao.php');

$sqlProfessor = "SELECT 
                    professores.cd_professor,
                    professores.nm_professor,
                    professores.ds_email_professor,
                    GROUP_CONCAT(materias.nm_materia SEPARATOR ', ') AS materias
                 FROM professores
                 LEFT JOIN professores_materias 
                    ON professores.cd_professor = professores_materias.id_professor
                 LEFT JOIN materias 
                    ON professores_materias.id_materia = materias.cd_materia
                 GROUP BY professores.cd_professor";

$resultadoProfessores = $conexao->query($sqlProfessor);

$mensagem = "";

if (isset($_GET['erro']) && $_GET['erro'] == 'reservas') {

    $mensagem = "<div class='alert alert-danger'>
                    Não é possível excluir este professor porque existem reservas cadastradas para ele.
                 </div>";

}

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'excluido') {

    $mensagem = "<div class='alert alert-success'>
                    Professor excluído com sucesso!
                 </div>";

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Professores</title>

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
            width: 90%;
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

        <div class="titulo">

            <h2>Professores</h2>

            <a href="CadasProfessor.php" class="btn btn-dark">
                Cadastrar Professor
            </a>

        </div>

        <hr style="opacity: 1;">

        <?php echo $mensagem; ?>

        <table class="table table-dark table-hover">

            <thead>

                <tr>

                    <th>Código</th>

                    <th>Nome</th>

                    <th>E-mail</th>

                    <th>Materias</th>

                    <th>Ações</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($professor = $resultadoProfessores->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $professor['cd_professor']; ?>
                        </td>

                        <td>
                            <?php echo $professor['nm_professor']; ?>
                        </td>

                        <td>
                            <?php echo $professor['ds_email_professor']; ?>
                        </td>

                        <td>
                            <?php echo $professor['materias'] ?? 'Nenhuma matéria'; ?>
                        </td>

                        <td>

                            <a href="editProf.php?cd=<?php echo $professor['cd_professor']; ?>"
                                class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <a href="excluirProf.php?cd=<?php echo $professor['cd_professor']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir este professor?');">
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