<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TexteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      mercure=true,
 *      normalizationContext={"groups"={"texte:get"}},
 *      collectionOperations={
 *         "get",
 *         "post"={
 *             "normalization_context"={"groups"={"texte:get"}},
 *             "denormalization_context"={"groups"={"texte:post"}},
 *             "method"="POST"
 *         }
 *      },
 *      itemOperations={
 *          "get"={
 *              "normalization_context": { "groups" = {"texte:get"} },
 *           },
 *          "put"={
 *               "normalization_context": { "groups" = {"texte:get"} },
 *               "denormalization_context": { "groups" = {"texte:put"} },
 *          },
 *          "delete"={
 *               "normalization_context": { "groups" = {"texte:get"} },
 *               "denormalization_context": { "groups" = {"texte:delete"} },
 *          },
 *          "patch"={
 *               "normalization_context": { "groups" = {"texte:get"} },
 *               "denormalization_context": { "groups" = {"texte:patch"} },
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass=TexteRepository::class)
 */
class Texte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Groups("texte:get")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * 
     * @Groups({"texte:get", "texte:post", "texte:put", "texte:patch"})
     */
    private $titre;

    /**
     * 
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="texte", orphanRemoval=true)
     * @ORM\JoinColumn(name="article", nullable=true)
     * 
     * @Groups("texte:get")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="texte")
     * @ORM\JoinColumn(name="user", nullable=true)
     * @Groups({"texte:get", "texte:put", "texte:patch"})
     */
    private $users;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * 
     * @Groups({"texte:get", "texte:post", "texte:put", "texte:patch"})
     */
    private $contenu;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setTexte($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getTexte() === $this) {
                $article->setTexte(null);
            }
        }

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
            $user->addTexte($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeTexte($this);
        }

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
}
