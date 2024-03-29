<?php
require_once(__DIR__ . "/../include/db.php");
require_once(__DIR__ . "/../include/ClientDAO.php");
require_once(__DIR__ . "/../include/response.php");

function validateData(array $_DELETE) : void
{
    if(!isset($_DELETE["code"]))
    {
        throw new Exception("O código do cliente não está definido");
    }
}

function main() : void
{
    if($_SERVER["REQUEST_METHOD"] !== "DELETE")
    {
        $reponse = ["error" => "Utilize o método de requisição DELETE"];
        setJsonResponse(400, $reponse);
        exit();
    }

    $inputJSON = file_get_contents('php://input');
    $_DELETE = json_decode($inputJSON, TRUE);
    print_r($_DELETE);

    try
    {
        validateData($_DELETE);
        $clientCode = $_DELETE["code"];
    }
    catch (\Throwable $th)
    {
        $reponse = ["error" => $th->getMessage()];
        setJsonResponse(400, $reponse);
        exit();
    }

    $conn = createDatabaseConnection();
    $clientDAO = new ClientDAO($conn);

    $clientToRemove = $clientDAO->getClient($clientCode);

    if($clientToRemove === null)
    {
        $reponse = ["error" => "Não existe nenhum cliente com esta razão social"];
        setJsonResponse(400, $reponse);
        exit();
    }

    $clientDAO->removeClient($clientToRemove);

    setJsonResponse(200, null);
}

try
{
    main();
}
catch (\Throwable $th)
{
    setJsonResponse(500, ["error" => "Erro interno, tente mais tarde"]);
}
?>
