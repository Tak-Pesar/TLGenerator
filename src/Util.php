<?php
declare(strict_types=1);

namespace Vendor\TLGenerator;

use Composer\Composer;

final class Util
{
    private Composer $composer;

    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
    }

    /**
     * Return absolute install path for a package name (vendor/name) or null if not installed.
     */
    public function findInstallPath(string $packageName): ?string
    {
        $localRepo = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages = $localRepo->getPackages();

        foreach ($packages as $pkg) {
            if ($pkg->getName() === $packageName) {
                return $this->composer->getInstallationManager()->getInstallPath($pkg);
            }
        }
        return null;
    }
}

?>
