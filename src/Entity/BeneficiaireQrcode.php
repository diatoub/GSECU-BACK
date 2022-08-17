<?php

namespace App\Entity;

use App\Repository\BeneficiaireQrcodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeneficiaireQrcodeRepository::class)
 */
class BeneficiaireQrcode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Dossier::class, inversedBy="beneficiaireQrcodes")
     */
    private $dossier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statutQrcode;

    /**
     * @ORM\OneToMany(targetEntity=HistoriqueLectureQrcode::class, mappedBy="beneficiareQrcode")
     */
    private $historiqueLectureQrcodes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsInterne;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sendQrcode;

    public function __construct()
    {
        $this->historiqueLectureQrcodes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->prenom."  ".$this->prenom ;
    } 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getStatutQrcode(): ?bool
    {
        return $this->statutQrcode;
    }

    public function setStatutQrcode(bool $statutQrcode): self
    {
        $this->statutQrcode = $statutQrcode;

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueLectureQrcode>
     */
    public function getHistoriqueLectureQrcodes(): Collection
    {
        return $this->historiqueLectureQrcodes;
    }

    public function addHistoriqueLectureQrcode(HistoriqueLectureQrcode $historiqueLectureQrcode): self
    {
        if (!$this->historiqueLectureQrcodes->contains($historiqueLectureQrcode)) {
            $this->historiqueLectureQrcodes[] = $historiqueLectureQrcode;
            $historiqueLectureQrcode->setBeneficiareQrcode($this);
        }

        return $this;
    }

    public function removeHistoriqueLectureQrcode(HistoriqueLectureQrcode $historiqueLectureQrcode): self
    {
        if ($this->historiqueLectureQrcodes->removeElement($historiqueLectureQrcode)) {
            // set the owning side to null (unless already changed)
            if ($historiqueLectureQrcode->getBeneficiareQrcode() === $this) {
                $historiqueLectureQrcode->setBeneficiareQrcode(null);
            }
        }

        return $this;
    }

    public function getIsInterne(): ?bool
    {
        return $this->IsInterne;
    }

    public function setIsInterne(bool $IsInterne): self
    {
        $this->IsInterne = $IsInterne;

        return $this;
    }

    public function getSendQrcode(): ?bool
    {
        return $this->sendQrcode;
    }

    public function setSendQrcode(bool $sendQrcode): self
    {
        $this->sendQrcode = $sendQrcode;

        return $this;
    }
}
