<?php

namespace MercurySeries\Bundle\InertiaBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class BundleDetector
{
    private $filesystem;
    private $basePath;
    private $configuredBundle;

    public function __construct(Filesystem $filesystem, string $basePath, string $configuredBundle = null)
    {
        $this->filesystem = $filesystem;
        $this->basePath = $basePath;
        $this->configuredBundle = $configuredBundle;
    }

    public function detect(): ?string
    {
        $bundlePaths = array_filter([
            $this->configuredBundle,
            Path::makeAbsolute('public/build-ssr/ssr.js', $this->basePath),
            Path::makeAbsolute('public/build-ssr/ssr.mjs', $this->basePath),
            Path::makeAbsolute('public/build/ssr.js', $this->basePath),
            Path::makeAbsolute('public/build/ssr.mjs', $this->basePath),
        ]);

        foreach ($bundlePaths as $bundlePath) {
            if ($this->filesystem->exists($bundlePath)) {
                return $bundlePath;
            }
        }

        return null;
    }
}
