<?php

namespace Bobyblog\Model\Entity;

class Media extends MediaAbstract {
    
    private int $id;
    private int $postId;
    
    public function __construct(
        private string $caption,
        private \Datetime $creationDate,
        private string $filename,
        private string $extension,
        private string $width,
        private string $height
    ){
        $this->postId = -1;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getPostId(): int{
        return $this->postId;
    }

    public function getCaption(): string{
        return $this->caption;
    }

    public function getCreationDate(): \Datetime{
        return $this->creationDate;
    }

    public function getFilename(): string{
        return $this->filename;
    }

    public function getExtension(): string{
        return $this->extension;
    }

    public function getWidth(): string{
        return $this->width;
    }

    public function getHeight(): string{
        return $this->height;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setPostId(int $postId): void{
        $this->postId = $postId;
    }

    public function setCaption(string $caption): void{
        $this->caption = $caption;
    }

    public function setCreationDate(\Datetime $creationDate): void{
        $this->creationDate = $creationDate;
    }

    public function setFilename(string $filename): void{
        $this->filename = $filename;
    }

    public function setExtension(string $extension): void{
        $this->extension = $extension;
    }

    public function setWidth(string $width): void{
        $this->width = $width;
    }

    public function setHeight(string $height): void{
        $this->height = $height;
    }
}