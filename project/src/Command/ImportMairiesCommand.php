<?php

namespace App\Command;

use App\Services\ImportMairiesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'import:mairies',
    description: 'Import des mairies depuis un fichier source csv de data.gouv',
)]
class ImportMairiesCommand extends Command
{

    public function __construct(
        private ImportMairiesService $service
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->service->importMairies($io);
        return Command::SUCCESS;
    }
}
