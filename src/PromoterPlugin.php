<?php
/**
 * (c) 2019 – Gabriel Somoza (gabriel@somoza.me)
 */
namespace Somoza\ConsiderDonating;

use Composer\Composer;
use Composer\IO\ConsoleIO;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Script\ScriptEvents;
use Composer\Plugin\PluginInterface;
use Somoza\ConsiderDonating\Command\Common\DonationPromptMessages;
use Symfony\Component\Console\Input\ArrayInput;
use Composer\EventDispatcher\EventSubscriberInterface;
use Somoza\ConsiderDonating\Donations\ComposerHelper;
use Somoza\ConsiderDonating\Command\PrintList;

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
     * @inheritDo
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
     * @throws \Exception
     */
    public function onPostAutoloadDump(Event $event)
    {
        $messages = DonationPromptMessages::fromComposerLocalRepository(
            $event->getComposer()->getRepositoryManager()->getLocalRepository()
        );

        $this->io->write($messages->getMessages());
    }
}
