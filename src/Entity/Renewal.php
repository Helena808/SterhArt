<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RenewalRepository")
 * @Vich\Uploadable
 */
class Renewal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $commentExec;

    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private $commentClient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Stage", inversedBy="renewalsID")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stageID;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCommentExec(): ?string
    {
        return $this->commentExec;
    }

    public function setCommentExec(string $commentExec): self
    {
        $this->commentExec = $commentExec;

        return $this;
    }

    public function getCommentClient(): ?string
    {
        return $this->commentClient;
    }

    public function setCommentClient(?string $commentClient): self
    {
        $this->commentClient = $commentClient;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getStageID(): ?Stage
    {
        return $this->stageID;
    }

    public function setStageID(?Stage $stageID): self
    {
        $this->stageID = $stageID;

        return $this;
    }
}
