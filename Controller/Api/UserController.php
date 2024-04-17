<?php
class UserController extends BaseController
{
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') 
        {
            try 
            {
                $userModel = new UserModel();
                $intLimit = 10;
                if (isset($_GET['limit']) && $_GET['limit']) {

                    $intLimit = $_GET['limit'];
                }

                $arrUsers = $userModel->getUsers($intLimit);
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

    public function getuserAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {

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
                $userModel = new UserModel();
                if (
                    isset($arrPostData->firstname) &&
                    isset($arrPostData->lastname) &&
                    isset($arrPostData->email) &&
                    isset($arrPostData->password)
                ) {
                    $jmeno = htmlspecialchars(trim($arrPostData->firstname), ENT_QUOTES, "UTF-8");
                    $prijmeni = htmlspecialchars(trim($arrPostData->lastname), ENT_QUOTES, "UTF-8");
                    $email = htmlspecialchars(trim($arrPostData->email), ENT_QUOTES, "UTF-8");
                    $heslo = htmlspecialchars(trim($arrPostData->password), ENT_QUOTES, "UTF-8");

                    // Přidání uživatele do databáze
                    $userModel->createUser($jmeno, $prijmeni, $email, $heslo);

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

        if (strtoupper($requestMethod) == 'DELETE') 
        {
            try 
            {
                // Získání ID uživatele ke smazání z URL parametru nebo z těla požadavku
                $idToDelete = isset($_GET['id']) ? $_GET['id'] : null;            
                if (!$idToDelete) {
                    throw new Exception('Chybějící ID uživatele k smazání.');
                }
            
                $userModel = new UserModel();
                $idToDelete = htmlspecialchars(trim($idToDelete), ENT_QUOTES, "UTF-8");
                // Smazání uživatele
                $userModel->deleteUser($idToDelete);
            
                $responseData = json_encode(array('status' => 'success', 'message' => 'Uživatel byl úspěšně smazán.'));
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


    public function updateuserAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $data = file_get_contents('php://input');
        $arrPostData = json_decode($data);

        if (strtoupper($requestMethod) == 'PUT') 
        {
            try 
            {                
                $userModel = new UserModel();
                if (
                    isset($arrPostData->firstname) &&
                    isset($arrPostData->lastname) &&
                    isset($arrPostData->email)
                ) {
                    $id = htmlspecialchars(trim($arrPostData->id), ENT_QUOTES, "UTF-8");
                    $jmeno = htmlspecialchars(trim($arrPostData->firstname), ENT_QUOTES, "UTF-8");
                    $prijmeni = htmlspecialchars(trim($arrPostData->lastname), ENT_QUOTES, "UTF-8");
                    $email = htmlspecialchars(trim($arrPostData->email), ENT_QUOTES, "UTF-8");
                    // Přidání uživatele do databáze
                    $userModel->updateUser($id, $jmeno, $prijmeni, $email);
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
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error - ahoj chyba';
            }
        } 
        else 
        {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

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
    

    public function updatepassworduserAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $data = file_get_contents('php://input');
        $arrPostData = json_decode($data);

        if (strtoupper($requestMethod) == 'PUT') {
            try 
            {           
                $userModel = new UserModel();
                if (
                    isset($arrPostData->password)
                ) {
                    $id = htmlspecialchars(trim($arrPostData->id), ENT_QUOTES, "UTF-8");
                    $password = htmlspecialchars(trim($arrPostData->password), ENT_QUOTES, "UTF-8");
                    // Přidání uživatele do databáze
                    $userModel->updateUserPassword($id, $password);

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
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error - ahoj chyba';
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
