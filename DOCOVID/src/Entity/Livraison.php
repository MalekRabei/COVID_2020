<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivraisonRepository")
 */
class Livraison
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
    private $nom_receveur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom_receveur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cite;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code_postal;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomReceveur(): ?string
    {
        return $this->nom_receveur;
    }

    public function setNomReceveur(?string $nom_receveur): self
    {
        $this->nom_receveur = $nom_receveur;

        return $this;
    }

    public function getPrenomReceveur(): ?string
    {
        return $this->prenom_receveur;
    }

    public function setPrenomReceveur(?string $prenom_receveur): self
    {
        $this->prenom_receveur = $prenom_receveur;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCite(): ?string
    {
        return $this->cite;
    }

    public function setCite(?string $cite): self
    {
        $this->cite = $cite;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

  
}
