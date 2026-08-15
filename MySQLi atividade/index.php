<?php

include('inc/conexcao.php');

$sql = "SELECT * FROM aluno";

$resultado = $conexao -> query($sql);

if($resultado->num_rows>0){
    while($aluno = $resultado->fetch_assoc()){
        echo "<br>Nome: " . $aluno['nm_aluno'];
    }
}

else{
    "nenhum aluno encontrado!";
}

?>