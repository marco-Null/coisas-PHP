<?php

include('inc/conexao.php');

$sqlLaboratorio = "SELECT * FROM laboratorios";
$resultadoLaboratorios = $conexao->query($sqlLaboratorio);

$mensagem = "";

if (isset($_GET['erro']) && $_GET['erro'] == 'reservas') {

    $mensagem = "<div class='alert alert-danger'>
                    Não é possível excluir este Laboratorio porque existem reservas cadastradas para ele.
                 </div>";

}

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'excluido') {

    $mensagem = "<div class='alert alert-success'>
                    Laboratorio excluído com sucesso!
                 </div>";

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laboratórios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: black;
            min-height: 100vh;
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

            <h2>Laboratórios</h2>

            <a href="CadasLaboratorio.php" class="btn btn-dark">
                Cadastrar Laboratório
            </a>

        </div>

        <hr>

        <?php echo $mensagem; ?>


        <table class="table table-dark table-hover">

            <thead>

                <tr>
                    <th>Código</th>
                    <th>Laboratório</th>
                    <th>Computadores</th>
                    <th>Disponibilidade</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

                <?php while ($laboratorio = $resultadoLaboratorios->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $laboratorio['cd_laboratorio']; ?>
                        </td>

                        <td>
                            <?php echo $laboratorio['nm_laboratorio']; ?>
                        </td>

                        <td>
                            <?php echo $laboratorio['ds_quantidade_computadores_laboratorio']; ?>
                        </td>

                        <td>

                            <?php if ($laboratorio['ds_disponibilidade_laboratorio'] == 1) { ?>

                                <span class="badge text-bg-success">
                                    Disponível
                                </span>

                            <?php } else { ?>

                                <span class="badge text-bg-danger">
                                    Indisponível
                                </span>

                            <?php } ?>

                        </td>

                        <td>

                            <a href="editLab.php?cd=<?php echo $laboratorio['cd_laboratorio']; ?>"
                                class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <a href="excluirLab.php?cd=<?php echo $laboratorio['cd_laboratorio']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir este laboratório?');">
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