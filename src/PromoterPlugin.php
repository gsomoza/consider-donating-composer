<?php
/**
 * (c) 2019 – Gabriel Somoza (gabriel@somoza.me)
 */
namespace Gabriel\ConsiderDonating;

use Composer\Composer;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Script\ScriptEvents;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Gabriel\ConsiderDonating\Promoter\PromoterService;

/**
 * The purpose of this plugin is to promote opportunities for donations to Composer users.
 */
final class PromoterPlugin implements PluginInterface, EventSubscriberInterface
{
    /** @var IOInterface */
    private $io;

    /** @var Composer */
    private $composer;

    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io) 
    {
        $this->io = $io;
        $this->composer = $composer;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents() 
    {
        return [
            ScriptEvents::POST_AUTOLOAD_DUMP => 'onPostAutoloadDump'
        ];
    }

    /**
     * Triggers a promotion message for all plugins that support the Consider Donating protocol.
     * 
     * Listens to the post-autoload-dump event.
     *
     * @param Event $event
     * @return void
     */
    public function onPostAutoloadDump(Event $event)
    {
        $shouldOutput = false;

        $localRepo = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages = $localRepo->getCanonicalPackages();
        $i = 1;
        foreach ($packages as $package) {
            /** @var $package \Composer\Package\PackageInterface */
            $extra = $package->getExtra();
            if (isset($extra['donations'])) {
                if (!$shouldOutput) {
                    $shouldOutput = true; // there's at least one package that requests donations
                    // intro message
                    $this->io->write(''); // ensure we start on a new line (some dump commands don't end on EOL)
                    $this->io->write('Your project depends on the generous work of real people.');
                    $this->io->write('Please consider donating to the following open-source projects:' . PHP_EOL);
                }
    
                if ($shouldOutput) {
                    $this->io->write(\sprintf('    (%s) %s ', $i, $package->getName()));
                    $i++;
                }
            }
        }

        if ($shouldOutput) {
            $this->io->write('');
            $this->io->write('To donate, simply run "composer donate package/name"');
            // $this->io->write('To see a list of projects you already support, run "composer list-donations [package/name]"');
        }
    }
}
