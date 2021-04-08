<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable()
 */
class User  implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Entrez un pseudo valide")
     * @Assert\Length(min=5,max=40)
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;


    private $roles;

    /**
     * @Assert\NotCompromisedPassword()
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min=7, groups={"registration,password"})
     */
    private $password;

    private $plainPassword;

    // Pour ne pas avoir a modifier le mot de passe a chaque fois que l'utilisateur le modifie

    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @Assert\Length(min=5,max=50)
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @Assert\Length(min=5,max=50)
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;

    /*  0123456789
        01 23 45 67 89
        01.23.45.67.89
        0123 45.67.89
        0033 123-456-789
        +33-1.23.45.67.89
        +33 - 123 456 789
        +33(0) 123 456 789
        +33 (0)123 45 67 89
        +33 (0)1 2345-6789
        +33(0) - 123456789
        sont captes par ce regex
     */

    /**
     *
     * @Assert\Regex(
     *     pattern="/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/",*
     * )
     * @ORM\Column(type="string", length=15)
     */
    private $phone;

    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=Meeting::class, mappedBy="organisedBy", orphanRemoval=true,cascade={"persist"})
     */
    private $organiserOf;

    /**
     * @ORM\ManyToMany(targetEntity=Meeting::class, inversedBy="participants")
     */
    private $meetings;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private ?string $imageName;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="profile_picture", fileNameProperty="imageName", size="imageSize")
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $imageSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTimeInterface|null
     */
    private ?DateTimeInterface $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="members",cascade={"persist"})
     */
    private $groups;

    public function __construct()
    {
        $this->organiserOf = new ArrayCollection();
        $this->meetings = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        if ($this->getIsAdmin()) {
            return ['ROLE_ADMIN'];
        }
         else return ['ROLE_USER'];
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }


    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Meeting[]
     */
    public function getOrganiserOf(): Collection
    {
        return $this->organiserOf;
    }

    public function addOrganiserOf(Meeting $organiserOf): self
    {
        if (!$this->organiserOf->contains($organiserOf)) {
            $this->organiserOf[] = $organiserOf;
            $organiserOf->setOrganisedBy($this);
        }

        return $this;
    }

    public function removeOrganiserOf(Meeting $organiserOf): self
    {
        if ($this->organiserOf->removeElement($organiserOf)) {
            // set the owning side to null (unless already changed)
            if ($organiserOf->getOrganisedBy() === $this) {
                $organiserOf->setOrganisedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Meeting[]
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): self
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings[] = $meeting;
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        $this->meetings->removeElement($meeting);

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

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

    public function __toString() {
        return $this->username;
    }

    public function getImageName(): ?string
    {
        if (null == $this->imageName){
            return 'default.jpg';
        } else
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function serialize()
    {
        $this->imageFile = base64_encode($this->imageFile);
    }

    public function unserialize($serialized)
    {
        $this->imageFile = base64_decode($this->imageFile);

    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addMember($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeMember($this);
        }

        return $this;
    }

    public function sendEmail(MailerInterface $mailer, string $subject,string $text) :void
    {
        $email = (new TemplatedEmail())
            ->from('confirmation@meetup.com')
            ->to($this->getEmail())
            ->subject($subject)
            ->htmlTemplate('email/confirmation.html.twig')
            ->context([
                'username' => $this->getUsername(),
                'text' => $text,
            ]);

        $mailer->send($email);
    }
}
