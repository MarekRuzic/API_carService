<?php
class RepairController extends BaseController
{   
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {

            try {
                $repairModel = new RepairModel();

                $car_id = -1;
                if (isset($_GET['id']) && $_GET['id']) 
                {
                  $car_id = $_GET['id'];
                }
                else
                {
                  throw new Exception('Nebylo nalezeno id');
                }

                $arrRepairs = $repairModel->getRepairs($car_id);
                $responseData = json_encode($arrRepairs);
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


    public function createAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $data = file_get_contents('php://input');
        $arrPostData = json_decode($data);

        if (strtoupper($requestMethod) == 'POST') {
            try 
            {        
                $repairModel = new RepairModel();                
                if (
                    isset($arrPostData->name) &&
                    isset($arrPostData->date) &&
                    isset($arrPostData->mileage) &&
                    isset($arrPostData->description) &&
                    isset($arrPostData->price) &&
                    isset($arrPostData->car_id)
                ) 
                {                    
                    $name = htmlspecialchars(trim($arrPostData->name), ENT_QUOTES, "UTF-8");
                    $date = htmlspecialchars(trim($arrPostData->date), ENT_QUOTES, "UTF-8");
                    $mileage = htmlspecialchars(trim($arrPostData->mileage), ENT_QUOTES, "UTF-8");
                    $description = htmlspecialchars(trim($arrPostData->description), ENT_QUOTES, "UTF-8");
                    $price = htmlspecialchars(trim($arrPostData->price), ENT_QUOTES, "UTF-8");
                    $part_name = htmlspecialchars(trim($arrPostData->part_name), ENT_QUOTES, "UTF-8");
                    $url = htmlspecialchars(trim($arrPostData->url), ENT_QUOTES, "UTF-8");
                    $car_id = htmlspecialchars(trim($arrPostData->car_id), ENT_QUOTES, "UTF-8");
                    // Přidání nové opravy
                    $repairModel->createRepair($name, $date, $mileage, $description, $price, $part_name, $url, $car_id);                    
                    $responseData = json_encode(array('status' => 'success', 'message' => 'Oprava byla úspěšně vytvořena'));
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

    public function updateAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $data = file_get_contents('php://input');
        $arrPostData = json_decode($data);

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $repairModel = new RepairModel();
                if (
                    isset($arrPostData->id) &&
                    isset($arrPostData->name) &&
                    isset($arrPostData->date) &&
                    isset($arrPostData->mileage) &&
                    isset($arrPostData->description) &&
                    isset($arrPostData->price) &&
                    isset($arrPostData->car_id)
                ) 
                {
                    $id = htmlspecialchars(trim($arrPostData->id), ENT_QUOTES, "UTF-8");
                    $name = htmlspecialchars(trim($arrPostData->name), ENT_QUOTES, "UTF-8");
                    $date = htmlspecialchars(trim($arrPostData->date), ENT_QUOTES, "UTF-8");
                    $mileage = htmlspecialchars(trim($arrPostData->mileage), ENT_QUOTES, "UTF-8");
                    $description = htmlspecialchars(trim($arrPostData->description), ENT_QUOTES, "UTF-8");
                    $price = htmlspecialchars(trim($arrPostData->price), ENT_QUOTES, "UTF-8");
                    $part_name = htmlspecialchars(trim($arrPostData->part_name), ENT_QUOTES, "UTF-8");
                    $url = htmlspecialchars(trim($arrPostData->url), ENT_QUOTES, "UTF-8");
                    $car_id = htmlspecialchars(trim($arrPostData->car_id), ENT_QUOTES, "UTF-8");

                    $repairModel->updateRepair($id, $name, $date, $mileage, $description, $price, $part_name, $url);
                    
                    $responseData = json_encode(array('status' => 'success', 'message' => 'Oprava byla aktualizována'));
                } 
                else 
                {
                    throw new Exception('Chybějící povinná data.');
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error - seš kokot fakt, že jo';
            }
        } else {
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
                // Získání ID opravy ke smazání z URL parametru            
                $idToDelete = isset($_GET['id']) ? $_GET['id'] : null;            
                if (!$idToDelete) {
                  throw new Exception('Chybějící ID vozidla k smazání.');
                }
            
                $repairModel = new RepairModel();            
                $idToDelete = htmlspecialchars(trim($idToDelete), ENT_QUOTES, "UTF-8");
            
                // Smazání Opravy
                $repairModel->deleteRepair($idToDelete);            
                $responseData = json_encode(array('status' => 'success', 'message' => 'Oprava byla úspěšně smazána'));
            } 
            catch (Exception $e) 
            {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error' . $e->getMessage();
            }
        } 
        else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
    
        // Odeslání odpovědi
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
