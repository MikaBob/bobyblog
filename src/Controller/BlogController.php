<?php

namespace Bobyblog\Controller;

use Bobyblog\Model\PostDAO;

class BlogController extends DefaultController {

    public function index($parameters){
        try{
            $from = filter_input(INPUT_GET, 'from', FILTER_SANITIZE_SPECIAL_CHARS);
            $from = $from === null ? new \DateTime('1980-01-01') : new \DateTime($from);
            
            $to = filter_input(INPUT_GET, 'to', FILTER_SANITIZE_SPECIAL_CHARS);
            $to = $to === null ? new \DateTime() : new \DateTime($to);
            
            $tags = explode(' ', filter_input(INPUT_GET, 'tags', FILTER_SANITIZE_SPECIAL_CHARS));
            
            $currentPage = intval((isset($parameters[0]) && $parameters[0] ==='page' && isset($parameters[1])) ? $parameters[1] : 1);
        } catch(\Exception $e){
            $from = null;
            $to = null;
            $tags = null;
        }
        
        if($from > $to){
            $tmp = $to;
            $to = $from;
            $from = $tmp;
        }
        
        if($from > new \DateTime()){
            $from = new \DateTime();
        }
        
        if($to > new \DateTime()){
            $to = new \DateTime();
        }

        if(isset($tags[0]) && $tags[0] === ""){
            $tags = [];
        }

        $data = [
            'happenedDate' => [ 
                'from' => $from,
                'to' => $to
            ],
            'tags' => $tags,
        ];

        $posts = (new PostDAO())->listBy($data, ($currentPage-1) * 10);
        echo $this->twig->render('Blog/index.html.twig', array_merge([
            'posts' => $posts['posts'],
            'totalResults' => $posts['count'],
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPages' => ceil($posts['count'] / 10),
                'urlParameters' => '?from='.$from->format('Y-m-d').'&to='.$to->format('Y-m-d').'&tags='.implode(' ', $tags)
            ]
        ], $data));
    }
}