<?php
namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image extends AbstractImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"postWithImages"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="images")
     * @Groups({"postWithImages"})
     */
    private $post;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $caption;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"postWithImages"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $filename;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $extension;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $width;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $height;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"postWithImages"})
     */
    private $tags;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(?string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): self
    {
        $this->height = $height;

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

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }
}