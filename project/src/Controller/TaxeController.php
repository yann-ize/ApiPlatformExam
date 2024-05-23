<?php

namespace App\Controller;

use App\Service\TaxeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaxeController extends AbstractController
{
    private TaxeService $taxeService;
    public function __construct(TaxeService $taxeService)
    {
        $this->taxeService = $taxeService;
    }

    #[Route('/api/taxe_fonciere', name: 'calculer_taxe_fonciere', methods: ['POST'])]
    public function calculerTaxeFoncier(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['surfaceHabitable']) || !isset($data['prixM2'])) {
            return $this->json(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }
        $surfaceHabitable = (float) $data['surfaceHabitable'];
        $prixM2 = (float) $data['prixM2'];
        $valeurCadastrale = $surfaceHabitable * $prixM2;
        $taxeFoncier = $this->taxeService->calculerTaxeFoncier($valeurCadastrale);
        return $this->json(['taxe_fonciere' => $taxeFoncier]);
    }

    #[Route('/api/taxe_ordures', name: 'calculer_taxe_ordures', methods: ['POST'])]
    public function calculerTaxeEnlevementOrduresMenageres(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['valeurLocativeCadastrale'])) {
            return $this->json(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }
        $valeurLocativeCadastrale = (float) $data['valeurLocativeCadastrale'];
        $taxeOrdures = $this->taxeService->calculerTaxeEnlevementOrduresMenageres($valeurLocativeCadastrale);
        return $this->json(['taxe_ordures' => $taxeOrdures]);
    }
}
