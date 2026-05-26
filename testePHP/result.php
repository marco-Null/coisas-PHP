<?php
    session_start();

    $EmailCerto = "Marco@gmail.com";
    $SenhaCerta = "147569";


    if(isset($_POST['EmailCadas'], $_POST['SenhaCadas']) && 
        $_POST['EmailCadas'] == $EmailCerto && 
        $_POST['SenhaCadas'] == $SenhaCerta){

        $_SESSION['Email'] = true;
        $_SESSION['Senha'] = true;

        echo "acesso liberado";
    }
    else{
        $_SESSION['Email'] = false;
        $_SESSION['Senha'] = false;

        echo "acesso negado";
    }
    

?>

