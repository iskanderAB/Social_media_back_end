<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatRepository::class)
 */
class Stat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NMR;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $year_recruitment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companySkills;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $yourSkills;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNMR(): ?int
    {
        return $this->NMR;
    }

    public function setNMR(?int $NMR): self
    {
        $this->NMR = $NMR;

        return $this;
    }

    public function getYearRecruitment(): ?\DateTimeInterface
    {
        return $this->year_recruitment;
    }

    public function setYearRecruitment(?\DateTimeInterface $year_recruitment): self
    {
        $this->year_recruitment = $year_recruitment;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCompanySkills(): ?string
    {
        return $this->companySkills;
    }

    public function setCompanySkills(?string $companySkills): self
    {
        $this->companySkills = $companySkills;

        return $this;
    }

    public function getYourSkills(): ?string
    {
        return $this->yourSkills;
    }

    public function setYourSkills(?string $yourSkills): self
    {
        $this->yourSkills = $yourSkills;

        return $this;
    }


}
