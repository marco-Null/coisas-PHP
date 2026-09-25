<?php
session_start();
include('inc/conexao.php');

$mensagem = "";

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}

if (isset($_GET['sucesso'])) {
    $mensagem = "<div class='AlertaCadastro alert alert-success' role='alert'>
                    Cadastro feito!
                </div>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeLab = $_POST['nm_laboratorio'];
    $quantidadePcLab = $_POST['ds_quantidade_computadores_laboratorio'];
    $disponibilidadeLab = $_POST['ds_disponibilidade_laboratorio'];

    if (empty($nomeLab)) {
        $mensagem = "<div class='alert alert-danger'>
                        Digite o nome do Laboratorio!
                    </div>";
    } 
    
    elseif (empty($quantidadePcLab)) {
        $mensagem = "<div class='alert alert-danger'>
                        Digite a quantidade de computadores do Laboratorio!
                    </div>";
    } 
    
    elseif ($disponibilidadeLab == "") {
        $mensagem = "<div class='alert alert-danger'>
                        Selecione a disponibilidade do Laboratorio!
                    </div>";
    } 
    
    else {

        $sqlLaboratorio = "INSERT INTO laboratorios 
        (nm_laboratorio, ds_quantidade_computadores_laboratorio, ds_disponibilidade_laboratorio) 
        VALUES (?, ?, ?)";

        $stmtLaboratorio = $conexao->prepare($sqlLaboratorio);

        $stmtLaboratorio->bind_param(
            "sii",
            $nomeLab,
            $quantidadePcLab,
            $disponibilidadeLab
        );

        $stmtLaboratorio->execute();

        $_SESSION['mensagem'] = "<div class='AlertaCadastro alert alert-success' role='alert'>
                            Cadastro feito com sucesso!
                         </div>";

            header("Location: CadasLaboratorio.php");
            exit;
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorio cadastro</title>

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

        <h1 style="text-align: center;">Cadastro de Laboratorio</h1>
        <hr class="linha"><br>

        <form method="post">
            <label class="form-label">
                Nome do Laboratorio:
            </label>

            <input class="form-control" type="text" placeholder="Nome do Laboratorio" name="nm_laboratorio"
                required><br>

            <label class="form-label">
                Quantos Computadores possui:
            </label>

            <input class="PcNumber form-control" type="number" placeholder="numero Computadores"
                name="ds_quantidade_computadores_laboratorio" max="20" min="1" required><br>

            <label for="" class="nomeDisponivel">Disponivel: </label>

            <select class="seletorDispo form-select" name="ds_disponibilidade_laboratorio" required>

                <option value="1" selected>Sim</option>
                <option value="2">Não</option>

            </select>

            <br>

            <button type="submit" class="botaoCadastre btn btn-dark">Cadastrar</button>

        </form>


        <?php echo "<br>" . $mensagem ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>