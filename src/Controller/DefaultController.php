<?php

namespace Bobyblog\Controller;

use \Twig\Loader\FilesystemLoader as Twig_Loader_Filesystem;
use \Twig\Environment as Twig_Environment;
use \Twig\Extra\Intl\IntlExtension;

// Mother class for controller which generate a view (which use twig)
abstract class DefaultController {

    protected $twig;

    public function __construct(){
        $this->loadTwig();
    }

    public function loadTwig(){
        $loader = new Twig_Loader_Filesystem(["./src/View"]);

        // set up environment
        $params = array(
            'cache' => ROOT_DIR.'/tmp/cache/',
            'auto_reload' => true,
            'debug' => $_ENV['IS_DEBUG']
        );

        $this->twig = new Twig_Environment($loader, $params);
        $this->twig->addExtension(new IntlExtension());
        if($_ENV['IS_DEBUG'])
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    }
    
    private function InvalidRequest(int $httpCode, array ...$params){
        http_response_code($httpCode);
        echo "Invalid request: error $httpCode.";
        print_r($params);
        die();
    }
}