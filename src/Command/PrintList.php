<?php
namespace Gabriel\ConsiderDonating\Command;

use Composer\Command\BaseCommand;
use Composer\Package\PackageInterface;
use Gabriel\ConsiderDonating\Command\Common\DonationPromptMessages;
use Gabriel\ConsiderDonating\Donations\ComposerHelper;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * List packages available for donations
 */
class PrintList extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('donate:list');
        $this->setDescription('List package available for donations.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = DonationPromptMessages::fromComposerLocalRepository(
            $this->getComposer()->getRepositoryManager()->getLocalRepository()
        );

        $output->writeln($messages->getMessages());
    }
}
