<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Orange\MainBundle\Entity\Indicateur;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DossierRepository")
 */
class Dossier
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
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenomBeneficiaire;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $matriculeBeneficiaire;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site_beneficiaire", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $siteBeneficiaire;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="validateur_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $validateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $matricule;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var Structure
     *
     * @ORM\ManyToOne(targetEntity="Structure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="structure_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $structure;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer", message="La valeur {{ value }} n'est pas un type {{ type }}")
     */
    private $coutDossier;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="date", nullable=true)
     */
    private $dateAjout;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     * @Assert\Type(type="integer", message="La valeur {{ value }} n'est pas un nombre, veuillez réessayer .")
     */
    private $quantite;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site",  nullable=true, referencedColumnName="id", onDelete="CASCADE")
     * })
     
     */
    private $site;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Site", inversedBy="dossier", cascade={"persist", "merge", "detach"})
     * @ORM\JoinTable(name="autorisation_has_site",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     *   }
     * )
     */
    private $siteAutorisation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     */
    private $sites;

    /**
     * @var TypeDossier
     *
     * @ORM\ManyToOne(targetEntity="TypeDossier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_dossier_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     * @Assert\NotNull(message="Vous devez choisir le type de dossier!")
     */
    private $typeDossier;

    /**
     * @var TypeMateriel
     *
     * @ORM\ManyToOne(targetEntity="TypeMateriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_materiel", referencedColumnName="id")
     * })
     */
    private $typeMateriel;

    /**
     * @var string
     *
     * @ORM\Column(name="autre_materiel", type="string", length=255, nullable=true)
     */
    private $autreMateriel;

    /**
     * @var Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etat_id", referencedColumnName="id")
     * })
     */
    private $etat;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="dossier", cascade={"persist", "detach"})
     * @ORM\JoinTable(name="dossier_has_user",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     */
    private $user;


    /**
     * @var integer
     *
     * @ORM\Column(name="code_dossier", type="string", nullable=true)
     */
    private $codeDossier;

    /**
     * @var integer
     *
     * @ORM\Column(name="code_secret", type="string", nullable=true)
     */
    private $codeSecret;

    private $message;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ComplementDossier", inversedBy="dossier", cascade={"persist"})
     * @ORM\JoinTable(name="dossier_has_complement",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="complement_id", referencedColumnName="id")
     *   }
     * )
     */
    private $complementDossier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Commentaire", mappedBy="dossier")
     */
    private $commentaireDossier;

    /**
     * @var string
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide.",
     * )
     *
     */
    private $mailUserOfTransfert;

    /**
     * @var TypeBadge
     *
     * @ORM\ManyToOne(targetEntity="TypeBadge")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="type_badge_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $typeBadge;

    /**
     * @var MotifDemande
     *
     * @ORM\ManyToOne(targetEntity="MotifDemande")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="motif_demande_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $motifDemande;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoriqueDossier", mappedBy="dossier", orphanRemoval=true)
     */
    private $historiqueDossier;

    /**
     * @var string
     *
     * @ORM\Column(name="duree_validite", type="string", length=255, nullable=true)
     */
    private $dureeValidite;

    /**
     * @var string
     *
     * @ORM\Column(name="dispositions", type="text", length=255, nullable=true)
     */
    private $dispositions;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Equipement", inversedBy="dossier", cascade={"persist", "merge","detach"})
     * @ORM\JoinTable(name="dossier_has_epi",
     * joinColumns={
     * @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", onDelete="CASCADE")
     * },
     * inverseJoinColumns={
     * @ORM\JoinColumn(name="equipement_id", referencedColumnName="id")
     * }
     * )
     */
    private $epi;

    /**
     * @var TypeContrat
     *
     * @ORM\ManyToOne(targetEntity="TypeContrat")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="type_contrat_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $typeContrat;

    /**
     * @var array
     *
     * @ORM\Column(name="beneficiaire", type="array", nullable=true)
     */
    private $beneficiaire;

    /**
     * @var NiveauAcces
     *
     * @ORM\ManyToOne(targetEntity="NiveauAcces")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="niveau_Acces_id", referencedColumnName="id", onDelete="CASCADE")
     *  })
     */
    private $niveauAcces; 

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire_rejet", type="text", length=255, nullable=true)
     */
    private $commentaireRejet;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SuiviDelai", mappedBy="dossier", orphanRemoval=true)
     */
    private $suiviDelai;

    /**
     * @var string
     *
     * @ORM\Column(name="structure_beneficiaire", type="string", nullable=true)
     */
    private $structureBeneficiaire;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire_autorisation", type="text", length=255, nullable=true)
     */
    private $commentaireAutorisation;

    /**
     * @var string
     *
     * @ORM\Column(name="analyse", type="text", length=255, nullable=true)
     */
    private $analyse;

    /**
     * @var string
     *
     * @ORM\Column(name="mesure_corrective", type="text", length=255, nullable=true)
     */
    private $MesureCorrective;

    /**
     * @Assert\File(maxSize="6000000", mimeTypes = {"application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","text/csv"}, mimeTypesMessage = "Le format du fichier n'est pas correct")
     * @var UploadedFile
     */
    public $fileBeneficiaires;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $agentExecution;

    /**
     * @ORM\ManyToOne(targetEntity=MotifRemplacement::class, inversedBy="dossiers")
     */
    private $motifRemplacement;

    /**
     * @ORM\ManyToOne(targetEntity=ObjetBadge::class)
     */
    private $objetBadge;

    /**
     * @ORM\OneToMany(targetEntity=BeneficiaireQrcode::class, mappedBy="dossier")
     */
    private $beneficiaireQrcodes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $choixAutorisation = true;


    public function __construct()
    {
        //$this->historiqueDossier = new ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateAjout = new \DateTime();
        $this->complementDossier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->beneficiaireQrcodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

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

    public function getCoutDossier(): ?int
    {
        return $this->coutDossier;
    }

    public function setCoutDossier(int $coutDossier): self
    {
        $this->coutDossier = $coutDossier;

        return $this;
    }

    /**
     * @return Structure
     */
    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     */
    public function setStructure(Structure $structure): void
    {
        $this->structure = $structure;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getDateAjout(): \DateTime
    {
        return $this->dateAjout;
    }

    /**
     * @param \DateTime $dateAjout
     */
    public function setDateAjout(\DateTime $dateAjout): void
    {
        $this->dateAjout = $dateAjout;
    }

    /**
     * @return int
     */
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @return Site
     */
    public function getSite(): ?Site
    {
        return $this->site;
    }

    /**
     * @param Site $site
     */
    public function setSite(Site $site): void
    {
        $this->site = $site;
    }


    /**
     * @return ArrayCollection
     */
    public function getSites(): ?ArrayCollection
    {
        return $this->sites;
    }

    /**
     * @param mixed $sites
     */
    public function setSites($sites): void
    {
        $this->sites = $sites;
    }

    /**
     * @return Collection
     */
    public function getSiteAutorisation(): ?Collection
    {
        return $this->siteAutorisation;
    }

    /**
     * Add siteAutorisation
     *
     * @param Site $siteAutorisation
     * @return Dossier
     */
    public function addSiteAutorisation(Site $siteAutorisation)
    {
        /*if ($this->siteAutorisation && !$this->siteAutorisation->contains($siteAutorisation)) {
            $this->siteAutorisation[] = $siteAutorisation;
        }
        elseif (!$this->siteAutorisation){
            $this->siteAutorisation[] = $siteAutorisation;
        }*/
        $this->siteAutorisation[] = $siteAutorisation;

        return $this;
    }

    /**
     * Remove siteAutorisation
     *
     * @param Site $siteAutorisation
     */
    public function removeSiteAutorisation(Site $siteAutorisation)
    {
        $this->siteAutorisation->removeElement($siteAutorisation);
    }

    /**
     * @return TypeDossier
     */
    public function getTypeDossier(): ?TypeDossier //? ajouté pour éviter l'erreur  must be an instancenof TypeDossier, null returned
    {
        return $this->typeDossier;
    }

    /**
     * @param TypeDossier $typeDossier
     */
    public function setTypeDossier(TypeDossier $typeDossier): void
    {
        $this->typeDossier = $typeDossier;
    }

    /**
     * @return TypeMateriel
     */
    public function getTypeMateriel(): ?TypeMateriel
    {
        return $this->typeMateriel;
    }

    /**
     * @param TypeMateriel $typeMateriel
     */
    public function setTypeMateriel(TypeMateriel $typeMateriel): void
    {
        $this->typeMateriel = $typeMateriel;
    }

    /**
     * @return string
     */
    public function getAutreMateriel(): ?string
    {
        return $this->autreMateriel;
    }

    /**
     * @param string $autreMateriel
     */
    public function setAutreMateriel(string $autreMateriel): void
    {
        $this->autreMateriel = $autreMateriel;
    }

    /**
     * @return Etat
     */
    public function getEtat(): Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat $etat
     */
    public function setEtat(Etat $etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return Collection
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    /**
     * Add user
     *
     * @param User $user
     * @return Dossier
     */
    public function addUser(User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        //$this->user = $user;
        if ($this->user->contains($user)) {
            return;
        }
        $this->user[] = $user;

        //return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * @return string
     */
    public function getCodeDossier(): string
    {
        return $this->codeDossier;
    }

    /**
     * @param string $codeDossier
     */
    public function setCodeDossier(string $codeDossier): void
    {
        $this->codeDossier = $codeDossier;
    }

    /**
     * @return string
     */
    public function getCodeSecret(): string
    {
        return $this->codeSecret;
    }

    /**
     * @param string $codeSecret
     */
    public function setCodeSecret(string $codeSecret): void
    {
        $this->codeSecret = $codeSecret;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return Collection
     */
    public function getComplementDossier(): Collection
    {
        return $this->complementDossier;
    }

    /**
     * Add complementDossier
     *
     * @param ComplementDossier $complementDossier
     * @return Dossier
     */
    public function addComplementDossier(ComplementDossier $complementDossier)
    {
        $this->complementDossier[] = $complementDossier;

        return $this;
    }

    /**
     * Remove complementDossier
     *
     * @param ComplementDossier $complementDossier
     */
    public function removeComplementDossier(ComplementDossier $complementDossier)
    {
        $this->complementDossier->removeElement($complementDossier);
    }

    /**
     * @return Collection
     */
    public function getCommentaireDossier(): Collection
    {
        return $this->commentaireDossier;
    }

    /**
     * @param Collection $commentaireDossier
     */
    public function setCommentaireDossier(Collection $commentaireDossier): void
    {
        $this->commentaireDossier = $commentaireDossier;
    }

    /**
     * @return string
     */
    public function getMailUserOfTransfert(): ?string
    {
        return $this->mailUserOfTransfert;
    }

    /**
     * @param string $mailUserOfTransfert
     */
    public function setMailUserOfTransfert(string $mailUserOfTransfert): void
    {
        $this->mailUserOfTransfert = $mailUserOfTransfert;
    }



    /**
     * @return Collection|HistoriqueDossier[]
     */
    public function getHistoriqueDossier(): Collection
    {
        return $this->historiqueDossier;
    }

    public function addHistoriqueDossier(HistoriqueDossier $historiqueDossier): self
    {
        if (!$this->historiqueDossier->contains($historiqueDossier)) {
            $this->historiqueDossier[] = $historiqueDossier;
            $historiqueDossier->setDossier($this);
        }

        return $this;
    }

    public function removeHistoriqueDossier(HistoriqueDossier $historiqueDossier): self
    {
        if ($this->historiqueDossier->contains($historiqueDossier)) {
            $this->historiqueDossier->removeElement($historiqueDossier);
            // set the owning side to null (unless already changed)
            if ($historiqueDossier->getDossier() === $this) {
                $historiqueDossier->setDossier(null);
            }
        }

        return $this;
    }

    /**
     * @return TypeBadge
     */
    public function getTypeBadge(): ?TypeBadge
    {
        return $this->typeBadge;
    }

    /**
     * @param TypeBadge $typeBadge
     */
    public function setTypeBadge(TypeBadge $typeBadge): void
    {
        $this->typeBadge = $typeBadge;
    }

    /**
     * @return MotifDemande
     */
    public function getMotifDemande(): ?MotifDemande
    {
        return $this->motifDemande;
    }

    /**
     * @param MotifDemande $motifDemande
     */
    public function setMotifDemande(MotifDemande $motifDemande): void
    {
        $this->motifDemande = $motifDemande;
    }

    /**
     * @return string
     */
    public function getDureeValidite(): ?string
    {
        return $this->dureeValidite;
    }

    /**
     * @param string $dureeValidite
     */
    public function setDureeValidite(string $dureeValidite): void
    {
        $this->dureeValidite = $dureeValidite;
    }

    /**
     * @return string
     */
    public function getDispositions(): ?string
    {
        return $this->dispositions;
    }

    /**
     * @param string $dispositions
     */
    public function setDispositions(string $dispositions): void
    {
        $this->dispositions = $dispositions;
    }

    /**
     * @return Collection
     */
    public function getEpi(): ? Collection
    {
        return $this->epi;
    }

    /**
 * Add Equipement
 *
 * @param Equipement $epi
 * @return Dossier
 */
    public function addEPi(Equipement $epi)
    {
        $this->epi[] = $epi;

        return $this;
    }

    /**
     * @param Collection $epi
     */
    public function setEpi(Collection $epi): void
    {
        $this->epi = $epi;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut(): ? \DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut(?\DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin(): ? \DateTime
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin(?\DateTime $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function __toString()
    {
        return $this->libelle."  ".$this->site ;
    }

    /**
     * @return TypeContrat
     */
    public function getTypeContrat(): ? TypeContrat
    {
        return $this->typeContrat;
    }

    /**
     * @param TypeContrat $typeContrat
     */
    public function setTypeContrat(TypeContrat $typeContrat): void
    {
        $this->typeContrat = $typeContrat;
    }

    /**
     * @return array
     */
    public function getBeneficiaire(): ?array
    {
        //return $this->beneficiaire;
        return $this->beneficiaire;
    }

    /**
     * @param array $beneficiaire
     */
    public function setBeneficiaire(array $beneficiaire): void
    {
        $this->beneficiaire = $beneficiaire;
    }

    /**
     * Add beneficiaire
     * @param $beneficiaire
     * @return Dossier
     */
    public function addBeneficiaire($beneficiaire)
    {
        $this->beneficiaire[] = $beneficiaire;

        return $this;
    }

   
    /**
     * @return NiveauAcces
     */
    public function getNiveauAcces(): ? NiveauAcces
    {
        return $this->niveauAcces;
    }

    /**
     * @param NiveauAcces $niveauAcces
     */
    public function setNiveauAcces(NiveauAcces $niveauAcces): void
    {
        $this->niveauAcces = $niveauAcces;
    }

    /**
     * @return mixed
     */
    public function getNomBeneficiaire()
    {
        return $this->nomBeneficiaire;
    }

    /**
     * @param mixed $nomBeneficiaire
     */
    public function setNomBeneficiaire($nomBeneficiaire): void
    {
        $this->nomBeneficiaire = $nomBeneficiaire;
    }

    /**
     * @return mixed
     */
    public function getPrenomBeneficiaire()
    {
        return $this->prenomBeneficiaire;
    }

    /**
     * @param mixed $prenomBeneficiaire
     */
    public function setPrenomBeneficiaire($prenomBeneficiaire): void
    {
        $this->prenomBeneficiaire = $prenomBeneficiaire;
    }

    /**
     * @return mixed
     */
    public function getMatriculeBeneficiaire()
    {
        return $this->matriculeBeneficiaire;
    }

    /**
     * @param mixed $matriculeBeneficiaire
     */
    public function setMatriculeBeneficiaire($matriculeBeneficiaire): void
    {
        $this->matriculeBeneficiaire = $matriculeBeneficiaire;
    }

    /**
     * @return Site
     */
    public function getSiteBeneficiaire(): ?Site
    {
        return $this->siteBeneficiaire;
    }

    /**
     * @param Site $siteBeneficiaire
     */
    public function setSiteBeneficiaire(Site $siteBeneficiaire): void
    {
        $this->siteBeneficiaire = $siteBeneficiaire;
    }

    /**
     * @return User
     */
    public function getValidateur(): ?User
    {
        return $this->validateur;
    }

    /**
     * @param User $validateur
     */
    public function setValidateur(User $validateur): void
    {
        $this->validateur = $validateur;
    }

    /**
     * @return string
     */
    public function getCommentaireRejet(): ?string
    {
        return $this->commentaireRejet;
    }

    /**
     * @param string $commentaireRejet
     */
    public function setCommentaireRejet(?string $commentaireRejet): void
    {
        $this->commentaireRejet = $commentaireRejet;
    }

    /**
     * @return mixed
     */
    public function getSuiviDelai()
    {
        return $this->suiviDelai;
    }

    /**
     * @param mixed $suiviDelai
     */
    public function setSuiviDelai($suiviDelai): void
    {
        $this->suiviDelai = $suiviDelai;
    }

    /**
     * @return string
     */
    public function getStructureBeneficiaire(): ?string
    {
        return $this->structureBeneficiaire;
    }

    /**
     * @param string $structureBeneficiaire
     */
    public function setStructureBeneficiaire(string $structureBeneficiaire): void
    {
        $this->structureBeneficiaire = $structureBeneficiaire;
    }

    /**
     * @return string
     */
    public function getCommentaireAutorisation(): ?string
    {
        return $this->commentaireAutorisation;
    }

    /**
     * @param string $commentaireAutorisation
     */
    public function setCommentaireAutorisation(string $commentaireAutorisation): void
    {
        $this->commentaireAutorisation = $commentaireAutorisation;
    }

    /**
     * @return string
     */
    public function getAnalyse(): ?string
    {
        return $this->analyse;
    }

    /**
     * @param string $analyse
     */
    public function setAnalyse(string $analyse): void
    {
        $this->analyse = $analyse;
    }

    /**
     * @return string
     */
    public function getMesureCorrective(): ?string
    {
        return $this->MesureCorrective;
    }

    /**
     * @param string $MesureCorrective
     */
    public function setMesureCorrective(string $MesureCorrective): void
    {
        $this->MesureCorrective = $MesureCorrective;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($fileBeneficiaires = $this->getAbsolutePath()){
            unlink($fileBeneficiaires);
        }
    }

    /**
     * @return UploadedFile
     */
    public function getFileBeneficiaires(): ?UploadedFile
    {
        return $this->fileBeneficiaires;
    }

    /**
     * @param UploadedFile $fileBeneficiaires
     */
    public function setFileBeneficiaires(UploadedFile $fileBeneficiaires): void
    {
        $this->fileBeneficiaires = $fileBeneficiaires;
    }

    public function getAgentExecution(): ?User
    {
        return $this->agentExecution;
    }

    public function setAgentExecution(?User $agentExecution): self
    {
        $this->agentExecution = $agentExecution;

        return $this;
    }

    public function getMotifRemplacement(): ?MotifRemplacement
    {
        return $this->motifRemplacement;
    }

    public function setMotifRemplacement(?MotifRemplacement $motifRemplacement): self
    {
        $this->motifRemplacement = $motifRemplacement;

        return $this;
    }

    public function getObjetBadge(): ?ObjetBadge
    {
        return $this->objetBadge;
    }

    public function setObjetBadge(?ObjetBadge $objetBadge): self
    {
        $this->objetBadge = $objetBadge;

        return $this;
    }

    /**
     * @return Collection<int, BeneficiaireQrcode>
     */
    public function getBeneficiaireQrcodes(): Collection
    {
        return $this->beneficiaireQrcodes;
    }

    public function addBeneficiaireQrcode(BeneficiaireQrcode $beneficiaireQrcode): self
    {
        if (!$this->beneficiaireQrcodes->contains($beneficiaireQrcode)) {
            $this->beneficiaireQrcodes[] = $beneficiaireQrcode;
            $beneficiaireQrcode->setDossier($this);
        }

        return $this;
    }

    public function removeBeneficiaireQrcode(BeneficiaireQrcode $beneficiaireQrcode): self
    {
        if ($this->beneficiaireQrcodes->removeElement($beneficiaireQrcode)) {
            // set the owning side to null (unless already changed)
            if ($beneficiaireQrcode->getDossier() === $this) {
                $beneficiaireQrcode->setDossier(null);
            }
        }

        return $this;
    }

    public function getChoixAutorisation(): ?bool
    {
        return $this->choixAutorisation;
    }

    public function setChoixAutorisation(bool $choixAutorisation): self
    {
        $this->choixAutorisation = $choixAutorisation;

        return $this;
    }



}
