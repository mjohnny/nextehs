<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $placeNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tag;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventInscription", mappedBy="event", orphanRemoval=true)
     */
    private $inscriptions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Program", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Program;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Article", mappedBy="event", cascade={"persist"})
     */
    private $article;

    /**
     * Event constructor.
     *
     */
    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->archived = false;
    }

    public function getId()
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getPlaceNumber(): ?int
    {
        return $this->placeNumber;
    }

    public function setPlaceNumber(?int $placeNumber): self
    {
        $this->placeNumber = $placeNumber;

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return Collection|EventInscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(EventInscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setEvent($this);
        }

        return $this;
    }

    public function removeInscription(EventInscription $inscription): self
    {
        if ($this->inscriptions->contains($inscription)) {
            $this->inscriptions->removeElement($inscription);
            // set the owning side to null (unless already changed)
            if ($inscription->getEvent() === $this) {
                $inscription->setEvent(null);
            }
        }

        return $this;
    }

    public function getProgram(): ?Program
    {
        return $this->Program;
    }

    public function setProgram(?Program $Program): self
    {
        $this->Program = $Program;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        // set (or unset) the owning side of the relation if necessary
        $newEvent = $article === null ? null : $this;
        if ($newEvent !== $article->getEvent()) {
            $article->setEvent($newEvent);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

}
