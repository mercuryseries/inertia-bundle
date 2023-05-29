<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'inertia:stop-ssr',
    description: 'Stop the Inertia SSR server',
)]
class StopInertiaSsrCommand extends Command
{
    public function __construct(
        #[Autowire('%mercuryseries_inertia_maker.ssr.url%')]
        private readonly string $ssrUrl
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $url = str_replace('/render', '/shutdown', $this->ssrUrl);

        $ch = curl_init($url);
        curl_exec($ch);

        if ('Empty reply from server' !== curl_error($ch)) {
            $io->error('Unable to connect to Inertia SSR server.');

            return Command::FAILURE;
        }

        $io->info('Inertia SSR server stopped.');

        curl_close($ch);

        return Command::SUCCESS;
    }
}
