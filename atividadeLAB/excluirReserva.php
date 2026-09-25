<?php

include('inc/conexao.php');

if (isset($_GET['cd'])) {

    $cd = $_GET['cd'];

    $sql = "DELETE FROM reservas
            WHERE cd_reserva = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cd);

    if ($stmt->execute()) {

        header("Location: RESERVS.php?sucesso=excluido");
        exit();

    } else {

        echo "Erro ao excluir reserva: " . $stmt->error;

    }

} else {

    echo "Código da reserva não informado.";

}

?>