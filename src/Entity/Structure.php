<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Structure
 *
 * @Gedmo\Mapping\Annotation\Tree(type="nested")
 * @ORM\Table(name="structure", indexes={@ORM\Index(name="fk_structure_type_structure1_idx", columns={"type_structure_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\StructureRepository")
 * @UniqueEntity(fields="libelle", message="Le libelle de la structure est unique")
 */
class Structure
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
     * @Gedmo\Mapping\Annotation\TreeLeft
     * @ORM\Column(name="lft", type="integer", nullable=true)
     */
    private $lft;

    /**
     * @Gedmo\Mapping\Annotation\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    private $lvl;

    /**
     * @Gedmo\Mapping\Annotation\TreeRight
     * @ORM\Column(name="rgt", type="integer", nullable=true)
     */
    private $rgt;

    /**
     * @Gedmo\Mapping\Annotation\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\Mapping\Annotation\TreeParent
     * @ORM\ManyToOne(targetEntity="Structure", inversedBy="children")
     * @ORM\JoinColumn(name="pere_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $pere;

    /**
     * @ORM\OneToMany(targetEntity="Structure", mappedBy="pere")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var TypeStructure
     *
     * @ORM\ManyToOne(targetEntity="TypeStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_structure_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     * @Assert\NotNull(message="Veuillez choisir le type de structure")
     */
    private $typeStructure;


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

    public function getLft(): ?int
    {
        return $this->lft;
    }

    public function setLft(int $lft): self
    {
        $this->lft = $lft;

        return $this;
    }

    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;

        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRgt(int $rgt): self
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Structure
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Add children
     *
     * @param Structure $children
     * @return Structure
     */
    public function addChildren(Structure $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Structure $children
     */
    public function removeChildren(Structure $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set pere
     *
     * @param Structure $pere
     * @return Structure
     */
    public function setPere(Structure $pere = null)
    {
        $this->pere = $pere;

        return $this;
    }

    /**
     * Get pere
     *
     * @return Structure
     */
    public function getPere()
    {
        return $this->pere;
    }

    /**
     * @return TypeStructure
     */
    public function getTypeStructure(): ?TypeStructure
    {
        return $this->typeStructure;
    }

    /**
     * @param TypeStructure $typeStructure
     */
    public function setTypeStructure(TypeStructure $typeStructure): void
    {
        $this->typeStructure = $typeStructure;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function __toString()
    {
        $object = $this;
        $libelle = null;
        if($object->getLvl() != 0) {
            while($object->getLvl() != 0) {
                $libelle = ' \ '.$object->getLibelle().$libelle;
                $object = $object->getPere();
            }
        } else {
            $libelle = $object->getLibelle();
        }
        return $libelle;
    }
}
