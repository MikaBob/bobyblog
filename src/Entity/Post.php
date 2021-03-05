<?php
namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post extends AbstractPost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"postWithImages"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"postWithImages"})
     */
    private $happenedDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"postWithImages"})
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="post", orphanRemoval=false)
     * @Groups({"postWithImages"})
     */
    private $images;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $tags;


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getHappenedDate(): ?\DateTimeInterface
    {
        return $this->happenedDate;
    }

    public function setHappenedDate(\DateTimeInterface $happenedDate): self
    {
        $this->happenedDate = $happenedDate;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPost($this);
        }
        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            if ($image->getPost() === $this) {
                $image->setPost(null);
            }
        }
        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }
}
