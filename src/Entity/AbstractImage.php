<?php
namespace App\Entity;


class AbstractImage
{
    public const LIST_OF_VIDEO_FORMAT = [".mp4", ".3gp", ".avi", ".mpeg", ".mkv"];
    
    public function getFilenameWithExtension(): string {
        return $this->getFilename().$this->getExtension();
    }
    
    public function getPathInAlbum(): string {
        $path = explode(".", $this->getFilename());
        return "/".$path[0]."/".$path[1]."/".$path[2]."/";
    }
    
    public function isVideo(): bool {
        return in_array($this->getExtension(), $this::LIST_OF_VIDEO_FORMAT);
    }
}