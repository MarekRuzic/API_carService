<?php
// Aktuální verze na web hostingu
require __DIR__ . "/inc/bootstrap.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode( '/', $uri );

// Heslo vymazané z důvodu bezpečnosti
$password = "*****";

if (!isset($_SERVER['HTTP_KEYAPI']) || !password_verify($password, $_SERVER['HTTP_KEYAPI'])) 
{
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(array("message" => "Neplatný API klíč"));
    exit();
} 

if ((isset($uri[3]) && ($uri[3] != 'user' &&  $uri[3] != 'car' && $uri[3] != 'repair')) || !isset($uri[4])) {

    header("HTTP/1.1 404 Not Found XD");

    exit();

}

$objFeedController = null;

switch ($uri[3])
{
    case 'user': 
    {
        require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php"; 
        $objFeedController = new UserController();
        break;
    }
    case 'car': 
    {
        require PROJECT_ROOT_PATH . "/Controller/Api/CarController.php"; 
        $objFeedController = new CarController();
        break;
    }
    case 'repair': 
    {
        require PROJECT_ROOT_PATH . "/Controller/Api/RepairController.php"; 
        $objFeedController = new RepairController();
        break;
    }
    default: header("HTTP/1.1 404 Not Found Controller"); exit();
}

$strMethodName = $uri[4] . 'Action';

$objFeedController->{$strMethodName}();

?>
