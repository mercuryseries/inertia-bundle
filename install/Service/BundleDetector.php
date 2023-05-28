<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class BundleDetector
{
    public function __construct(
        private readonly Filesystem $filesystem,

        #[Autowire('%app.inertia.ssr.bundle%')]
        private readonly string $configuredBundle,

        #[Autowire('%kernel.project_dir%')]
        private readonly string $basePath
    ) {
    }

    public function detect(): ?string
    {
        $bundlePaths = [
            $this->configuredBundle,
            Path::makeAbsolute('public/build-ssr/ssr.js', $this->basePath),
            Path::makeAbsolute('public/build-ssr/ssr.mjs', $this->basePath),
            Path::makeAbsolute('public/build/ssr.js', $this->basePath),
            Path::makeAbsolute('public/build/ssr.mjs', $this->basePath),
        ];

        foreach ($bundlePaths as $bundlePath) {
            if ($this->filesystem->exists($bundlePath)) {
                return $bundlePath;
            }
        }

        return null;
    }
}
