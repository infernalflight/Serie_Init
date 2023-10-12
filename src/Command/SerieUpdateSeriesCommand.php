<?php

namespace App\Command;

use App\Helper\Updater;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'serie:update-series',
    description: 'Met à jour le catalogue',
)]
class SerieUpdateSeriesCommand extends Command
{
    public function __construct(private Updater $updater)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('genre', InputArgument::OPTIONAL, 'Seules les séries du genre passé en paramètre seront mises à jour')
            ->addOption('force', 'f', InputOption::VALUE_NONE,'Effectue réellement la mise à jour')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('genre');
        $opt1 = $input->getOption('force');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $io->note(sprintf('You passed an option: %s', $opt1));

        /**
        $io->text('Bonjour');

        $reponse = $io->ask('Comment ça va ?', 'Pas super');

        $reponse2 = $io->confirm('Etes-vous sûr de votre réponse ?', false);

        $reponse3 = $io->choice("Quel parfum pour la glace ?", ['Vanille', 'fraise', 'pistache']);

        $io->error("Y'en a plus");

        $io->writeln("Ah en fait si !");

        $io->success('Tout s\'est bien passé');
**/

        $count = $this->updater->removeOldSeries($arg1, $opt1);

        if ($opt1) {
            $io->success('Nombre de séries supprimées : ' . $count);
        } else {
            $io->warning('Nombre de séries pouvant être supprimées : ' . $count);
        }


        return Command::SUCCESS;
    }
}
