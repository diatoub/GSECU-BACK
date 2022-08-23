<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $telephone;

    /**
     * @var Profil
     *
     * @ORM\ManyToOne(targetEntity="Profil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profil_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $profil;

    /**
     * @var Structure
     *
     * @ORM\ManyToOne(targetEntity="Structure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="structure_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $structure;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Dossier", mappedBy="user")
     */
    protected $dossier;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dossier", mappedBy="validateur", orphanRemoval=true)
     */
    private $dossierValidateur;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->dossier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->plainPassword = "orange";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Set profil
     *
     * @param Profil $profil
     * @return User
     */
    public function setProfil(Profil $profil = null)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return Profil
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set structure
     *
     * @param Structure $structure
     * @return User
     */
    public function setStructure(Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Add dossier
     *
     * @param Dossier $dossier
     * @return User
     */
    public function addDossier(Dossier $dossier)
    {
        $this->dossier[] = $dossier;

        return $this;
    }

    /**
     * Remove dossier
     *
     * @param Dossier $dossier
     */
    public function removeDossier(Dossier $dossier)
    {
        $this->dossier->removeElement($dossier);
    }

    /**
     * Get dossier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDossier()
    {
        return $this->dossier;
    }
}
