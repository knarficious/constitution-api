<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RevisionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(mercure=true)
 * @ORM\Entity(repositoryClass=RevisionRepository::class)
 */
class Revision
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(min=0, max=5)
     */
    private $evaluation;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="revision")
     * @ORM\JoinColumn(name="user", nullable=true)
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull
     */
    private $datePublication;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="revisions")
     * 
     * @Assert\NotBlank
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluation(): ?int
    {
        return $this->evaluation;
    }

    public function setEvaluation(int $evaluation): self
    {
        $this->evaluation = $evaluation;

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

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
