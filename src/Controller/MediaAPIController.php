<?php

namespace Bobyblog\Controller;

use Bobyblog\Model\Entity\Media;
use Bobyblog\Model\MediaDAO;

class MediaAPIController extends DefaultAPIController {
    
    public static $ACCEPTED_MIME_TYPE = [
        'image/png', 
        'image/svg+xml', 
        'image/svg', 
        'image/jpeg',
        'video/mp4',
        'video/x-matroska' // mkv
    ];
            
    public function uploadMedia(){
        
        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) !== 'POST' 
            || empty($_FILES)
            || !isset($_FILES['files'])
        ){
            return $this->generateResponse(400, 'Bad Request');
        }
        $extension = strrchr($_FILES['files']['name'], '.');
        $filename = 'tmp_' . uniqid(mt_rand(), true);

        if(!in_array(mime_content_type($_FILES['files']['tmp_name']), MediaAPIController::$ACCEPTED_MIME_TYPE)){
            return $this->generateResponse(403, $_FILES['files']['name'].': Media type not supported. Acceptable files:<br>'. implode('<br>',MediaAPIController::$ACCEPTED_MIME_TYPE));
        }
                
        if(!move_uploaded_file($_FILES['files']['tmp_name'], UPLOAD_DIR . $filename . $extension)){
            return $this->generateResponse(500, 'Could not move uploaded file');
        }
        
        $mediaSize = getimagesize(UPLOAD_DIR . $filename.$extension) ?? [];

        $media = new Media(
            '',
            new \DateTime(),
            $filename,
            $extension,
            $mediaSize[0] ?? 1024,
            $mediaSize[1] ?? 768,
        );

        $mediaDAO = new MediaDAO();
        $result = $mediaDAO->insert($media);
        $media->setId($mediaDAO->getDbConnection()->lastInsertId());

        if($result->errorCode() !== \PDO::ERR_NONE){
            unlink(UPLOAD_DIR . $filename . $extension);
            return $this->generateResponse(400, $result->errorCode().' '.$result->errorInfo());
        }
        return $this->generateResponse(200, ['id' => $media->getId(), 'src' => $media->getFilenameWithExtension()]);
    }
    
    public function updateMedia(){
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) !== 'POST' || !is_numeric($id)){
            return $this->generateResponse(400, 'Bad Request');
        }

        $mediaDAO = new MediaDAO();
        $media = $mediaDAO->getById(intval($id));

        if($media === null){
            return $this->generateResponse(400, 'Media not found');
        }

        /**
         * @TODO make real validation with constraints
         */
        $caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING);

        try{
            $media->setCaption($caption);
            $result = $mediaDAO->update($media);
            if($result->errorCode() !== \PDO::ERR_NONE){
                return $this->generateResponse(400, $result->errorInfo());
            }
        } catch(\PDOException $ex){
            return $this->generateResponse(400, $ex->getMessage());
        } catch(\TypeError $error){
            return $this->generateResponse(400, $error->getMessage());
        }

        return $this->generateResponse(200, ['id' => $media->getId()]);
    }
}