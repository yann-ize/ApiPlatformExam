<?php

namespace App\Command;

use App\Services\ImportDepartementsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'import:departements',
    description: 'Import des départements par région de France',
)]
class ImportDepartementsCommand extends Command
{

    public function __construct(
        private readonly ImportDepartementsService $service
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->service->importDepartements($io);


        return Command::SUCCESS;
    }
}
