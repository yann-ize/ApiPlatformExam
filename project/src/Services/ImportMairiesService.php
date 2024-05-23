<?php

namespace App\Services;

use App\Entity\Mairie;
use App\Repository\DepartementRepository;
use App\Repository\MairieRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\Reader;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportMairiesService
{


    public function __construct(
        private readonly MairieRepository $repository,
        private readonly DepartementRepository $departementRepository,
        private readonly EntityManagerInterface $em,
        private readonly string $rootDir
    )
    {
    }

    public function importMairies(SymfonyStyle $io){
        $io->title('Importation des mairies');
        $mairies = $this->readCsvFile();

        $io->progressStart(count($mairies));
        foreach ($mairies as $arrayMairie){
            $io->progressAdvance();
            $mairie = $this->createOrUpdateMairie($arrayMairie);
            $this->em->persist($mairie);
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
        $csv = Reader::createFromPath($this->rootDir.$_ENV["MAIRIES_FILE_PATH"],'r');
        $csv->setHeaderOffset(0);
        return $csv;
    }

    private function createOrUpdateMairie(array $array): Mairie
    {

        $mairie = $this->repository->findOneBy(["codeInsee"=>$array["codeInsee"]]);

        $numeroDepartement = str_starts_with($array["codeInsee"],"97") || str_starts_with($array["codeInsee"],"98")
            ? substr($array["codeInsee"],0,3)
            : substr($array["codeInsee"],0,2);

        $departement = $this->departementRepository->findOneBy(["numero"=>$numeroDepartement]);
        if(!$mairie){$mairie = new Mairie();}

        $telephone = str_replace([" ","(0)"],"",$array["Téléphone"]);
        $telephone = str_replace("+33","0",$telephone);

        $mairie->setCodeInsee($array["codeInsee"])
            ->setCodePostal($array["CodePostal"])
            ->setLabel($array["NomOrganisme"])
            ->setVille($array["NomCommune"])
            ->setTelephone($telephone)
            ->setSiteWeb($array["Url"])
            ->setEmail($array["Email"])
            ->setAdresse($array["Adresse"])
            ->setLatitude($array["Latitude"])
            ->setLongitude($array["Longitude"])
            ->setDateMaj(new \DateTimeImmutable($this->handleMultiDate($array["dateMiseAJour"])))
            ->setDepartement($departement);
        return $mairie;
    }

    private function handleMultiDate(string $dateString): string
    {
        $dateArr = explode(" ",$dateString);
        return count($dateArr) > 1 ? $dateArr[1] : $dateString;
    }
}
