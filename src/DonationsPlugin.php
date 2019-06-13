<?php
namespace Gabriel\ConsiderDonating;

use Composer\Plugin\PluginInterface;
use Composer\Plugin\Capable;
use Composer\Composer;
use Composer\IO\IOInterface;
use Gabriel\ConsiderDonating\Donations\CommandProvider as DonationsCommandProvider;

/**
 * Provides commands for managing donations
 */
final class DonationsPlugin implements PluginInterface, Capable
{
    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        // nothing for now
    }

    /**
     * @inheritDoc
     */
    public function getCapabilities()
    {
        return [
            \Composer\Plugin\Capability\CommandProvider::class => DonationsCommandProvider::class,
        ];
    }
}
