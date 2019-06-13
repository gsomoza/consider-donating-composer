<?php
/**
 * (c) 2019 – Gabriel Somoza (gabriel@somoza.me)
 */
namespace Gabriel\ConsiderDonating;

use Composer\Composer;
use Composer\IO\ConsoleIO;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Script\ScriptEvents;
use Composer\Plugin\PluginInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Composer\EventDispatcher\EventSubscriberInterface;
use Gabriel\ConsiderDonating\Promoter\PromoterService;
use Gabriel\ConsiderDonating\Donations\Command\PrintList;

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
        // TODO: this is an ugly way of extracting the symfony output object - try to find a better way
        // ideally by finding the instance of the symfony console application
        $klass = new class extends ConsoleIO {
            public function __construct() {}

            public static function getOutput(ConsoleIO $io) {
                return $io->output;
            }
        };

        $input = new ArrayInput([]);
        $output = $klass::getOutput($this->io);

        $command = new PrintList();
        $command->setComposer($this->composer);

        $output->writeln('');

        $command->run($input, $output);
    }
}
