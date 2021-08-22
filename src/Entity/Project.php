<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length="50")
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="text",  nullable=true)
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length="255", nullable=true)
     * @var string|null
     */
    private ?string $repository = null;

    /**
     * @ORM\Column(type="string", length="255", nullable=true)
     * @var string|null
     */
    private ?string $fullname = null;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private bool $active = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectVersion", mappedBy="project")
     * @ORM\OrderBy({"id": "DESC"})
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
     * @return Project
     */
    public function setId(?int $id): Project
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return Project
     */
    public function setTitle(?string $title): Project
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Project
     */
    public function setDescription(?string $description): Project
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRepository(): ?string
    {
        return $this->repository;
    }

    /**
     * @param string|null $repository
     * @return Project
     */
    public function setRepository(?string $repository): Project
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Project
     */
    public function setActive(bool $active): Project
    {
        $this->active = $active;
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
     * @return Project
     */
    public function setVersions(Collection $versions): Project
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
            $version->setProject($this);
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

            if ($version->getProject() === $this) {
                $version->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    /**
     * @param string|null $fullname
     * @return Project
     */
    public function setFullname(?string $fullname): Project
    {
        $this->fullname = $fullname;
        return $this;
    }
}
