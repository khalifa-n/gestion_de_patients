<?php

namespace App\Controller;
use App\DTO\DossierMedicalRequest;
use App\DTO\DossierMedicalResponse;
use App\Service\DossierMedicalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Exception\NotFoundException;

#[Route('/api/dossierMedical',name:'api_dossierMedical')]
class DossierMedicalController extends AbstractController
{
    private DossierMedicalService $dossierMedicalService;
    private DossierMedicalResponse $dossierMedicalResponse;
    private DossierMedicalRequest $dossierMedicalRequest;


    public function __construct
    (
        DossierMedicalService $dossierMedicalService,
        DossierMedicalResponse $dossierMedicalResponse,
        DossierMedicalRequest $dossierMedicalRequest
        
        )
    {
        $this->dossierMedicalService = $dossierMedicalService;
        $this->dossierMedicalResponse = $dossierMedicalResponse;
        $this->dossierMedicalRequest = $dossierMedicalRequest;

    }

    #[Route('/list', name: 'list_dossier_medical',methods:['GET'])]
    public function list(): JsonResponse
    {
        $dossierMedicals = $this->dossierMedicalService->getAllDossiersMedicals();
        $data = [];
        foreach ($dossierMedicals as $dossierMedical){
            $dossierMedicalResponseData = $this->dossierMedicalResponse->fromEntity($dossierMedical);
            $data[] = $dossierMedicalResponseData;
        }
        return $this->json($data);
      
    }
    #[Route('/add', name: 'add_dossier_medical', methods: ['POST'])]
    public function create(Request $request,DossierMedicalRequest $dossierMedicalRequest ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $dossierMedicalRequest->setData($data);

        $result = $this->dossierMedicalService->createDossierMedical($dossierMedicalRequest);

        if (isset($result['errors'])) {
            return $this->json(['errors' => $result['errors']], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Dossier médical créé avec succès.', 'dossier_medical' => $result['dossierMedical']], JsonResponse::HTTP_OK);
    }

    #[Route('/update/{id}', name: 'update_dossier_medical', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $dossierMedicalRequest = new DossierMedicalRequest($data);
        $result = $this->dossierMedicalService->updateDossierMedical($id, $dossierMedicalRequest);

        if (isset($result['errors'])) {
            return $this->json(['errors' => $result['errors']], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Dossier médical mis à jour avec succès.', 'dossier_medical' => $result['dossierMedical']], JsonResponse::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete_dossier_medical', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->dossierMedicalService->deleteDossierMedical($id);
            return $this->json(['message' => 'Dossier médical supprimé avec succès.'], JsonResponse::HTTP_OK);
        } catch (NotFoundException $e) {
            return $this->json(['message' => 'Dossier médical non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
