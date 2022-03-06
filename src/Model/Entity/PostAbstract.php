<?php

namespace Bobyblog\Model\Entity;

abstract class PostAbstract {

    private array $medias = [];

    public function getMedias(): array{
        return $this->medias;
    }

    public function setMedias(array $medias): void{
        $this->medias = $medias;
    }
    
    public function getAlbumPath(): string{
        return '/album/'.$this->getHappenedDate()->format('Y/m/d/');
    }
}