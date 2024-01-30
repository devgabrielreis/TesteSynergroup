<?php
function setJsonResponse(int $statusCode, object|array|null $data) : void
{
    header('Content-Type: application/json');
    http_response_code($statusCode);

    if($data !== null)
    {
        echo json_encode($data);
    }
}
?>
