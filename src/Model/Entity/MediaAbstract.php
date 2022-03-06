<?php

namespace Bobyblog\Model\Entity;

abstract class MediaAbstract {
    
    public function getFileNameWithExtension(): string{
        return $this->getFileName().$this->getExtension();
    }
    
    public function isVideo(): string{
        return in_array($this->getExtension(), ['.mkv', '.mp4']);
    }
}