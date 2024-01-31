<?php
require_once(__DIR__ . "/../include/db.php");
require_once(__DIR__ . "/../include/ClientDAO.php");
require_once(__DIR__ . "/../include/response.php");

function main() : void
{
    if($_SERVER["REQUEST_METHOD"] !== "GET")
    {
        $reponse = ["error" => "Utilize o método de requisição GET"];
        setJsonResponse(400, $reponse);
        exit();
    }

    $conn = createDatabaseConnection();
    $clientDAO = new ClientDAO($conn);

    $response = $clientDAO->getAll();

    setJsonResponse(200, $response);
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
