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
        $oldCode = filter_input(INPUT_POST, "oldCode");
        $newCode = filter_input(INPUT_POST, "newCode");
        $newName = filter_input(INPUT_POST, "clientName");
        $newCurrencyCode = filter_input(INPUT_POST, "currencyCode", FILTER_VALIDATE_INT);
        $newLastSaleDate = filter_input(INPUT_POST, "lastSaleDate");
        $newTotalSales = filter_input(INPUT_POST, "totalSales", FILTER_VALIDATE_FLOAT);

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
