<?php
session_start();
include('inc/conexao.php');

$mensagem = "";

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeTurma = $_POST['nm_turma'];
    $quantidadeAlunosTurma = $_POST['ds_quantidade_alunos_turma'];
    $cursoTurma = $_POST['ds_curso_turma'];

    if (empty($nomeTurma)) {
        $mensagem = "<div class='alert alert-danger'>
                        Digite o nome da Turma!
                    </div>";
    } elseif (empty($quantidadeAlunosTurma)) {
        $mensagem = "<div class='alert alert-danger'>
                        Digite a quantidade de Alunos da Turma!
                    </div>";
    } else {

        $sqlTurma = "INSERT INTO turmas (nm_turma, ds_quantidade_alunos_turma, ds_curso_turma) VALUES (?, ?, ?)";

        $stmtTurma = $conexao->prepare($sqlTurma);

        $stmtTurma->bind_param(
            "sis",
            $nomeTurma,
            $quantidadeAlunosTurma,
            $cursoTurma
        );

        $stmtTurma->execute();

            $_SESSION['mensagem'] = "<div class='AlertaCadastro alert alert-success' role='alert'>
                            Cadastro feito com sucesso!
                         </div>";

            header("Location: CadasTurma.php");
            exit;
    }

}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turma cadastro</title>

    <style>
        .body {
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            background-color: black;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .AlertaCadastro {
            text-align: center;
        }

        .divCadastroLab {
            width: 50%;
            background-color: white;
            color: black;
            justify-self: center;
            margin-top: 100px;
            padding: 15px;
            border-radius: 5px;
        }

        .PcNumber {
            width: 210px !important;
        }

        .seletorDispo {
            width: 270px !important;
            position: absolute;
            bottom: 41.6%;
            right: 41%;
        }

        .nomeDisponivel {
            position: absolute;
            bottom: 47.7%;
            left: 42%;
        }


        .divItensNavBar {
            margin-left: 65%;
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

    <div class="divCadastroLab">

        <h1 style="text-align: center;">Cadastro de Turmas</h1>
        <hr class="linha"><br>

        <form method="post">
            <label class="form-label">
                Nome da Turma:
            </label>

            <input class="form-control" type="text" placeholder="Nome da turma" name="nm_turma" required><br>

            <label class="form-label">
                Quantos Alunos possui a Turma:
            </label>

            <input class="PcNumber form-control" type="number" max="50" placeholder="numero alunos"
                name="ds_quantidade_alunos_turma" required><br>

            <br>

            <label for="" class="nomeDisponivel">Curso da Turma: </label>

            <select class="seletorDispo form-select" name="ds_curso_turma" required>

                <option value="Não Possui" selected>Não Possui</option>
                <option value="Desenvolvimento de sistemas">Desenvolvimento de sistemas</option>
                <option value="Informatica">Informatica</option>
                <option value="Farmácia">Farmácia</option>
                <option value="Administração">Administração</option>

            </select>

            <button type="submit" class="botaoCadastre btn btn-dark">Cadastrar</button>

        </form>


        <?php echo "<br>" . $mensagem ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>