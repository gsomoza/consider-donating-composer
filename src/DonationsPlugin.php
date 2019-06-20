<?php
namespace Somoza\ConsiderDonating;

use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\Capable;
use Composer\Composer;
use Composer\IO\IOInterface;
use Somoza\ComposerPluginFileLoader\PackageFileLoader;
use Somoza\ConsiderDonating\CommandProvider as DonationsCommandProvider;

/**
 * Provides commands for managing donations
 */
final class DonationsPlugin implements PluginInterface, Capable
{
    const AUTOLOAD_FILES_PACKAGES = ['ringcentral/psr7', 'react/promise', 'react/promise-stream'];

    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        // @see https://github.com/composer/composer/issues/8192
        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';
    }

    /**
     * @inheritDoc
     */
    public function getCapabilities()
    {
        return [
            ComposerCommandProvider::class => DonationsCommandProvider::class,
        ];
    }
}
