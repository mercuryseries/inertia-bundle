<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class BundleDetector
{
    public function __construct(
        private readonly Filesystem $filesystem,

        private readonly string $configuredBundle,

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
