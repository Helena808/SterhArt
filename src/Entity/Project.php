<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
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
    private $projectTitle;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="project")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stage", mappedBy="projectID")
     */
    private $stagesID;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="project_id", cascade = {"persist"})
     */
    private $photos;



    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectTitle(): ?string
    {
        return $this->projectTitle;
    }

    public function setProjectTitle(string $projectTitle): self
    {
        $this->projectTitle = $projectTitle;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }



    /**
     * @return Collection|Stage[]
     */
    public function getStagesID(): Collection
    {
        return $this->stagesID;
    }

    public function addStagesID(Stage $stagesID): self
    {
        if (!$this->stagesID->contains($stagesID)) {
            $this->stagesID[] = $stagesID;
            $stagesID->setProjectID($this);
        }

        return $this;
    }

    public function removeStagesID(Stage $stagesID): self
    {
        if ($this->stagesID->contains($stagesID)) {
            $this->stagesID->removeElement($stagesID);
            // set the owning side to null (unless already changed)
            if ($stagesID->getProjectID() === $this) {
                $stagesID->setProjectID(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProjectId($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProjectId() === $this) {
                $photo->setProjectId(null);
            }
        }

        return $this;
    }

    
}
