<?php

namespace Bobyblog\Controller;

use Bobyblog\Model\PostDAO;

class BlogController extends DefaultController {

    public function index(){
        $posts = (new PostDAO())->listBy();
        echo $this->twig->render('Blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}