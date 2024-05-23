<?php

namespace App\Services;

use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\Reader;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDepartementsService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly string $rootDir
    )
    {
    }

    public function importDepartements(SymfonyStyle $io): void
    {
        $io->title('Importation des départements par region');
        $departements = $this->readCsvFile();


        $io->progressStart(count($departements));

        foreach ($departements as $arrayDepartement){
            $io->progressAdvance();
            $departement = $this->createDepartement($arrayDepartement);
            $this->em->persist($departement);
        }

        $this->em->flush();
        $io->progressFinish();
        $io->success("Importation terminée");
    }

    /**
     * @throws Exception
     */
    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath($this->rootDir.$_ENV["DEPARTEMENT_FILE_PATH"],'r');
        $csv->setHeaderOffset(0);
        return $csv;
    }

    private function createDepartement(array $array): Departement
    {

        $departement = new Departement();

        $departement->setLabel($array["dep_name"])
                    ->setNumero($array["num_dep"])
                    ->setRegion($array["region_name"]);
        return $departement;
    }

}
