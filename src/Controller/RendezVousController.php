<?php

namespace App\Controller;
use App\DTO\RendezVousRequest;
use App\DTO\RendezVousResponse;
use App\Service\RendezVousService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rendezVous', name: 'api_rendezvous')]
class RendezVousController extends AbstractController
{
    private RendezVousService $rendezVousService;
    private RendezVousRequest $rendezVousRequest;
    private RendezVousResponse $rendezVousResponse;

    public function __construct(
        RendezVousService $rendezVousService,
        RendezVousRequest $rendezVousRequest,
        RendezVousResponse $rendezVousResponse
        )
    {
        $this->rendezVousService = $rendezVousService;
        $this->rendezVousRequest = $rendezVousRequest;
        $this->rendezVousResponse = $rendezVousResponse;
    }

    #[Route('/list', name: 'list_rendezvous', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $data = $this->rendezVousService->getAllRendezVous();
        return $this->json($data);
    }

    #[Route('/add', name: 'add_rendezvous', methods: ['POST'])]
    public function create(Request $request,RendezVousRequest $rendezVousRequest): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $rendezVousRequest->setData($data);
        $result = $this->rendezVousService->createRendezVous($rendezVousRequest);

        if (isset($result['errors'])) {
            return $this->json(['errors' => $result['errors']], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Rendez-vous créé avec succès.', 'rendezvous' => $result['rendezVous']], JsonResponse::HTTP_OK);
    }
    #[Route('/update/{id}', name: 'update_rendezvous', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->rendezVousRequest->setData($data);
        $result = $this->rendezVousService->updateRendezVous($id, $this->rendezVousRequest);

        if (isset($result['errors'])) {
            return $this->json(['errors' => $result['errors']], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Rendez-vous mis à jour avec succès.', 'rendezvous' => $result['rendezVous']], JsonResponse::HTTP_OK);
    }
     
    #[Route('/cancel/{id}', name: 'cancel_rendezvous', methods: ['PUT'])]
    public function cancel(int $id, RendezVousService $rendezVousService): JsonResponse
    {
        try {
            $result = $rendezVousService->cancelRendezVous($id);
            return $this->json(['message' => 'Rendez-vous annulé avec succès.', 'rendezvous' => $result['rendezVous']], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
