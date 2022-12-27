<?php
ini_set('memory_limit', '2048M');
require '../vendor/autoload.php';
require_once "src/configurations.php";
require_once "src/S3Wrapper.php";
require_once "src/CardController.php";


function validateUri($uriParts): void
{
    // all of our endpoints start with /card as only actions related to cards are available at this moment
    if ($uriParts[1] !== 'card') {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}

function getInfoFromRequest($uriParts): array
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    $cardId = null;
    if (isset($uriParts[2])) {
        $cardId = $uriParts[2];
    }

    $input = file_get_contents('php://input');
    $propertiesToUpdate = null;
    if (isset($input)) {
        $propertiesToUpdate = (array) json_decode($input, true);
    }

    return ['requestMethod' => $requestMethod, 'cardId' => $cardId, 'propertiesToUpdate' => $propertiesToUpdate];
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriParts = explode('/', $uri);

$requestTnfo = getInfoFromRequest($uriParts);

$s3Wrapper = new S3Wrapper($endpoint, $creadentials, $region);
$sourceMetadata = ['bucketName' => $bucketName, 'keyName' => $keyName];
$controller = new CardController($s3Wrapper, $sourceMetadata);

$response = $controller->processRequest($requestTnfo['requestMethod'], $requestTnfo['cardId'], $requestTnfo['propertiesToUpdate']);
header($response['statusCodeHeader']);
if ($response['body']) {
    echo $response['body'];
}
?>