<?php
declare(strict_types=1);

namespace Vendor\TLGenerator;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Installer\PackageEvent;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    private Composer $composer;
    private IOInterface $io;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_AUTOLOAD_DUMP => 'onPostAutoloadDump',
            ScriptEvents::POST_INSTALL_CMD  => 'onPostInstall',
            ScriptEvents::POST_UPDATE_CMD   => 'onPostUpdate',
            // package-level events (optional)
            'post-package-install' => 'onPostPackageInstall',
            'post-package-update'  => 'onPostPackageUpdate',
        ];
    }

    public function onPostAutoloadDump(Event $event): void
    {
        $this->io->write('<info>Vendor/TLGenerator: running post-autoload-dump tasks</info>');

        // Example task: write a small cache file in the vendor directory if writable.
        try {
            $vendorDir = $this->composer->getConfig()->get('vendor-dir') ?? __DIR__ . '/..';
            $cacheFile = rtrim((string) $vendorDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'vendor-TLGenerator.cache';

            $payload = [
                'generated' => (new \DateTime())->format(\DateTime::ATOM),
                'package' => $this->composer->getPackage()?->getName() ?: 'unknown-root',
            ];

            if (@file_put_contents($cacheFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
                throw new \RuntimeException('Unable to write to ' . $cacheFile);
            }

            $this->io->write('Wrote cache to ' . $cacheFile);
        } catch (\Throwable $e) {
            $this->io->writeError('Vendor/TLGenerator: failed to run tasks — ' . $e->getMessage());
        }
    }

    public function onPostInstall(Event $event): void
    {
        $this->io->write('Vendor/TLGenerator: post-install hook');
        $this->onPostAutoloadDump($event);
    }

    public function onPostUpdate(Event $event): void
    {
        $this->io->write('Vendor/TLGenerator: post-update hook');
        $this->onPostAutoloadDump($event);
    }

    public function onPostPackageInstall(PackageEvent $event): void
    {
        $op = $event->getOperation();
        $pkg = $op?->getPackage()?->getName() ?: 'unknown';
        $this->io->write('Vendor/TLGenerator: package installed: ' . $pkg);
    }

    public function onPostPackageUpdate(PackageEvent $event): void
    {
        $op = $event->getOperation();
        $pkg = $op?->getPackage()?->getName() ?: 'unknown';
        $this->io->write('Vendor/TLGenerator: package updated: ' . $pkg);
    }
}
