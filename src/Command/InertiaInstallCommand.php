<?php

namespace MercurySeries\Bundle\InertiaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Process\Process;

final class InertiaInstallCommand extends Command
{
    private $filesystem;
    private $basePath;

    private $input;
    private $output;
    private $io;

    public function __construct(Filesystem $filesystem, string $basePath)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->basePath = $basePath;
    }

    protected function configure(): void
    {
        $this
            ->setName('inertia:install')
            ->setDescription('Install the Inertia resources')
            ->addArgument('stack', InputArgument::OPTIONAL, 'The development stack that should be installed (react or vue)')
            ->addOption('bundler', null, InputOption::VALUE_REQUIRED, 'The bundler that should be used (webpack or vite)', 'webpack')
            ->addOption('ssr', null, InputOption::VALUE_NONE, 'Indicates if Inertia SSR support should be installed')
            ->addOption('composer', null, InputOption::VALUE_REQUIRED, 'Absolute path to the Composer binary which should be used to install packages', 'global')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->io = new SymfonyStyle($this->input, $this->output);

        $bundler = $this->input->getArgument('bundler');

        if (!in_array($bundler, ['webpack', 'vite'])) {
            $this->io->error('Invalid bundler. Supported bundlers are [webpack] and [vite].');

            return Command::INVALID;
        }

        $stack = $this->input->getArgument('stack');

        if ('react' === $stack) {
            return $this->installInertiaReactStack($bundler);
        } elseif ('vue' === $stack) {
            return $this->installInertiaVueStack($bundler);
        }

        $this->io->error('Invalid stack. Supported stacks are [react] and [vue].');

        return Command::INVALID;
    }

    /**
     * Install the Inertia React stack.
     */
    protected function installInertiaReactStack(string $bundler): int
    {
        // Install Inertia...
        if (!$this->requireComposerPackages([
            'symfony/webpack-encore-bundle:^1.17|^2.0',
            'symfony/validator:^5.0|^6.0',
        ])) {
            return Command::FAILURE;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/react' => '^1.0.0',
                '@babel/preset-react' => '^7.18.6',
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
                'babel-plugin-transform-react-remove-prop-types' => '^0.4.24',
                'fos-router' => 'file:vendor/friendsofsymfony/jsrouting-bundle/Resources',
                'prop-types' => '^15.8.1',
                'webpack-node-externals' => '^3.0.0',
            ] + array_filter(
                $packages,
                fn ($package) => !in_array($package, ['@hotwired/stimulus', '@symfony/stimulus-bridge']),
                ARRAY_FILTER_USE_KEY
            );
        });

        // Routes...
        if ($this->filesystem->exists(Path::makeAbsolute('config/routes/annotations.yaml', $this->basePath))) {
            $this->filesystem->copy(
                __DIR__.'/../../stubs/config/routes/annotations.yaml',
                Path::makeAbsolute('config/routes/annotations.yaml', $this->basePath),
                true
            );
        } else {
            $this->filesystem->copy(
                __DIR__.'/../../stubs/config/routes.yaml',
                Path::makeAbsolute('config/routes.yaml', $this->basePath),
                true
            );
        }

        // Controllers...
        $this->ensureDirectoryExists(Path::makeAbsolute('src/Controller', $this->basePath));
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/src/Controller',
            Path::makeAbsolute('src/Controller', $this->basePath)
        );
        if ($this->filesystem->exists(Path::makeAbsolute('src/Controller/.gitignore', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('src/Controller/.gitignore', $this->basePath));
        }

        // Forms...
        $this->ensureDirectoryExists(Path::makeAbsolute('src/Form', $this->basePath));
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/src/Form',
            Path::makeAbsolute('src/Form', $this->basePath)
        );

        // Event Subscribers...
        $this->ensureDirectoryExists(Path::makeAbsolute('src/EventSubscriber', $this->basePath));
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/src/EventSubscriber',
            Path::makeAbsolute('src/EventSubscriber', $this->basePath)
        );

        // Templates...
        if ($this->filesystem->exists(Path::makeAbsolute('templates/base.html.twig', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('templates/base.html.twig', $this->basePath));
        }
        $this->filesystem->copy(
            __DIR__.'/../../stubs/templates/app.html.twig',
            Path::makeAbsolute('templates/app.html.twig', $this->basePath)
        );

        // Components + Pages + Styles...
        $this->ensureDirectoryExists(Path::makeAbsolute('assets/js/components', $this->basePath));
        $this->ensureDirectoryExists(Path::makeAbsolute('assets/js/pages', $this->basePath));
        $this->ensureDirectoryExists(Path::makeAbsolute('assets/styles', $this->basePath));

        if ($this->filesystem->exists(Path::makeAbsolute('assets/app.js', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/app.js', $this->basePath));
        }
        if ($this->filesystem->exists(Path::makeAbsolute('assets/bootstrap.js', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/bootstrap.js', $this->basePath));
        }
        if ($this->filesystem->exists(Path::makeAbsolute('assets/controllers.json', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/controllers.json', $this->basePath));
        }
        if ($this->filesystem->exists(Path::makeAbsolute('assets/controllers', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/controllers', $this->basePath));
        }

        $this->filesystem->mirror(
            __DIR__.'/../../stubs/assets/js/components',
            Path::makeAbsolute('assets/js/components', $this->basePath)
        );
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/assets/js/pages',
            Path::makeAbsolute('assets/js/pages', $this->basePath)
        );
        $this->filesystem->copy(
            __DIR__.'/../../stubs/assets/js/app.js',
            Path::makeAbsolute('assets/js/app.js', $this->basePath)
        );
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/assets/styles',
            Path::makeAbsolute('assets/styles', $this->basePath),
            null,
            ['override' => true]
        );

        // Webpack...
        $this->filesystem->copy(
            __DIR__.'/../../stubs/webpack.config.js',
            Path::makeAbsolute('webpack.config.js', $this->basePath),
            true
        );
        $this->filesystem->copy(
            __DIR__.'/../../stubs/jsconfig.json',
            Path::makeAbsolute('jsconfig.json', $this->basePath)
        );

        if ($this->input->getOption('ssr')) {
            $this->installInertiaReactSsrStack($bundler);
        }

        $this->io->info('Installing Node dependencies.');

        if ($this->filesystem->exists(Path::makeAbsolute('pnpm-lock.yaml', $this->basePath))) {
            $this->runCommand('pnpm install');
        } elseif ($this->filesystem->exists(Path::makeAbsolute('yarn.lock', $this->basePath))) {
            $this->runCommand('yarn install');
        } else {
            $this->runCommand('npm install');
        }

        $this->io->info('Inertia scaffolding installed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Install the Inertia Vue stack.
     */
    protected function installInertiaVueStack(string $bundler): int
    {
        throw new \RuntimeException('Not implemented yet!');
    }

    /**
     * Install the Inertia React SSR stack into the application.
     */
    protected function installInertiaReactSsrStack(string $bundler): void
    {
        $this->filesystem->copy(
            __DIR__.'/../../stubs/webpack.ssr.config.js',
            Path::makeAbsolute('webpack.ssr.config.js', $this->basePath)
        );

        $this->filesystem->copy(
            __DIR__.'/../../stubs/assets/js/ssr.js',
            Path::makeAbsolute('assets/js/ssr.js', $this->basePath)
        );

        $this->replaceInFile(
            '"build": "encore production --progress"',
            '"build:client": "encore production --progress",'.PHP_EOL.'        "build:server": "encore production --progress --config webpack.ssr.config.js",'.PHP_EOL.'        "build": "npm run build:client && npm run build:server"',
            Path::makeAbsolute('package.json', $this->basePath)
        );
    }

    /**
     * Replace a given string within a given file.
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        $this->filesystem->dumpFile($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Installs the given Composer Packages into the application.
     */
    protected function requireComposerPackages(array $packages, bool $asDev = false): bool
    {
        $composer = $this->input->getOption('composer');

        if ('global' !== $composer) {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            $packages,
            $asDev ? ['--dev'] : [],
        );

        return 0 === (new Process($command, $this->basePath, ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Update the "package.json" file.
     */
    protected function updateNodePackages(callable $callback, bool $dev = true): void
    {
        $packageJsonFile = Path::makeAbsolute('package.json', $this->basePath);

        if (!$this->filesystem->exists($packageJsonFile)) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents($packageJsonFile), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        $this->filesystem->dumpFile(
            $packageJsonFile,
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected function ensureDirectoryExists(string $path, int $mode = 0755): void
    {
        if (!$this->filesystem->exists($path)) {
            $this->filesystem->mkdir($path, $mode);
        }
    }

    /**
     * Run the given command.
     */
    protected function runCommand(string $command): void
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }
}
