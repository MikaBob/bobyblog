<?php

namespace Bobyblog\Model;

use Bobyblog\Model\Entity\Post;

class PostDao extends AbstractDAO {

    protected string $tableName = 'post';

    public function __construct(){
        parent::__construct($this->tableName);
    }

    public function insert(Post $post): \PDOStatement{
        $query = $this->getDbConnection()->prepare('INSERT INTO `' . $this->tableName . '` (text, happened_date, creation_date, tags) '
                . 'VALUES (:text, :happened_date, :creation_date, :tags)');

        $query->execute([
            ':text' => $post->getText(),
            ':happened_date' => $post->getHappenedDate()->format('c'),
            ':creation_date' => $post->getCreationDate()->format('c'),
            ':tags' => implode(';', $post->getTags())
        ]);

        return $query;
    }

    public function update(Post $post): \PDOStatement{
        $query = $this->getDbConnection()->prepare('UPDATE `' . $this->tableName . '` SET text = :text, happened_date = :happened_date, creation_date = :creation_date, medias = :medias, tags = :tags '
                . 'WHERE id = :id');

        $query->execute([
            ':text' => $post->getUserId(),
            ':happened_date' => $post->getStartDate()->format('c'),
            ':creation_date' => $post->getEndDate()->format('c'),
            ':medias' => $post->getMedias(),
            ':tags' => $post->getTags(),
            ':id' => $post->getId()
        ]);

        return $query;
    }

    public function delete(Post $post){
        $query = $this->getDbConnection()->prepare('DELETE FROM`' . $this->tableName . '` WHERE id = :id');

        $query->execute([
            ':id' => $post->getId()
        ]);

        return $query;
    }

    public function getById($id): ?Post{
        $obj = parent::getById($id);

        if($obj !== false){
            return $this->fromObjToPost($obj);
        }
        return null;
    }

    private function fromObjToPost($obj): Post{
        $tags = $obj->tags !== null ? explode(';', $obj->tags) : [];
        $post = new Post(
            $obj->text,
            new \DateTime($obj->happened_date),
            new \DateTime($obj->creation_date),
            ($tags[0] === '' ? [] : $tags)
        );
        
        $post->setId($obj->id);
        return $post;
    }

    public function listBy(array $param = [], $offset = 0, string $orderBy = 'happened_date', $direction = 'DESC', $limit = 10){
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM $this->tableName";
        
        if(!empty($param)){
            $sql .= "\nWHERE 1=1";
            if(isset($param['happenedDate'])){
                if(isset($param['happenedDate']['from'])){
                    $sql .= "\n\tAND `happened_date` >= '" . $param['happenedDate']['from']->format('Y-m-d')."'";
                }
                if(isset($param['happenedDate']['to'])){
                    $sql .= "\n\tAND `happened_date` <= '" . $param['happenedDate']['to']->format('Y-m-d')."'";
                }
            }
            if(isset($param['tags'])){
                $sql .= "\n\tAND (1=1";
                foreach($param['tags'] as $tag){
                    if($tag !== '')
                        $sql .= "\n\tAND `tags` LIKE \"%". str_ireplace(['"', '\\','\''], '', $tag)."%\"";
                }
                $sql .= "\n\t)";
            }
        }

        $sql .= "\nORDER BY `$orderBy` " . ($direction === 'ASC' ? $direction : 'DESC');
        $sql .= "\nLIMIT " . (is_numeric($limit) ? $limit : 0);
        $sql .= "\nOFFSET " . (is_numeric($offset) ? $offset : 0);
        $sql .= "\n;";

//        echo($sql);die();

        $query = $this->getDbConnection()->prepare($sql);
        $query->execute();
        $count = $this->getDbConnection()->query('SELECT FOUND_ROWS();')->fetchColumn();

        $posts = [];
        $mediaDao = new MediaDAO();
        $results = $query->fetchAll(\PDO::FETCH_OBJ);
        foreach($results as $post){
            $tmpPost = $this->fromObjToPost($post);
            $tmpPost->setMedias($mediaDao->getByPostId($tmpPost));
            $posts[] = $tmpPost;
        }
        return [
            'posts' => $posts,
            'count' => $count
        ];
    }
}