<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 * @UniqueEntity(
 *     fields={"slug"},
 *     message="This url path {{ value }} is already being used."
 * )
 * @UniqueEntity(
 *     fields={"title"},
 *     message="{{ value }} is already being used as page title"
 * )
 */
class Page
{
    /**
     * STATIC METHODS
     */
    public static function cleanSlug(String $slug): string
    {
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $slug);
        /*$slug = str_replace(" ", "-", $slug);*/
        return $slug;
    }
    /**
     * END OF STATIC METHODS
     */

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)

     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $in_navigation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $priority_list;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $puid;

    /**
     * @ORM\OneToMany(targetEntity=Block::class, mappedBy="page", orphanRemoval=true, fetch="EAGER")
     */
    private $blocks;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInNavigation(): ?bool
    {
        return $this->in_navigation;
    }

    public function setInNavigation(?bool $in_navigation): self
    {
        $this->in_navigation = $in_navigation;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getPriorityList(): ?string
    {
        return $this->priority_list;
    }

    public function setPriorityList(?string $priority_list): self
    {
        $this->priority_list = $priority_list;

        return $this;
    }

    public function getActiveString(): string
    {
        return $this->getActive() ? "true" : "false";
    }

    public function getInNavigationString(): string
    {
        return $this->getInNavigation() ? "true" : "false";
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPuid(): ?string
    {
        return $this->puid;
    }

    public function setPuid(string $puid): self
    {
        $this->puid = $puid;

        return $this;
    }

    /**
     * @return Collection|Block[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setPage($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->blocks->contains($block)) {
            $this->blocks->removeElement($block);
            // set the owning side to null (unless already changed)
            if ($block->getPage() === $this) {
                $block->setPage(null);
            }
        }

        return $this;
    }
}
