<?php

namespace App\Entity;

use App\Repository\HistoriqueLectureQrcodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriqueLectureQrcodeRepository::class)
 */
class HistoriqueLectureQrcode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateLecture;

    /**
     * @ORM\ManyToOne(targetEntity=BeneficiaireQrcode::class, inversedBy="historiqueLectureQrcodes")
     */
    private $beneficiareQrcode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLecture(): ?\DateTimeInterface
    {
        return $this->dateLecture;
    }

    public function setDateLecture(\DateTimeInterface $dateLecture): self
    {
        $this->dateLecture = $dateLecture;

        return $this;
    }

    public function getBeneficiareQrcode(): ?BeneficiaireQrcode
    {
        return $this->beneficiareQrcode;
    }

    public function setBeneficiareQrcode(?BeneficiaireQrcode $beneficiareQrcode): self
    {
        $this->beneficiareQrcode = $beneficiareQrcode;

        return $this;
    }
}
