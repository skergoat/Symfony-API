<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 * 
 * @ExclusionPolicy("all")
 * 
 * @Hateoas\Relation(
 *    "modify",
 *    href=@Hateoas\Route("update_phone", parameters={"id" = "expr(object.getId())"}, absolute=true)
 * )
 * 
 * @Hateoas\Relation(
 *  "author",
 *  embedded=@Hateoas\Embedded("expr(object.getAuthor())")
 * )
 * 
 * @Hateoas\Relation(
 *  "self",
 *  href=@Hateoas\Route("getone_phone", parameters={"id" = "expr(object.getId())"}, absolute = true)
 * )
 *
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"create"})
     * @Serializer\Since("1.0")
     * @Expose
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min="0", minMessage="la valeur min est 0", max="1500", maxMessage="la valeur max est 1500", groups={"create"}) 
     * @Serializer\Since("1.0")
     * @Expose
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Since("1.0")
     * @Expose
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Since("1.0")
     * @Expose
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Author", mappedBy="phone", cascade={"persist"})
     * @Serializer\Since("1.0")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Since("2.0")
     * @Expose
     */
    private $shotDescription;

    public function __construct()
    {
        $this->author = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

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

    /**
     * @return Collection|Author[]
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author[] = $author;
            $author->setPhone($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->author->contains($author)) {
            $this->author->removeElement($author);
            // set the owning side to null (unless already changed)
            if ($author->getPhone() === $this) {
                $author->setPhone(null);
            }
        }

        return $this;
    }

    public function getShotDescription(): ?string
    {
        return $this->shotDescription;
    }

    public function setShotDescription(?string $shotDescription): self
    {
        $this->shotDescription = $shotDescription;

        return $this;
    }
}