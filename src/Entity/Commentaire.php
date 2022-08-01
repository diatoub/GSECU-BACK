<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @Assert\File(maxSize="6000000")
     * @var UploadedFile
     * @Assert\NotNull(message="Vous devez joindre la preuve !")
     */
    public $file;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Dossier", inversedBy="commentaire", cascade={"persist"})
     * @ORM\JoinTable(name="dossier_has_commentaire",
     *   joinColumns={
     *     @ORM\JoinColumn(name="commentaire_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="dossier_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $dossier;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dossier = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {

        return __DIR__.'/../../../../public/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploads()
    {
        if(null !== $this->file)
        {
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if(null === $this->file)
        {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($file = $this->getAbsolutePath()){
            unlink($file);
        }
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Commentaire
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getWebPath();
    }

    public function getTempPath() {
        return $this->file ? $this->file->getPathname() : null;
    }

    public function getDossier() {
        return $this->dossier;
    }
    public function setDossier($dossier) {
        $this->dossier = $dossier;
        return $this;
    }

    /**
     * Add dossier
     *
     * @param \App\Entity\Dossier $dossier
     * @return Commentaire
     */
    public function addDossier(\App\Entity\Dossier $dossier)
    {
        $this->dossier[] = $dossier;

        return $this;
    }

    /**
     * Remove dossier
     *
     * @param \App\Entity\Dossier $dossier
     */
    public function removeDossier(\App\Entity\Dossier $dossier)
    {
        $this->dossier->removeElement($dossier);
    }
}
