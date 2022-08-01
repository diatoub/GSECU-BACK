<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="type_dossier", indexes={@ORM\Index(name="fk_type_dossier_categorie_dossier_idx", columns={"categorie_dossier_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\TypeDossierRepository")
 */
class TypeDossier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbre_jours_livraison", type="integer", nullable=true)
     * @Assert\NotNull(message="Vous devez définir le délai de traitement pour ce type de dossier")
     * @Assert\Type(type="integer", message="La valeur {{ value }} entrée n'est pas un entier valide !")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Merci d'entrer un entier positif"
     *      )
     */
    private $nbreJoursLivraison;

    /**
     * @var CategorieDossier
     *
     * @ORM\ManyToOne(targetEntity="CategorieDossier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_dossier_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     * @Assert\NotNull(message="Veuillez choisir une catégorie pour ce type de dossier")
     */
    private $categorieDossier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TypeMateriel", inversedBy="typeDossier", cascade={"persist", "detach"})
     * @ORM\JoinTable(name="typedossier_has_typemateriel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="typedossier_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="typemateriel_id", referencedColumnName="id")
     *   }
     * )
     */
    private $typeMateriel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->typeMateriel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Add typeMateriel
     *
     * @param TypeMateriel $typeMateriel
     * @return TypeDossier
     */
    public function addTypeMateriel(TypeMateriel $typeMateriel)
    {
        $this->typeMateriel[] = $typeMateriel;

        return $this;
    }

    /**
     * Remove typeMateriel
     *
     * @param TypeMateriel $typeMateriel
     */
    public function removeTypeMateriel(TypeMateriel $typeMateriel)
    {
        $this->typeMateriel->removeElement($typeMateriel);
    }

    /**
     * Get typeMateriel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeMateriel()
    {
        return $this->typeMateriel;
    }

    /**
     * Set nbreJoursLivraison
     *
     * @param integer $nbreJoursLivraison
     * @return TypeDossier
     */
    public function setNbreJoursLivraison($nbreJoursLivraison)
    {
        $this->nbreJoursLivraison = $nbreJoursLivraison;

        return $this;
    }

    /**
     * Get nbreJoursLivraison
     *
     * @return integer
     */
    public function getNbreJoursLivraison()
    {
        return $this->nbreJoursLivraison;
    }

    /**
     * Set categorieDossier
     *
     * @param CategorieDossier $categorieDossier
     * @return TypeDossier
     */
    public function setCategorieDossier(CategorieDossier $categorieDossier = null)
    {
        $this->categorieDossier = $categorieDossier;

        return $this;
    }

    /**
     * Get categorieDossier
     *
     * @return CategorieDossier
     */
    public function getCategorieDossier()
    {
        return $this->categorieDossier;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
