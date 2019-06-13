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

class Donate extends BaseCommand
{
    protected function configure()
    {
        $this->setName('donate');
        $this->setDescription('Takes you to a web page where you can support your package of choice.');
        $this->addArgument('package', InputArgument::REQUIRED, "Name of the package you'd like to donate to");
        $this->addOption('yes', 'y', null, 'Open the URL directly, without confirmation.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packageArgument = $input->getArgument('package');
        $packages = $this->getComposer()->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();

        $selectedPackage = null;
        if (\is_numeric($packageArgument)) {
            $packages = \array_filter($packages, function(PackageInterface $package) {
                return !empty($package->getExtra()['donations']);
            });
            $selectedPackage = $packages[(int) $packageArgument - 1] ?? null;
        } else {
            foreach ($packages as $package) {
                if ($package->getName() === $packageArgument) 
                {
                    $selectedPackage = $package;
                    break;
                }
            }    
        }

        $result = 0;
        if ($selectedPackage) {
            $force = (bool) $input->getOption('yes');
            $result = $this->donateTo($selectedPackage, $force, $input, $output);
        } else {
            throw new \ErrorException(
                "Couldn't find the requested package, please double-check the package name and try again."
            );

            $result = 1;
        }

        return $result;
    }

    /**
     * Help perform the donation
     *
     * @param PackageInterface $package The package to donate to
     * @param boolean $force Whether a prompt should be used to confirm the donation
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    private function donateTo(PackageInterface $package, bool $force, InputInterface $input, OutputInterface $output)
    {
        if (empty($package->getExtra()['donations'])) {
            $output->writeln(\sprintf(
                "<error>ERROR:</error> Package '%s' doesn't follow our donations protocol", 
                $package->getName()
            ));
            return 1;
        }

        $donationOptions = $package->getExtra()['donations'];
        // $donationOptions = is_array($donationOptions) ? $donationOptions: [$donationOptions];
        $donationOption = $donationOptions; // only one option supported for now
        $url = '"' . $donationOption['url'] . '"';
        if (!\preg_match('#^\"https?://#i', $url)) {
            $output->writeln(\sprintf('<error>Invalid donation URL %s</error>', $url));
            return 1;
        }

        if (!$force) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion(
                \sprintf('About to open URL %s – do you want to proceed (y/n)? ', $url), 
                true
            );
            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }
        
        $command = \strtolower(\substr(\php_uname('s'), 0, 3)) === 'win' ? 'start' : 'open';
        $process = new Process($command . ' ' . $url);
        $process->run();

        return 0;
    }
}
