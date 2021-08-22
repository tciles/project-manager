<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ProjectVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private bool $active = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="versions")
     * @var Project|null
     */
    private ?Project $project = null;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProjectTag", inversedBy="versions", cascade={"persist"})
     * @ORM\JoinTable(name="project_version_has_project_tag")
     * @var Collection<int, ProjectTag>
     */
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return ProjectVersion
     */
    public function setId(?int $id): ProjectVersion
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
     * @return ProjectVersion
     */
    public function setName(?string $name): ProjectVersion
    {
        $this->name = $name;
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
     * @return ProjectVersion
     */
    public function setActive(bool $active): ProjectVersion
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     * @return ProjectVersion
     */
    public function setProject(?Project $project): ProjectVersion
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return Collection<int, ProjectTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection<int, ProjectTag> $tags
     * @return ProjectVersion
     */
    public function setTags(Collection $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param ProjectTag $tag
     * @return $this
     */
    public function addTag(ProjectTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $tag->addVersion($this);
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * @param ProjectTag $tag
     * @return $this
     */
    public function removeTag(ProjectTag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeVersion($this);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getOpacity(): int
    {
        $opacity = 100;

        if (!$this->tags->isEmpty()) {
            /** @var ProjectTag $tag */
            foreach ($this->tags as $tag) {
                $name = strtolower($tag->getName());

                if (in_array($name, ['unmaintained', 'deprecated'], true)) {
                    $opacity = 40;
                }
            }
        }

        return $opacity;
    }
}
