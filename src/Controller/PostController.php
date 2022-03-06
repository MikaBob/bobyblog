<?php

namespace Bobyblog\Controller;

use Bobyblog\Router;
use Bobyblog\Model\Entity\Post;
use Bobyblog\Model\Entity\Media;
use Bobyblog\Model\PostDAO;
use Bobyblog\Model\MediaDAO;

class PostController extends DefaultController {
    
    public function create(){
        $post = null;
        $errors = [];

        // if form is submited
        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) === "POST"){
            /**
             * @TODO make real validation with constraints
             */
            $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
            $happenedDate = filter_input(INPUT_POST, 'happenedDate', FILTER_SANITIZE_STRING);
            $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);
            $medias = filter_input(INPUT_POST, 'medias', FILTER_SANITIZE_STRING);

            try{
                // Insert new Post
                $post = new Post(
                    $text,
                    new \DateTime($happenedDate),
                    new \DateTime(),
                    $tags !== '' ? explode(';', $tags) : []
                );

                $postDAO = new PostDAO();
                $result = $postDAO->insert($post);
                $post->setId($postDAO->getDbConnection()->lastInsertId());
                
                $mediaDAO = new MediaDAO();

                foreach(explode(';', $medias) as $imgageId){
                    $img = $mediaDAO->getById($imgageId);
                    if (file_exists(UPLOAD_DIR . $img->getFilenameWithExtension())) {
                        $img->setPostId($post->getId());
                        $this->moveMediaToAlbum($img, $post->getHappenedDate());
                        $mediaDAO->update($img);
                    } else {
                        $post->removeMedia($media);
                    }
                }

                if($result->errorCode() !== \PDO::ERR_NONE){
                    $errors[] = $result->errorInfo();
                } else {
                    header('Location: ' . Router::generateUrl('blog', 'index'));
                }
            } catch(\PDOException $ex){
                $errors[] = $ex->getMessage();
            } catch(\TypeError $error){
                $errors[] = $error->getMessage();
            }
        }

        echo $this->twig->render('Post/create.html.twig', [
            'post' => $post,
            'errors' => $errors,
            'uploadDir' => $_ENV['UPLOAD_DIR']
        ]);
    }
    
    private function moveMediaToAlbum(Media $media, \DateTime $happenedDate): Media {
        $newFilename = $happenedDate->format('Y.m.d').'.'.$media->getId().'.'.uniqid(mt_rand(), true);
        $newPath = ALBUM_DIR.$happenedDate->format('/Y/m/d/');

        if(!is_dir($newPath)){
            mkdir($newPath, 0700, true);
        }

        rename(UPLOAD_DIR.$media->getFilenameWithExtension(), $newPath.$newFilename.$media->getExtension());
        
        $media->setFilename($newFilename);
        return $media;
    }
}