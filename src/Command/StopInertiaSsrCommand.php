<?php

namespace MercurySeries\Bundle\InertiaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class StopInertiaSsrCommand extends Command
{
    private $ssrUrl;

    public function __construct(string $ssrUrl)
    {
        parent::__construct();
        $this->ssrUrl = $ssrUrl;
    }

    protected function configure(): void
    {
        $this->setName('inertia:stop-ssr');
        $this->setDescription('Stop the Inertia SSR server');
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
