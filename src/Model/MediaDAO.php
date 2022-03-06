<?php

namespace Bobyblog\Model;

use Bobyblog\Model\Entity\Media;
use Bobyblog\Model\Entity\Post;

class MediaDAO extends AbstractDAO {

    protected string $tableName = 'media';

    public function __construct(){
        parent::__construct($this->tableName);
    }

    public function insert(Media $media): \PDOStatement{
        $query = $this->getDbConnection()->prepare(''
            . 'INSERT INTO `' . $this->tableName . '` (caption, creation_date, filename, extension, width, height) '
            . 'VALUES (:caption, :creation_date, :filename, :extension, :width, :height)');

        $query->execute([
            ':caption' => $media->getCaption(),
            ':creation_date' => $media->getCreationDate()->format('c'),
            ':filename' => $media->getFilename(),
            ':extension' => $media->getExtension(),
            ':width' => $media->getWidth(),
            ':height' => $media->getHeight()
        ]);

        return $query;
    }

    public function update(Media $media): \PDOStatement{
        $sql = 'UPDATE `' . $this->tableName . '` SET '
            . ($media->getPostId() > 0 ? 'post_id = :post_id, ' : '')
            . 'caption = :caption, '
            . 'creation_date = :creation_date, '
            . 'filename = :filename, '
            . 'extension = :extension, '
            . 'width = :width, '
            . 'height = :height '
            . 'WHERE id = :id';

        $query = $this->getDbConnection()->prepare($sql);
        $params = [
            ':id' => $media->getId(),
            ':caption' => $media->getCaption(),
            ':creation_date' => $media->getCreationDate()->format('c'),
            ':filename' => $media->getFilename(),
            ':extension' => $media->getExtension(),
            ':width' => $media->getWidth(),
            ':height' => $media->getHeight()
        ];

        if($media->getPostId() > 0){
            $params = array_merge($params, [':post_id' => $media->getPostId()]);
        }

        $query->execute($params);

        return $query;
    }

    public function delete(Media $media){
        $query = $this->getDbConnection()->prepare('DELETE FROM`' . $this->tableName . '` WHERE id = :id');

        $query->execute([
            ':id' => $media->getId()
        ]);

        return $query;
    }

    public function getById($id): ?Media{
        $obj = parent::getById($id);

        if($obj !== false){
            return $this->fromObjToMedia($obj);
        }
        return null;
    }
    
    public function getByPostId(Post $post): array{
        $query = $this->getDbConnection()->prepare("SELECT * FROM $this->tableName WHERE post_id = :id");
        $query->execute([':id' => $post->getId()]);
        
        $medias = [];
        $results = $query->fetchAll(\PDO::FETCH_OBJ);
        foreach($results as $media){
            $medias[] = $this->fromObjToMedia($media);
        }
        return $medias;
    }

    private function fromObjToMedia($obj): Media{
        $media = new Media(
            $obj->caption,
            new \DateTime($obj->creation_date),
            $obj->filename,
            $obj->extension,
            $obj->width,
            $obj->height
        );
        $media->setId($obj->id);
        if($obj->post_id !== null)
            $media->setPostId($obj->post_id);
        return $media;
    }
}