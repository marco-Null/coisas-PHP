<?php


session_start();

if(isset($_SESSION['login']) && isset($_SESSION['senha'])){
    echo "acesso liberado";
}
else {
    echo "acesso negado";
}


?>