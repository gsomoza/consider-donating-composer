<?php
namespace Gabriel\ConsiderDonating\Donations\Command;

use Composer\Command\BaseCommand;
use Composer\Package\PackageInterface;
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
    protected function configure()
    {
        $this->setName('donate:list');
        $this->setDescription('List package available for donations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shouldOutput = false;

        $localRepo = $this->getComposer()->getRepositoryManager()->getLocalRepository();
        $packages = $localRepo->getCanonicalPackages();
        $i = 1;
        foreach ($packages as $package) {
            /** @var $package \Composer\Package\PackageInterface */
            $extra = $package->getExtra();
            if (isset($extra['donations'])) {
                if (!$shouldOutput) {
                    $shouldOutput = true; // there's at least one package that requests donations
                    // intro message
                    $output->writeln([
                        'This project depends on the generous work of real people.',
                        'Please consider donating to the following open-source projects:',
                        ''
                    ]);
                }
    
                if ($shouldOutput) {
                    $output->writeln(\sprintf('    (<info>%s</info>) %s ', $i, $package->getName()));
                    $i++;
                }
            }
        }

        if ($shouldOutput) {
            $output->writeln('');
            $output->writeln('To donate, simply run <comment>composer donate package/name</comment>');
            // $this->io->write('To see a list of projects you already support, run "composer list-donations [package/name]"');
        }
    }
}
