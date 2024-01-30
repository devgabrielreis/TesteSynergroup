<?php
function createDatabaseConnection() : PDO
{
    $dbName = getenv("CADASTRO_CLIENTES_DB_NAME");
    $dbHost = getenv("CADASTRO_CLIENTES_DB_HOST");
    $dbUser = getenv("CADASTRO_CLIENTES_DB_USER");
    $dbPass = getenv("CADASTRO_CLIENTES_DB_PASS");

    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $conn;
}
?>
