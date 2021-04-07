<?php

namespace App\Entity;

use App\Repository\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Listeners\TimeListener;

/**
 * @ORM\Entity(repositoryClass=MeetingRepository::class)
 * @ORM\EntityListeners({TimeListener::class})
 */
class Meeting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please provide a valid name")
     * @Assert\Length(min=8, max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     *
     * @Assert\GreaterThanOrEqual("now")
     * @ORM\Column(type="datetime")
     */
    private $timeStarting;

    /**
     * @Assert\Positive()
     * @ORM\Column(type="integer")
     */
    private $length;

    /**
     * @Assert\GreaterThanOrEqual("now")
     * @Assert\LessThanOrEqual(propertyPath="timeStarting")
     * @ORM\Column(type="datetime")
     */
    private $registerUntil;

    /**
     * @Assert\GreaterThanOrEqual(8)
     * @Assert\LessThan(300)
     * @ORM\Column(type="integer")
     */
    private $maxParticipants;

    /**
     * @Assert\Length(min=10, max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $information;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="organiserOf",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisedBy;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="meetings",cascade={"persist"})
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="meetings",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="meetings",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="meetings",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTimeStarting(): ?\DateTimeInterface
    {
        return $this->timeStarting;
    }

    public function setTimeStarting(\DateTimeInterface $timeStarting): self
    {
        $this->timeStarting = $timeStarting;

        return $this;
    }

    public function getLength(): ? int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getRegisterUntil(): ?\DateTimeInterface
    {
        return $this->registerUntil;
    }

    public function setRegisterUntil(\DateTimeInterface $registerUntil): self
    {
        $this->registerUntil = $registerUntil;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): self
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }


    public function getOrganisedBy(): ?User
    {
        return $this->organisedBy;
    }

    public function setOrganisedBy(?User $organisedBy): self
    {
        $this->organisedBy = $organisedBy;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addMeeting($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeMeeting($this);
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getStatus(): ?State
    {
        return $this->status;
    }

    public function setStatus(?State $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }


}
