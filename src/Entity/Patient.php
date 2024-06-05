<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Nom is required")]

    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?int $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\OneToOne(mappedBy: 'patient', cascade: ['persist', 'remove'])]
    private ?DossierMedical $dossierMedical = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class, cascade: ['persist', 'remove'])]
    private Collection $rendezVous;

    #[ORM\Column(length: 255)]
    private ?string $numeroPatient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getDossierMedical(): ?DossierMedical
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?DossierMedical $dossierMedical): static
    {
        $this->dossierMedical = $dossierMedical;

        return $this;
    }

   

    public function getNumeroPatient(): ?string
    {
        return $this->numeroPatient;
    }

    public function setNumeroPatient(string $numeroPatient): static
    {
        $this->numeroPatient = $numeroPatient;

        return $this;
    }
    public function getRendezVous(): Collection {
        return $this->rendezVous;
    }
    public function setRendezVous(Collection $rendezVous): self
{
    $this->rendezVous = $rendezVous;

    // Explicitly set the patient for each RendezVous in the collection
    // (assuming a setter like `setPatient` exists in RendezVous)
    foreach ($rendezVous as $rendezVousObject) {
        $rendezVousObject->setPatient($this);
    }

    return $this;
}
}
