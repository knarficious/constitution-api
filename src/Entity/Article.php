<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      mercure=true,
 *      normalizationContext={"groups"={"article:get"}},
 *      collectionOperations={
 *         "get",
 *         "post"={
 *             "normalization_context"={"groups"={"article:get"}},
 *             "denormalization_context"={"groups"={"article:post"}},
 *             "method"="POST"
 *         }
 *      },
 *      itemOperations={
 *          "get"={
 *              "normalization_context": { "groups" = {"article:get"} },
 *           },
 *          "put"={
 *               "normalization_context": { "groups" = {"article:get"} },
 *               "denormalization_context": { "groups" = {"article:put"} },
 *          },
 *          "delete"={
 *               "normalization_context": { "groups" = {"article:get"} },
 *               "denormalization_context": { "groups" = {"article:delete"} },
 *          },
 *          "patch"={
 *               "normalization_context": { "groups" = {"article:get"} },
 *               "denormalization_context": { "groups" = {"article:patch"} },
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Groups("article:get")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Groups({"article:get", "article:post", "article:put"})
     */
    private $numero;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * 
     * @Groups({"article:get", "article:post", "article:put"})
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("article:get")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups({"article:get", "article:put"})
     */
    private $dateModification;

    /**
     * @ORM\ManyToOne(targetEntity=Texte::class, inversedBy="articles", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="texte", nullable=false)
     * @Assert\NotNull
     * @Groups({"article:get", "article:post"})
     */
    private $texte;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="article")
     * @ORM\JoinColumn(name="user", nullable=true)
     * 
     * @Groups({"article:get", "article:post", "article:put", "article:patch"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Revision::class, mappedBy="article")
     * @ORM\JoinColumn(name="revision", nullable=true)
     * 
     * @Groups("article:get")
     */
    private $revisions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->revisions = new ArrayCollection();
        $this->setDateCreation(new \DateTime("now"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getTexte(): ?Texte
    {
        return $this->texte;
    }

    public function setTexte(?Texte $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addArticle($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection|Revision[]
     */
    public function getRevisions(): Collection
    {
        return $this->revisions;
    }

    public function addRevision(Revision $revision): self
    {
        if (!$this->revisions->contains($revision)) {
            $this->revisions[] = $revision;
            $revision->setArticle($this);
        }

        return $this;
    }

    public function removeRevision(Revision $revision): self
    {
        if ($this->revisions->contains($revision)) {
            $this->revisions->removeElement($revision);
            // set the owning side to null (unless already changed)
            if ($revision->getArticle() === $this) {
                $revision->setArticle(null);
            }
        }

        return $this;
    }
}
