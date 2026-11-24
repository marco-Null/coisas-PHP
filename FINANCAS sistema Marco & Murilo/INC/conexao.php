<?php

$host = "aws-0-us-east-1.pooler.supabase.com";
$port = "5432";
$dbname = "postgres";
$user = "postgres.oxrcdzpbdtlndtvppmzm";
$password = "apidegerenciamentofinanceiro";

try {

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // echo "Conectado ao Supabase com sucesso!";

} catch (PDOException $e) {

    die("Erro na conexão: " . $e->getMessage());

}
?>