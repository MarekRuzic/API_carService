<?php
class CarController extends BaseController
{
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {

            try {
                $carModel = new CarModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {

                    $intLimit = $arrQueryStringParams['limit'];
                }

                $arrUsers = $carModel->getCars($intLimit);
                $responseData = json_encode($arrUsers);
            } 
            catch (Error $e)
            {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } 
        else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output

        if (!$strErrorDesc) {

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } 
        else 
        {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function listcaruserAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') 
        {
            try
            {
                $carModel = new CarModel();
                $user_id = -1;
                if (isset($_GET['id']) && $_GET['id']) {

                    $user_id = $_GET['id'];
                }
                else
                {
                    throw new Exception('Nebylo nalezeno id');
                }

                $arrUsers = $carModel->getCarsUser($user_id);
                $responseData = json_encode($arrUsers);
            } 
            catch (Error $e) 
            {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } 
        else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output

        if (!$strErrorDesc) 
        {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } 
        else 
        {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function getuserAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') 
        {
            try 
            {
                if (isset($_GET['id']))
                {
                    $id = isset($_GET['id']) ? $_GET['id'] : null;
                    if (!$id) {
                        throw new Exception('Nebylo nalezeno ID.');
                    } 
                    
                    $id = htmlspecialchars(trim($id), ENT_QUOTES, "UTF-8");

                    $userModel = new UserModel();
                    $arrUsers = $userModel->getUser($id);
                    $responseData = json_encode($arrUsers);
                }
                else 
                {
                    $email = isset($_GET['email']) ? $_GET['email'] : null;
                    if (!$email) {
                        throw new Exception('Nebyl nalezen email.');
                    }   

                    $email = htmlspecialchars(trim($email), ENT_QUOTES, "UTF-8");
                    $userModel = new UserModel();
                    $arrUsers = $userModel->getUserByEmail($email);
                    $responseData = json_encode($arrUsers);
                }
            } 
            catch (Error $e) 
            {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }

        } 
        else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

    }

    public function createAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $data = file_get_contents('php://input');
        $arrPostData = json_decode($data);

        if (strtoupper($requestMethod) == 'POST') 
        {
            try 
            {
                $carModel = new CarModel();
                if (
                    isset($arrPostData->brand) &&
                    isset($arrPostData->model) &&
                    isset($arrPostData->manufacture) &&
                    isset($arrPostData->mileage) &&
                    isset($arrPostData->fuel) &&
                    isset($arrPostData->body) &&
                    //isset($arrPostData->color) &&
                    //isset($arrPostData->drive4x4) &&
                    isset($arrPostData->doors) &&
                    isset($arrPostData->seats) &&
                    //isset($arrPostData->aircondition) &&
                    isset($arrPostData->vin) &&
                    isset($arrPostData->spz) &&
                    //isset($arrPostData->nickname) &&
                    isset($arrPostData->name_engine) &&
                    isset($arrPostData->power) &&
                    isset($arrPostData->transmition) &&
                    isset($arrPostData->user_id)
                ) {                    
                    $brand = htmlspecialchars(trim($arrPostData->brand), ENT_QUOTES, "UTF-8");
                    $model = htmlspecialchars(trim($arrPostData->model), ENT_QUOTES, "UTF-8");
                    $manufacture = htmlspecialchars(trim($arrPostData->manufacture), ENT_QUOTES, "UTF-8");
                    $mileage = htmlspecialchars(trim($arrPostData->mileage), ENT_QUOTES, "UTF-8");
                    $fuel = htmlspecialchars(trim($arrPostData->fuel), ENT_QUOTES, "UTF-8");
                    $body = htmlspecialchars(trim($arrPostData->body), ENT_QUOTES, "UTF-8");
                    $color = htmlspecialchars(trim($arrPostData->color), ENT_QUOTES, "UTF-8");
                    $drive4x4 = htmlspecialchars(trim($arrPostData->drive4x4), ENT_QUOTES, "UTF-8");
                    $doors = htmlspecialchars(trim($arrPostData->doors), ENT_QUOTES, "UTF-8");
                    $seats = htmlspecialchars(trim($arrPostData->seats), ENT_QUOTES, "UTF-8");
                    $aircondition = htmlspecialchars(trim($arrPostData->aircondition), ENT_QUOTES, "UTF-8");
                    $vin = htmlspecialchars(trim($arrPostData->vin), ENT_QUOTES, "UTF-8");
                    $spz = htmlspecialchars(trim($arrPostData->spz), ENT_QUOTES, "UTF-8");
                    $nickname = htmlspecialchars(trim($arrPostData->nickname), ENT_QUOTES, "UTF-8");
                    $name_engine = htmlspecialchars(trim($arrPostData->name_engine), ENT_QUOTES, "UTF-8");
                    $code = htmlspecialchars(trim($arrPostData->code), ENT_QUOTES, "UTF-8");
                    $displacement = htmlspecialchars(trim($arrPostData->displacement), ENT_QUOTES, "UTF-8");
                    $power = htmlspecialchars(trim($arrPostData->power), ENT_QUOTES, "UTF-8");
                    $torque = htmlspecialchars(trim($arrPostData->torque), ENT_QUOTES, "UTF-8");
                    $oil_capacity = htmlspecialchars(trim($arrPostData->oil_capacity), ENT_QUOTES, "UTF-8");
                    $transmition = htmlspecialchars(trim($arrPostData->transmition), ENT_QUOTES, "UTF-8");
                    $user_id = htmlspecialchars(trim($arrPostData->user_id), ENT_QUOTES, "UTF-8");

                    // Přidání nového vozidla
                    $carModel->createCar($brand, $model, $manufacture, $mileage, $fuel, 
                        $body, $color, $drive4x4, $doors, $seats, $aircondition, $vin, $spz, 
                        $nickname, $name_engine, $code, $displacement, $power, $torque, $oil_capacity, 
                        $transmition, $user_id);

                    $responseData = json_encode(array('status' => 'success', 'message' => 'Auto bylo úspěšně vytvořeno.'));
                } 
                else 
                {
                    throw new Exception('Chybějící povinná data.');
                }
            } 
            catch (Exception $e) 
            {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error - auto ti nefunguje šašku fakt, že jo';
            }
        } 
        else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }        

        // send output

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } 
        else 
        {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function deleteAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'DELETE') {
            try {
                $idToDelete = isset($_GET['id']) ? $_GET['id'] : null;            
                if (!$idToDelete) {
                    throw new Exception('Chybějící ID vozidla k smazání.');
                }
            
                $carModel = new CarModel();            
                $idToDelete = htmlspecialchars(trim($idToDelete), ENT_QUOTES, "UTF-8");            
                // Smazání vozidla
                $carModel->deleteCar($idToDelete);
            
                $responseData = json_encode(array('status' => 'success', 'message' => 'Vozidlo bylo úspěšně smazáno'));
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error' . $e->getMessage();
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
    
        // Odeslání odpovědi
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function updateAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $data = file_get_contents('php://input');
        $arrPostData = json_decode($data);

        if (strtoupper($requestMethod) == 'PUT') 
        {
            try 
            {
                $carModel = new carModel();
                if (
                    isset($arrPostData->id) &&
                    isset($arrPostData->brand) &&
                    isset($arrPostData->model) &&
                    isset($arrPostData->manufacture) &&
                    isset($arrPostData->mileage) &&
                    isset($arrPostData->fuel) &&
                    isset($arrPostData->body) &&
                    //isset($arrPostData->color) &&
                    //isset($arrPostData->drive4x4) &&
                    isset($arrPostData->doors) &&
                    isset($arrPostData->seats) &&
                    //isset($arrPostData->aircondition) &&
                    isset($arrPostData->vin) &&
                    isset($arrPostData->spz) &&
                    //isset($arrPostData->nickname) &&
                    isset($arrPostData->name_engine) &&
                    isset($arrPostData->power) &&
                    isset($arrPostData->transmition) &&
                    isset($arrPostData->user_id)
                ) {
                    $id = htmlspecialchars(trim($arrPostData->id), ENT_QUOTES, "UTF-8");
                    $brand = htmlspecialchars(trim($arrPostData->brand), ENT_QUOTES, "UTF-8");
                    $model = htmlspecialchars(trim($arrPostData->model), ENT_QUOTES, "UTF-8");
                    $manufacture = htmlspecialchars(trim($arrPostData->manufacture), ENT_QUOTES, "UTF-8");
                    $mileage = htmlspecialchars(trim($arrPostData->mileage), ENT_QUOTES, "UTF-8");
                    $fuel = htmlspecialchars(trim($arrPostData->fuel), ENT_QUOTES, "UTF-8");
                    $body = htmlspecialchars(trim($arrPostData->body), ENT_QUOTES, "UTF-8");
                    $color = htmlspecialchars(trim($arrPostData->color), ENT_QUOTES, "UTF-8");
                    $drive4x4 = htmlspecialchars(trim($arrPostData->drive4x4), ENT_QUOTES, "UTF-8");
                    $doors = htmlspecialchars(trim($arrPostData->doors), ENT_QUOTES, "UTF-8");
                    $seats = htmlspecialchars(trim($arrPostData->seats), ENT_QUOTES, "UTF-8");
                    $aircondition = htmlspecialchars(trim($arrPostData->aircondition), ENT_QUOTES, "UTF-8");
                    $vin = htmlspecialchars(trim($arrPostData->vin), ENT_QUOTES, "UTF-8");
                    $spz = htmlspecialchars(trim($arrPostData->spz), ENT_QUOTES, "UTF-8");
                    $nickname = htmlspecialchars(trim($arrPostData->nickname), ENT_QUOTES, "UTF-8");
                    $name_engine = htmlspecialchars(trim($arrPostData->name_engine), ENT_QUOTES, "UTF-8");
                    $code = htmlspecialchars(trim($arrPostData->code), ENT_QUOTES, "UTF-8");
                    $displacement = htmlspecialchars(trim($arrPostData->displacement), ENT_QUOTES, "UTF-8");
                    $power = htmlspecialchars(trim($arrPostData->power), ENT_QUOTES, "UTF-8");
                    $torque = htmlspecialchars(trim($arrPostData->torque), ENT_QUOTES, "UTF-8");
                    $oil_capacity = htmlspecialchars(trim($arrPostData->oil_capacity), ENT_QUOTES, "UTF-8");
                    $transmition = htmlspecialchars(trim($arrPostData->transmition), ENT_QUOTES, "UTF-8");
                    $user_id = htmlspecialchars(trim($arrPostData->user_id), ENT_QUOTES, "UTF-8");

                    // Aktualizace vozidla
                    $carModel->updateCar($id, $brand, $model, $manufacture, $mileage, $fuel, 
                        $body, $color, $drive4x4, $doors, $seats, $aircondition, $vin, $spz, 
                        $nickname, $name_engine, $code, $displacement, $power, $torque, $oil_capacity, 
                        $transmition, $user_id);

                    $responseData = json_encode(array('status' => 'success', 'message' => 'Uživatel byl úspěšně vytvořen.'));
                } 
                else 
                {
                    throw new Exception('Chybějící povinná data.');
                }
            } 
            catch (Exception $e) 
            {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error - seš kokot fakt, že jo';
            }
        } 
        else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } 
        else 
        {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
