<?php

namespace App\DTO;

use App\Entity\Patient;

class PatientResponse
{
    public $id;
    public $numeroPatient;
    public $nom;
    public $prenom;
    public $adresse;
    public $telephone;
    public $age;

    public function __construct(Patient $patient)
    {
        $this->id = $patient->getId();
        $this->numeroPatient = $patient->getNumeroPatient();
        $this->nom = $patient->getNom();
        $this->prenom = $patient->getPrenom();
        $this->adresse = $patient->getAdresse();
        $this->telephone = $patient->getTelephone();
        $this->age = $patient->getAge();
    }
    public static function fromEntity($patient): self
    {
        return new self($patient);
    }
}
