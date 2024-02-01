<?php
require_once(__DIR__ . "/../include/db.php");
require_once(__DIR__ . "/../include/CurrencyDAO.php");
require_once(__DIR__ . "/../include/ClientDAO.php");
require_once(__DIR__ . "/../include/response.php");

function validateData(?string $oldCode) : void
{
    // Apenas o código antigo do cliente é obrigatório, se os outros valores forem nulos eles
    // não serão alterados
    if($oldCode === null)
    {
        throw new Exception("O código do cliente não está definido");
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

        $oldCode = $input["oldCode"];
        $newCode = $input["newCode"];
        $newCode = ($newCode === $oldCode) ? null : $newCode;
        $newName = $input["clientName"];
        $newCurrencyCode = $input["currencyCode"];
        $newCurrencyCode = ($newCurrencyCode === "") ? null : strval($newCurrencyCode);
        $newLastSaleDate = $input["lastSaleDate"];
        $newLastSaleDate = ($newLastSaleDate === "") ? null : $newLastSaleDate;
        $newTotalSales = $input["totalSales"];
        $newTotalSales = ($newTotalSales === "") ? null : strval($newTotalSales);

        validateData($oldCode);

        $clientDAO->updateClient($currencyDAO, $oldCode, $newCode, $newName, $newCurrencyCode, $newLastSaleDate, $newTotalSales);
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
    setJsonResponse(500, null);
}
?>
