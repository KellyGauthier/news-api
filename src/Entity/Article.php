<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *  normalizationContext={"groups"={"articleRead"}},
 *  collectionOperations={"get"},
 *  attributes={"order"={"id":"DESC"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @UniqueEntity("url")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("articleRead")
     * @Groups("favoritesSourcesRead")
     * @Groups("favoritesCategoriesRead")
     */
    private $id;

    /**
     * @ORM\Column(type="text", name="url")
     * @Groups("articleRead")
     * @Groups("favoritesSourcesRead")
     * @Groups("favoritesCategoriesRead")
     * @Assert\Url
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("articleRead")
     * @Groups("favoritesSourcesRead")
     * @Groups("favoritesCategoriesRead")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("articleRead")
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("articleRead")
     * @Groups("favoritesSourcesRead")
     * @Groups("favoritesCategoriesRead")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("articleRead")
     * @Groups("favoritesSourcesRead")
     * @Groups("favoritesCategoriesRead")
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article", orphanRemoval=true)
     * @Groups("articleRead")
     * @Groups("favoritesSourcesRead")
     * @Groups("favoritesCategoriesRead")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Source", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("articleRead")
     */
    private $source;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;

        return $this;
    }
}
