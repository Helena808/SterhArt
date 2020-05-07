<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StageRepository")
 */
class Stage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stageTitle;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "-"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Renewal", mappedBy="stageID")
     */
    private $renewalsID;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", cascade={"persist", "remove"})
     */
    private $projectID;

    public function __construct()
    {
        $this->renewalsID = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStageTitle(): ?string
    {
        return $this->stageTitle;
    }

    public function setStageTitle(string $stageTitle): self
    {
        $this->stageTitle = $stageTitle;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Renewal[]
     */
    public function getRenewalsID(): Collection
    {
        return $this->renewalsID;
    }

    public function addRenewalsID(Renewal $renewalsID): self
    {
        if (!$this->renewalsID->contains($renewalsID)) {
            $this->renewalsID[] = $renewalsID;
            $renewalsID->setStageID($this);
        }

        return $this;
    }

    public function removeRenewalsID(Renewal $renewalsID): self
    {
        if ($this->renewalsID->contains($renewalsID)) {
            $this->renewalsID->removeElement($renewalsID);
            // set the owning side to null (unless already changed)
            if ($renewalsID->getStageID() === $this) {
                $renewalsID->setStageID(null);
            }
        }

        return $this;
    }

    public function getProjectID(): ?Project
    {
        return $this->projectID;
    }

    public function setProjectID(?Project $projectID): self
    {
        $this->projectID = $projectID;

        return $this;
    }
}
