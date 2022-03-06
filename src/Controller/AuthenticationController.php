<?php

namespace Bobyblog\Controller;

use Bobyblog\Model\UserDAO;
use Bobyblog\Model\Entity\User;
use Bobyblog\Router;

class AuthenticationController extends DefaultController {

    /**
     * a.k.a. Login page
     */
    public function index(){
        $error = null;
        echo $this->twig->render('Authentication/login.html.twig', ['error' => $error]);
    }

    /**
     * Generate and send back an access token (if credentials are valid of course)
     */
    public function login($params){
        header("Content-Type: application/json; charset=UTF-8");

        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) !== "POST"){
            http_response_code(400);
            return json_encode(['status' => 400, 'error' => 'Bad Request']);
        }

        /**
         * @TODO make real validation
         */
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if($username !== "" && $username !== false && $password !== "" && $password !== ""){
            $userDAO = new UserDAO();
            $user = $userDAO->getByUsername($username);

            if($user !== null){
                if(password_verify($password, $user->getPassword())){

                    $user->setLastLogin(new \DateTime());
                    /** @TODO check that SQL went well */
                    $userDAO->update($user);

                    // Do not show users's password
                    $user->setPassword('');

                    /** @TODO refresh token */
                    $token = $this->generateJWTToken($user);
                    $_SESSION['loggedUser'] = $user;
                    
                    $redirect = Router::generateUrl('post', 'create');

                    return json_encode(['status' => 200, 'token' => $token, 'redirect' => $redirect]);
                }
            }
        }

        http_response_code(400);
        return json_encode(['status' => 400, 'error' => 'Invalid credidentials']);
    }

    /**
     * https://en.wikipedia.org/wiki/JSON_Web_Token
     */
    private function generateJWTToken(User $user): string{
        $header = AuthenticationController::encodeBase64Url(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = AuthenticationController::encodeBase64Url(json_encode([
            'id' => $user->getId(),
            'iat' => $user->getLastLogin()->format('U'),
            'exp' => (time() + 60 * 15) // 15 min
        ]));

        $signature = AuthenticationController::encodeBase64Url(hash_hmac('sha256', "{$header}.{$payload}", $_ENV['SECRET'], true));

        // Generate JWT
        $jwt = "{$header}.{$payload}.{$signature}";

        return $jwt;
    }

    // Verify if the current request hold a valid token. (Token stored in cookie)
    public static function hasValidJWTToken(): bool{
        $token = filter_input(INPUT_COOKIE, 'token', FILTER_SANITIZE_STRING);

        if(!is_string($token))
            return false;

        $parts = explode('.', $token);

        // Invalid token structure
        if(count($parts) !== 3)
            return false;

        $header = base64_decode($parts[0]);
        $payload = json_decode(base64_decode($parts[1]));
        $signature = $parts[2];

        // Token expired
        if(!isset($payload->exp) || $payload->exp - time() < 0)
            return false;

        if(!isset($payload->id))
            return false;

        $user = (new UserDAO())->getById($payload->id);
        // User not found
        if($user === null)
            return false;

        // Do not show password
        $user->setPassword('');

        $signatureToHash = AuthenticationController::encodeBase64Url($header) . '.' . AuthenticationController::encodeBase64Url(json_encode($payload));

        // Repeat token generation procedure and compare signature
        $expectedSignature = AuthenticationController::encodeBase64Url(hash_hmac('SHA256', $signatureToHash, $_ENV['SECRET'], true));

        // Incorrect token
        if($signature !== $expectedSignature){
            return false;
        }

        // The token is valid.
        $_SERVER['loggedUser'] = $user;

        return true;
    }

    // Basically only Home is public
    public static function hasAccess($controller, $action){
//        return true; // for debug
        switch($controller){
            case BlogController::class:
                return $action === 'index';
            case AuthenticationController::class:
                return in_array($action,['login', 'index']);
            default:
                return AuthenticationController::hasValidJWTToken() && AuthenticationController::isAdmin();
        }
    }

    // "Encode" a string in base 64 but make it url "friendly"
    private static function encodeBase64Url($data){
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * @TODO Make real role system and control user's access
     */
    // Check if the user has admin rights
    public static function isAdmin(){
        return isset($_SERVER['loggedUser']) ? $_SERVER['loggedUser']->getId() === 1 : false;
    }
}