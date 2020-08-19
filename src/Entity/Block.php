<?php

namespace App\Entity;

use App\Repository\BlockRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=BlockRepository::class)
 * @Vich\Uploadable()
 */
class Block
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="blocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $page;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobile_breakpoint;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $desktop_breakpoint;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buid;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_name;

    /**
     * @Vich\UploadableField(mapping="block_image", fileNameProperty="imageName", size="imageSize")
     * @var File\Null
     */
    private $image_file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMobileBreakpoint(): ?string
    {
        return $this->mobile_breakpoint;
    }

    public function setMobileBreakpoint(string $mobile_breakpoint): self
    {
        $this->mobile_breakpoint = $mobile_breakpoint;

        return $this;
    }

    public function getDesktopBreakpoint(): ?string
    {
        return $this->desktop_breakpoint;
    }

    public function setDesktopBreakpoint(string $desktop_breakpoint): self
    {
        $this->desktop_breakpoint = $desktop_breakpoint;

        return $this;
    }

    public function getBuid(): ?string
    {
        return $this->buid;
    }

    public function setBuid(string $buid): self
    {
        $this->buid = $buid;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(?string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }
}
