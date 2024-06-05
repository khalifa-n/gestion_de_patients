<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class PatientRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public $nom;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public $numeroPatient;
    #[Assert\NotBlank]
    public $prenom;

    #[Assert\NotBlank]
    public $adresse;

    #[Assert\NotBlank]
    public $telephone;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 120)]
    public $age;

    public function __construct(array $data = [])
    {
        $this->setData($data);
    }
    public function setData(array $data)
    {
        $this->numeroPatient = $data['numeroPatient'] ?? null;
        $this->nom = $data['nom'] ?? null;
        $this->prenom = $data['prenom'] ?? null;
        $this->adresse = $data['adresse'] ?? null;
        $this->telephone = $data['telephone'] ?? null;
        $this->age = $data['age'] ?? null;
    }
}

