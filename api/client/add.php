<?php
require_once(__DIR__ . "/../include/db.php");
require_once(__DIR__ . "/../include/CurrencyDAO.php");
require_once(__DIR__ . "/../include/ClientDAO.php");
require_once(__DIR__ . "/../include/response.php");

function validateData(?string $code, ?string $name, ?int $currencyCode)
{
    if($code === null)
    {
        throw new Exception("O código do cliente não está definido");
    }

    if($name === null)
    {
        throw new Exception("A razão social do cliente não está definida");
    }

    if($currencyCode === null)
    {
        throw new Exception("A moeda não está definida");
    }
}

function main() : void
{
    if($_SERVER["REQUEST_METHOD"] !== "POST")
    {
        $reponse = ["error" => "Utilize o método de requisição POST"];
        setJsonResponse(400, $reponse);
        exit();
    }

    $conn = createDatabaseConnection();
    $currencyDAO = new CurrencyDAO($conn);
    $clientDAO = new ClientDAO($conn);

    try
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        $code = $input['code'];
        $name = $input['clientName'];
        $currencyCode = $input['currencyCode'];
    
        validateData($code, $name, $currencyCode);

        $clientDAO->addClient($currencyDAO, $code, $name, $currencyCode);
    }
    catch (\Throwable $th)
    {
        $reponse = ["error" => $th->getMessage()];
        setJsonResponse(400, $reponse);
        exit();
    }

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