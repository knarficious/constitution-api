<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DisableAutoMapping;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"read"}},
 *     "denormalization_context"={"groups"={"write"}}
 * })
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 * fields={"username", "email"}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage="Give us at least 3 characters!")
     * @Groups({"read", "write"})
     */
    private $username = '';
    
    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"read", "write"})
     */
    private $email = '';

    /**
     * @ORM\Column(type="simple_array")
     * @ApiProperty(
     *     attributes={
     *         "jsonld_context"={
     *             "@type"="http://www.w3.org/2001/XMLSchema#array",
     *         }
     *     }
     * )
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     * @DisableAutomapping
     */
    private $password = '';
    
    /**
     * @var string 
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @ORM\ManyToMany(targetEntity=Texte::class, inversedBy="users")
     * @ORM\JoinColumn(name="texte", nullable=true)
     */
    private $texte;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, inversedBy="users")
     * @ORM\JoinColumn(name="article", nullable=true)
     */
    private $article;

    /**
     * @ORM\OneToOne(targetEntity=Revision::class, mappedBy="auteur", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="revision", nullable=true)
     */
    private $revision;
    
    /**
     * @SerializedName("password")
     * @Groups("write")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $apiToken;  


    public function __construct()
    {
        $this->texte = new ArrayCollection();
        $this->article = new ArrayCollection();
        $this->setRoles(["ROLE_USER"]);
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return $this->salt;
    }    

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return Collection|Texte[]
     */
    public function getTexte(): Collection
    {
        return $this->texte;
    }

    public function addTexte(Texte $texte): self
    {
        if (!$this->texte->contains($texte)) {
            $this->texte[] = $texte;
        }

        return $this;
    }

    public function removeTexte(Texte $texte): self
    {
        if ($this->texte->contains($texte)) {
            $this->texte->removeElement($texte);
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->contains($article)) {
            $this->article->removeElement($article);
        }

        return $this;
    }

    public function getRevision(): ?Revision
    {
        return $this->revision;
    }

    public function setRevision(?Revision $revision): self
    {
        $this->revision = $revision;

        // set (or unset) the owning side of the relation if necessary
        $newAuteur = null === $revision ? null : $this;
        if ($revision->getAuteur() !== $newAuteur) {
            $revision->setAuteur($newAuteur);
        }

        return $this;    }


    
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        
        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
