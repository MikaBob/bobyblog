<?php

namespace Bobyblog\Model\Entity;

class Post extends PostAbstract {

    private int $id;

    public function __construct(
            private string $text,
            private \DateTime $happened_date,
            private \DateTime $creation_date,
            private array $tags
    ){}

    public function getId(): int{
        return $this->id;
    }

    public function getText(): string{
        return $this->text;
    }

    public function getHappenedDate(): \DateTime{
        return $this->happened_date;
    }

    public function getCreationDate(): \DateTime{
        return $this->creation_date;
    }

    public function getTags(): array{
        return $this->tags;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setText(string $text): void{
        $this->text = $text;
    }

    public function setHappenedDate(\DateTime $happened_date): void{
        $this->happened_date = $happened_date;
    }

    public function setCreationDate(\DateTime $creation_date): void{
        $this->creation_date = $creation_date;
    }

    public function setTags(array $tags): void{
        $this->tags = $tags;
    }
}