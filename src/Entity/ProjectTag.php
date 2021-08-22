<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint("name_unique", columns={"name"})})
 * @ORM\Entity
 */
class ProjectTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=10)
     * @var string|null
     */
    private ?string $color = null;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProjectVersion", mappedBy="tags", cascade={"persist"})
     * @var Collection<int, ProjectVersion>
     */
    private Collection $versions;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ProjectTag
     */
    public function setId(?int $id): ProjectTag
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ProjectTag
     */
    public function setName(?string $name): ProjectTag
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return ProjectTag
     */
    public function setColor(?string $color): ProjectTag
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return Collection<int, ProjectVersion>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    /**
     * @param Collection<int, ProjectVersion> $versions
     * @return ProjectTag
     */
    public function setVersions(Collection $versions): ProjectTag
    {
        $this->versions = $versions;
        return $this;
    }

    /**
     * @param ProjectVersion $version
     * @return $this
     */
    public function addVersion(ProjectVersion $version): self
    {
        if (!$this->versions->contains($version)) {
            $version->addTag($this);
            $this->versions[] = $version;
        }

        return $this;
    }

    /**
     * @param ProjectVersion $version
     * @return $this
     */
    public function removeVersion(ProjectVersion $version): self
    {
        if ($this->versions->contains($version)) {
            $this->versions->removeElement($version);
            $version->removeTag($this);
        }

        return $this;
    }
}
