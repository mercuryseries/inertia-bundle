<?php

namespace App\Command;

use App\Service\BundleDetector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:start-inertia-ssr',
    description: 'Start the Inertia SSR server',
)]
class StartInertiaSsrCommand extends Command
{
    public function __construct(
        private readonly BundleDetector $bundleDetector,

        #[Autowire('%app.inertia.ssr.enabled%')]
        private readonly bool $ssrEnabled,

        #[Autowire('%app.inertia.ssr.bundle%')]
        private readonly string $configuredBundle
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$this->ssrEnabled) {
            $io->error('Inertia SSR is not enabled. Enable it via the `app.inertia.ssr.enabled` config parameter.');

            return Command::FAILURE;
        }

        $bundle = $this->bundleDetector->detect();

        if (null === $bundle) {
            $io->error(
                $this->configuredBundle
                    ? 'Inertia SSR bundle not found at the configured path: "'.$this->configuredBundle.'"'
                    : 'Inertia SSR bundle not found. Set the correct Inertia SSR bundle path via the `app.inertia.ssr.bundle` config parameter.'
            );

            return Command::FAILURE;
        } elseif ($this->configuredBundle && $bundle !== $this->configuredBundle) {
            $io->warning('Inertia SSR bundle not found at the configured path: "'.$this->configuredBundle.'"');
            $io->warning('Using a default bundle instead: "'.$bundle.'"');
        }

        $command = $this->getApplication()->find('app:stop-inertia-ssr');
        $command->run(new ArrayInput([]), new NullOutput());

        $process = new Process(['node', $bundle]);
        $process->setTimeout(null);
        $process->start();

        if (extension_loaded('pcntl')) {
            $stop = function () use ($process) {
                $process->stop();
            };
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, $stop);
            pcntl_signal(SIGQUIT, $stop);
            pcntl_signal(SIGTERM, $stop);
        }

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $io->info(trim($data));
            } else {
                $io->error(trim($data));
            }
        }

        return Command::SUCCESS;
    }
}
