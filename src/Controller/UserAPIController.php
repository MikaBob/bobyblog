<?php

namespace Bobyblog\Controller;

use Bobyblog\Model\Entity\User;
use Bobyblog\Model\UserDAO;

class UserAPIController extends DefaultAPIController {

    /**
     * Return list of users
     * @TODO sort, offset, limit
     */
    public function list(){
        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) !== "GET"){
            return $this->generateResponse(400, 'Bad Request');
        }

        $userDAO = new UserDAO();
        $result = $userDAO->getAll();

        if($result === false){
            return $this->generateResponse(400, $result->errorInfo());
        }

        // Do not show users's password
        foreach($result as $user){
            $user->password = '';
        }

        return $this->generateResponse(200, ['users' => $result]);
    }

    // Get a specific User
    public function get($params){
        $id = $params[0] ?? null;

        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) !== "GET" || !is_numeric($id)){
            return $this->generateResponse(400, 'Bad Request');
        }

        $userDAO = new UserDAO();
        $user = $userDAO->getById(intval($id));

        if($user === null){
            return $this->generateResponse(400, 'User not found');
        }

        // Do not show users's password
        $user->setPassword('');

        return $this->generateResponse(200, ['user' => $user]);
    }

    // Update an User
    public function post($params){
        $id = $params[0] ?? null;

        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) !== "POST" || !is_numeric($id)){
            return $this->generateResponse(400, 'Bad Request');
        }

        $userDAO = new UserDAO();
        $user = $userDAO->getById(intval($id));

        if($user === null){
            return $this->generateResponse(400, 'User not found');
        }

        /**
         * @TODO make real validation with constraints
         */
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $dynamicFieldsFromForm = filter_input(INPUT_POST, 'dynamicFields', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $isMOLGranted = $dynamicFieldsFromForm[User::DYNAMIC_FIELD_MICROSOFT_OFFICE_LICENSE] === 'true' ? true : false;
        $isEAGGranted = $dynamicFieldsFromForm[User::DYNAMIC_FIELD_EMAIL_ACCESS_GRANTED] === 'true' ? true : false;
        $isGRGGranted = $dynamicFieldsFromForm[User::DYNAMIC_FIELD_GIT_REPOSITORY_GRANTED] === 'true' ? true : false;
        $isJAGGranted = $dynamicFieldsFromForm[User::DYNAMIC_FIELD_JIRA_ACCESS_GRANTED] === 'true' ? true : false;

        try{
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setDynamicFieldMicrosoftOffice($isMOLGranted);
            $user->setDynamicFieldEmailAccess($isEAGGranted);
            $user->setDynamicFieldGitRepository($isGRGGranted);
            $user->setDynamicFieldJira($isJAGGranted);

            $result = $userDAO->update($user);
            if($result->errorCode() !== \PDO::ERR_NONE){
                return $this->generateResponse(400, $result->errorInfo());
            }
        } catch(\PDOException $ex){
            return $this->generateResponse(400, $ex->getMessage());
        } catch(\TypeError $error){
            return $this->generateResponse(400, $error->getMessage());
        }

        // Do not show users's password
        $user->setPassword('');

        return $this->generateResponse(200, ['user' => $user]);
    }
}