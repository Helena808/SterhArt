<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RenewalRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Stage", inversedBy="renewalsID")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stageID;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sketch", mappedBy="renewal_id", cascade = {"persist"})
     */
    private $sketches;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $commentClientDate;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->sketches = new ArrayCollection();
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


    public function getStageID(): ?Stage
    {
        return $this->stageID;
    }

    public function setStageID(?Stage $stageID): self
    {
        $this->stageID = $stageID;

        return $this;
    }

    /**
     * @return Collection|Sketch[]
     */
    public function getSketches(): Collection
    {
        return $this->sketches;
    }

    public function addSketch(Sketch $sketch): self
    {
        if (!$this->sketches->contains($sketch)) {
            $this->sketches[] = $sketch;
            $sketch->setRenewalId($this);
        }

        return $this;
    }

    public function removeSketch(Sketch $sketch): self
    {
        if ($this->sketches->contains($sketch)) {
            $this->sketches->removeElement($sketch);
            // set the owning side to null (unless already changed)
            if ($sketch->getRenewalId() === $this) {
                $sketch->setRenewalId(null);
            }
        }

        return $this;
    }

    public function getCommentClientDate(): ?\DateTimeInterface
    {
        return $this->commentClientDate;
    }

    public function setCommentClientDate(?\DateTimeInterface $commentClientDate): self
    {
        $this->commentClientDate = $commentClientDate;

        return $this;
    }
}
