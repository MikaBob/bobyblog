<?php
namespace App\Entity;

use App\Entity\Image;

class AbstractPost
{
    public function addImageToAlbum(string $uploadDir, Image $image, string $album_dir): Image {
        $newFilename = $this->getHappenedDate()->format('Y.m.d').'.'.uniqid(mt_rand(), true);
        $newPath = $album_dir.$this->getHappenedDate()->format('/Y/m/d/');

        if(!is_dir($newPath)){
            mkdir($newPath, 0700, true);
        }
        rename($uploadDir."/".$image->getFilenameWithExtension(), $newPath.$newFilename.$image->getExtension());
        
        $image->setFilename($newFilename);
        $image->setTags($this->getTags());
        $image->setPost($this);
        return $image;
    }
    /*
    public function addImageId(int $id): int
    {
        $imageIds = $this->getImageIds() ?? "";
        
        
        $this->setImageIds($imageIds === "" ? $id : $imageIds.";".$id);
        return count(explode($this->getImageIds(),";"));
    }
    
    public function removeImageId(int $id): int
    {
        $imageIds = $this->getImageIds() ? explode(";", $this->getImageIds()): [];
        
        $key = array_search($id, $imageIds);
        if($key !== false){
            unset($imageIds[$key]);
        }
        $this->setImageIds(implode(";", $imageIds));
        return count($imageIds);
    }
     * */
}