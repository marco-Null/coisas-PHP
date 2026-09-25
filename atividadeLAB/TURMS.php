<?php

include('inc/conexao.php');

$sqlTurmas = "SELECT * FROM turmas";
$resultadoTurmas = $conexao->query($sqlTurmas);

$mensagem = "";

if (isset($_GET['erro']) && $_GET['erro'] == 'reservas') {

    $mensagem = "<div class='alert alert-danger'>
                    Não é possível excluir esta turma porque existem reservas cadastradas para ela.
                 </div>";

}

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'excluido') {

    $mensagem = "<div class='alert alert-success'>
                    Turma excluída com sucesso!
                 </div>";

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Turmas</title>

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
                        <a class="nav-link active" aria-current="page" href="TURMS.php">
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


    <div class="divPrincipal">

        <div class="titulo">

            <h2>Turmas</h2>

            <a href="CadasTurma.php" class="btn btn-dark">
                Cadastrar Turma
            </a>

        </div>

        <hr style="opacity: 1;">

        <?php echo $mensagem; ?>

        <table class="table table-dark table-hover">

            <thead>

                <tr>

                    <th>Código</th>

                    <th>Turma</th>

                    <th>Quantidade de Alunos</th>

                    <th>Curso</th>

                    <th>Ações</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($turma = $resultadoTurmas->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $turma['cd_turma']; ?>
                        </td>

                        <td>
                            <?php echo $turma['nm_turma']; ?>
                        </td>

                        <td>
                            <?php echo $turma['ds_quantidade_alunos_turma']; ?>
                        </td>

                        <td>
                            <?php echo $turma['ds_curso_turma']; ?>
                        </td>

                        <td>

                            <a href="editTurma.php?cd=<?php echo $turma['cd_turma']; ?>" class="btn btn-warning btn-sm">

                                Editar

                            </a>

                            <a href="excluirTurma.php?cd=<?php echo $turma['cd_turma']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir esta turma?');">

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