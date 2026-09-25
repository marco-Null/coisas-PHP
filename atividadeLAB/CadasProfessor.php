<?php
session_start();
include('inc/conexao.php');

$mensagem = "";

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomeProfessor = $_POST['nm_professor'];
    $materias = $_POST['materias'] ?? [];

    if (empty($nomeProfessor)) {

        echo "Digite o nome do professor.";

    } elseif (empty($materias)) {

        echo "Selecione pelo menos uma matéria.";

    } else {

        $sqlProfessor = "INSERT INTO professores (nm_professor) VALUES (?)";

        $stmtProfessor = $conexao->prepare($sqlProfessor);
        $stmtProfessor->bind_param(
            "s",
            $nomeProfessor
        );

        $stmtProfessor->execute();
        $idProfessor = $conexao->insert_id;


        $sqlMateria = "INSERT INTO materias (nm_materia) VALUES (?)";
        $stmtMateria = $conexao->prepare($sqlMateria);


        $sqlRelacionamento = "INSERT INTO professores_materias (id_professor, id_materia) VALUES (?, ?)";
        $stmtRelacionamento = $conexao->prepare($sqlRelacionamento);

        foreach ($materias as $materia) {

            $stmtMateria->bind_param(
                "s",
                $materia
            );

            $stmtMateria->execute();
            $idMateria = $conexao->insert_id;

            $stmtRelacionamento->bind_param(
                "ii",
                $idProfessor,
                $idMateria
            );
        }
        
            $stmtRelacionamento->execute();

        $_SESSION['mensagem'] = "<div class='AlertaCadastro alert alert-success' role='alert'>
                            Cadastro feita com sucesso!
                         </div>";

            header("Location: CadasProfessor.php");
            exit;

    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Professores Cadastro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .body {
            background-color: black;
            color: white;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .divDasCaixas {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            color: black;
            width: 450px;
            margin: 10px auto;
        }

        .inputNomeProf {
            width: 600px;
            background-color: white;
            color: black;
            border-radius: 5px;
            padding: 10px;
            margin: 0 auto;
        }

        .linha {
            color: white;
            border: 3px solid white;
            opacity: 1;
            width: 100%;
            opacity: 1;
        }

        .botaoCadastre {
            width: 50%;
            display: block;
            margin: 0 auto;
        }

        .AlertaCadastro {
            text-align: center;
            color: black;
            width: 25%;
            margin: 20px auto;
        }

        .divItensNavBar {
            margin-left: 65%;
        }

        .teste {
            opacity: 1;
        }
    </style>

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

    <div class="container mt-5">


        <form method="post">

            <div class="inputNomeProf mb-3">

                <h1 style="text-align: center;">Cadastro de Professor</h1>
                <hr class="linha"><hr>

                <label class="form-label">
                    Nome:
                </label>

                <input class="form-control" type="text" placeholder="Nome do professor" name="nm_professor"
                    required><br>

                <label class="form-label">
                    E-mail:
                </label>
                <input class="form-control" type="email" placeholder="Email do professor" name="ds_email_professor"
                    required>

            </div>

            <div class="divDasCaixas">
                <h3>
                    Coloque suas matérias de aulas
                </h3>


                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Banco de dados" id="bancoDeDados"
                        name="materias[]">

                    <label class="form-check-label" for="bancoDeDados">
                        Banco de dados
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Programação web" id="programacaoWEB"
                        name="materias[]">

                    <label class="form-check-label" for="programacaoWEB">
                        Programação web
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Programação Mobile" id="programacaoMOBILE"
                        name="materias[]">

                    <label class="form-check-label" for="programacaoMOBILE">
                        Programação Mobile
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Teste de programação e algoritmo"
                        id="programacaoTPA" name="materias[]">

                    <label class="form-check-label" for="programacaoTPA">
                        Teste de programação e algoritmo
                    </label>

                </div>

                <hr style="opacity: 1;">

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Matemática" id="matematica"
                        name="materias[]">

                    <label class="form-check-label" for="matematica">
                        Matemática
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Português" id="portugues" name="materias[]">

                    <label class="form-check-label" for="portugues">
                        Português
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Química" id="quimica" name="materias[]">

                    <label class="form-check-label" for="quimica">
                        Química
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="História" id="historia" name="materias[]">

                    <label class="form-check-label" for="historia">
                        História
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Inglês" id="ingles" name="materias[]">

                    <label class="form-check-label" for="ingles">
                        Inglês
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Biologia" id="biologia" name="materias[]">

                    <label class="form-check-label" for="biologia">
                        Biologia
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Geografia" id="geografia" name="materias[]">

                    <label class="form-check-label" for="geografia">
                        Geografia
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Física" id="fisica" name="materias[]">

                    <label class="form-check-label" for="fisica">
                        Física
                    </label>

                </div>

                <div class="form-check">

                    <input class="form-check-input" type="checkbox" value="Educação fisica" id="educacaoFisica"
                        name="materias[]">

                    <label class="form-check-label" for="educacaoFisica">
                        Educação Fisica
                    </label>

                </div>


                <br>

                <button type="submit" class="botaoCadastre btn btn-success">Cadastrar</button>

            </div>
        </form>

        <?php echo $mensagem ?>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>