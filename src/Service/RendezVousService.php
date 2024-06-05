<?php

namespace App\Service;

use App\Entity\Patient;
use App\Entity\RendezVous;
use App\DTO\RendezVousRequest;
use App\DTO\RendezVousResponse;
use App\Repository\RendezVousRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Exception\NotFoundException;


class RendezVousService
{
    private EntityManagerInterface $entityManager;
    private RendezVousRepository $rendezVousRepository;
    private PatientRepository $patientRepository;
    private ValidatorInterface $validator;
    private RendezVousResponse $rendezVousResponse;
    private RendezVous $rendezVous;
    private NotFoundException $notFoundException;


    public function __construct(
        EntityManagerInterface $entityManager,
        RendezVousRepository $rendezVousRepository,
        PatientRepository $patientRepository,
        ValidatorInterface $validator,
        RendezVousResponse $rendezVousResponse,
        RendezVous $rendezVous,
        NotFoundException $notFoundException
    ) {
        $this->entityManager = $entityManager;
        $this->rendezVousRepository = $rendezVousRepository;
        $this->patientRepository = $patientRepository;
        $this->validator = $validator;
        $this->rendezVousResponse = $rendezVousResponse;
        $this->rendezVous = $rendezVous;
        $this->notFoundException = $notFoundException;
    }

    public function getAllRendezVous(): array
    {
        $rendezVousList = $this->rendezVousRepository->findAll();
        $data = [];
        foreach ($rendezVousList as $rendezVous) {
            $data[] = $this->rendezVousResponse->fromEntity($rendezVous);
        }
        return $data;
    }

    public function createRendezVous(RendezVousRequest $request): array
    {
        $this->entityManager->beginTransaction();

        try {
            $rendezVous = $this->rendezVous;
            $rendezVous = $this->populateRendezVous($rendezVous, $request);
            $this->validateAndPersist($rendezVous);
            $this->entityManager->commit();
            return ['rendezVous' => $this->rendezVousResponse->fromEntity($rendezVous)];
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function updateRendezVous(int $id, RendezVousRequest $request): array
    {
        $this->entityManager->beginTransaction();

        try {
            $rendezVous = $this->findRendezVousById($id);
            $rendezVous = $this->populateRendezVous($rendezVous, $request);
            $this->validateAndPersist($rendezVous);
            $this->entityManager->commit();
            return ['rendezVous' => $this->rendezVousResponse->fromEntity($rendezVous)];
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function deleteRendezVous(int $id): void
    {
        $this->entityManager->beginTransaction();

        try {
            $rendezVous = $this->findRendezVousById($id);
            $this->entityManager->remove($rendezVous);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
    public function cancelRendezVous(int $id): array
    {
        $this->entityManager->beginTransaction();
    
        try {
            $rendezVous = $this->findRendezVousById($id);
            $rendezVous->setEtat('annuler');
            $this->validateAndPersist($rendezVous);
            $this->entityManager->commit();
            return ['rendezVous' => $this->rendezVousResponse->fromEntity($rendezVous)];
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    private function populateRendezVous(RendezVous $rendezVous, RendezVousRequest $request): RendezVous
    {
        
        $rendezVous->setNumeroRendezVous($this->generateNumeroRendezVous());
        $rendezVous->setDate(new \DateTime($request->date));
        $rendezVous->setPatient($this->findPatientById($request->patientId));
        $rendezVous->setType($request->type);
        $rendezVous->setEtat($request->etat);

        return $rendezVous;
    }
    private function findRendezVousById(int $id): RendezVous
    {
        $rendezVous = $this->rendezVousRepository->find($id);
        if (!$rendezVous) {
            throw new NotFoundHttpException("Rendez-vous not found");
        }
        return $rendezVous;
    }
    private function validateAndPersist(RendezVous $rendezVous): void
    {
        $errors = $this->validator->validate($rendezVous);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }
        $this->entityManager->persist($rendezVous);
        $this->entityManager->flush();
    }
    private function findPatientById(int $id): Patient
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            throw $this->notFoundException;
        }
        return $patient;
    }
    private function generateNumeroRendezVous(): string
    {
        $timestamp = date('dmyHis');
        return 'DOS-' . $timestamp;
    }
}
