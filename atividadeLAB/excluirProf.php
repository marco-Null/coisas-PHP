<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sqlReservas = "SELECT cd_reserva
                    FROM reservas
                    WHERE id_professor = ?";

    $stmtReservas = $conexao->prepare($sqlReservas);
    $stmtReservas->bind_param("i", $cd);
    $stmtReservas->execute();

    $resultado = $stmtReservas->get_result();

    if ($resultado->num_rows > 0) {

        header("Location: PROFS.php?erro=reservas");
        exit();

    }

    $sqlMaterias = "DELETE FROM professores_materias
                    WHERE id_professor = ?";

    $stmtMaterias = $conexao->prepare($sqlMaterias);
    $stmtMaterias->bind_param("i", $cd);
    $stmtMaterias->execute();

    $sql = "DELETE FROM professores
            WHERE cd_professor = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);

    if ($stmt->execute()) {

        header("Location: PROFS.php?sucesso=excluido");
        exit();

    } else {

        echo "Erro ao excluir professor: " . $stmt->error;

    }

} else {

    echo "Código do professor não informado.";

}

?>