<?php

include('INC/conexao.php');

$SQLalunos = "SELECT * FROM tb_alunos";

$SQLprofessores = "SELECT * FROM tb_professores";

$SQLdiciplinas = "SELECT 
                    d.nm_diciplina, 
                    GROUP_CONCAT(p.nm_professor SEPARATOR ', ') AS professores
                  FROM tb_diciplinas d 
                  INNER JOIN tb_professores_diciplinas dp 
                    ON d.cd_diciplina = dp.cd_diciplina
                  INNER JOIN tb_professores p 
                    ON dp.cd_professor = p.cd_professor 
                  GROUP BY d.cd_diciplina, d.nm_diciplina";

$resultadoAlunos = $conexao->query($SQLalunos);
$resultadoProfessores = $conexao->query($SQLprofessores);
$resultadoDiciplinas = $conexao->query($SQLdiciplinas);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background: linear-gradient(black, #363636, black);
        }

        .tabelas {
            position: relative;
            height: 500px;
            perspective: 1200px;
            margin-top: 50px;
        }

        .tabela {
            position: absolute;
            width: 100%;

            transition:
                transform 0.8s ease,
                opacity 0.8s ease,
                filter 0.8s ease;

            transform-style: preserve-3d;
        }

        .frente {
            transform: translateX(0) translateZ(100px) rotateY(0deg);
            opacity: 1;
            filter: brightness(1);
            z-index: 3;
        }

        .direita {
            transform: translateX(35%) translateZ(-150px) rotateY(-35deg) scale(0.85);
            opacity: 0.55;
            filter: brightness(0.35);
            z-index: 2;
        }

        .esquerda {
            transform: translateX(-35%) translateZ(-150px) rotateY(35deg) scale(0.85);
            opacity: 0.55;
            filter: brightness(0.35);
            z-index: 1;
        }

        .botao {
            margin-top: 20px;
            padding: 10px 30px;
            font-size: 18px;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-sm-8">

                <div class="tabelas">


                    <div class="tabela frente">

                        <table class="table table-dark table-hover">

                            <tr>
                                <th colspan="4" class="text-center">
                                    ALUNOS
                                </th>
                            </tr>

                            <tr>
                                <th>Nomes</th>
                                <th>RM</th>
                                <th>E-Mail</th>
                                <th>Série</th>
                            </tr>

                            <?php

                            if ($resultadoAlunos->num_rows > 0) {

                                while ($aluno = $resultadoAlunos->fetch_assoc()) {

                                    echo "<tr>";

                                    echo "<td>" . $aluno['nm_aluno'] . "</td>";
                                    echo "<td>" . $aluno['ds_matricula_aluno'] . "</td>";
                                    echo "<td>" . $aluno['ds_email_aluno'] . "</td>";
                                    echo "<td>" . $aluno['ds_serie_aluno'] . "</td>";

                                    echo "</tr>";
                                }
                            }
                            ?>

                        </table>

                    </div>


                    <div class="tabela direita">

                        <table class="table table-info table-striped-columns">

                            <tr>
                                <th colspan="2" class="text-center">
                                    DISCIPLINAS
                                </th>
                            </tr>

                            <tr>
                                <th>Nomes</th>
                                <th>Professores Responsáveis</th>
                            </tr>

                            <?php

                            if ($resultadoDiciplinas->num_rows > 0) {

                                while ($diciplina = $resultadoDiciplinas->fetch_assoc()) {

                                    echo "<tr>";

                                    echo "<td>" . $diciplina['nm_diciplina'] . "</td>";
                                    echo "<td>" . $diciplina['professores'] . "</td>";

                                    echo "</tr>";
                                }
                            }
                            ?>

                        </table>

                    </div>


                    <div class="tabela esquerda">

                        <table class="table table-warning table-hover">

                            <tr>
                                <th colspan="3" class="text-center">
                                    PROFESSORES
                                </th>
                            </tr>

                            <tr>
                                <th>Nome</th>
                                <th>E-Mail</th>
                                <th>Telefone</th>
                            </tr>

                            <?php

                            if ($resultadoProfessores->num_rows > 0) {

                                while ($professor = $resultadoProfessores->fetch_assoc()) {

                                    echo "<tr>";

                                    echo "<td>" . $professor['nm_professor'] . "</td>";
                                    echo "<td>" . $professor['ds_email_professor'] . "</td>";
                                    echo "<td>" . $professor['ds_telefone_professor'] . "</td>";

                                    echo "</tr>";
                                }
                            }
                            ?>

                        </table>

                    </div>

                </div>


                <div class="text-center">

                    <button type="button" class="btn btn-light botao" onclick="girarTabelas()">

                        Próxima tabela

                    </button>

                </div>

            </div>

        </div>

    </div>


    <script>

        let atual = 0;

        function girarTabelas() {

            const tabelas = document.querySelectorAll(".tabela");

            tabelas[atual].classList.remove("frente");
            tabelas[atual].classList.add("esquerda");

            let proxima = (atual + 1) % tabelas.length;

            tabelas[proxima].classList.remove("direita");
            tabelas[proxima].classList.remove("esquerda");
            tabelas[proxima].classList.add("frente");

            let terceira = (atual + 2) % tabelas.length;

            tabelas[terceira].classList.remove("esquerda");
            tabelas[terceira].classList.add("direita");

            atual = proxima;
        }

    </script>

</body>

</html>