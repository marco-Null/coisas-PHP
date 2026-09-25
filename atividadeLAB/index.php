<!DOCTYPE html>
<html lang="pr-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecLabs</title>

    <style>
        .body {
            background-image: linear-gradient(to top, white, black);
            width: 100%;
            height: 5000px;
        }

        .navbar {
            width: 100%;
        }

        .divItensNavBar {
            margin-left: 65%;
        }

        .textoEstampa {
            font-weight: bold;
            font-size: 200px;
            color: white;
            text-align: center;
            font-family: Verdana, Geneva, Tahoma, sans-serif;

            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -80%);
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

    <h1 class="textoEstampa">TecLAB's</h1>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>