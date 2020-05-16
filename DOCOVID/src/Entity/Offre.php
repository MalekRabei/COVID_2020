<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffreRepository")
 */
class Offre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $materiel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_offre;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $time_offre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

     /**
     * @ORM\OneToOne(targetEntity="App\Entity\Livraison", cascade={"persist", "remove"})
     */
    private $livraison;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id")
     */
    private $id_user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMateriel(): ?string
    {
        return $this->materiel;
    }

    public function setMateriel(?string $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDateOffre(): ?\DateTimeInterface
    {
        return $this->date_offre;
    }

    public function setDateOffre(?\DateTimeInterface $date_offre): self
    {
        $this->date_offre = $date_offre;

        return $this;
    }

    public function getTimeOffre(): ?\DateTimeInterface
    {
        return $this->time_offre;
    }

    public function setTimeOffre(?\DateTimeInterface $time_offre): self
    {
        $this->time_offre = $time_offre;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }


     /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }
}
