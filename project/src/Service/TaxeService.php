<?php

namespace App\Service;

class TaxeService
{
    public function calculerTaxeFoncier(float $valeurCadastrale): float
    {
        return $valeurCadastrale * 0.005;
    }

    public function calculerTaxeEnlevementOrduresMenageres(float $valeurLocativeCadastrale): float
    {
        return ($valeurLocativeCadastrale / 2) * 0.0937;
    }
}
