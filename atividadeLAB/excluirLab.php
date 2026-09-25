<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sqlReservas = "SELECT cd_reserva 
                    FROM reservas 
                    WHERE id_laboratorio = ?";

    $stmtReservas = $conexao->prepare($sqlReservas);
    $stmtReservas->bind_param("i", $cd);
    $stmtReservas->execute();

    $resultado = $stmtReservas->get_result();

    if ($resultado->num_rows > 0) {

        header("Location: labs.php?erro=reservas");
        exit();

    }

    $sql = "DELETE FROM laboratorios WHERE cd_laboratorio = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);

    if ($stmt->execute()) {

        header("Location: labs.php?sucesso=excluido");
        exit();

    } else {

        echo "Erro ao excluir laboratório: " . $stmt->error;

    }

} else {

    echo "Código do laboratório não informado.";

}

?>