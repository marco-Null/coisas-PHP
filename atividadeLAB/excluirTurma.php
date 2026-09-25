<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    // Verifica se a turma possui reservas
    $sqlReservas = "SELECT cd_reserva
                    FROM reservas
                    WHERE id_turma = ?";

    $stmtReservas = $conexao->prepare($sqlReservas);
    $stmtReservas->bind_param("i", $cd);
    $stmtReservas->execute();

    $resultado = $stmtReservas->get_result();

    if ($resultado->num_rows > 0) {

        header("Location: TURMS.php?erro=reservas");
        exit();

    }

    $sql = "DELETE FROM turmas
            WHERE cd_turma = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);

    if ($stmt->execute()) {

        header("Location: TURMS.php?sucesso=excluido");
        exit();

    } else {

        echo "Erro ao excluir turma: " . $stmt->error;

    }

} else {

    echo "Código da turma não informado.";

}

?>