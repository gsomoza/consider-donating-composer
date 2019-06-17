<?php
namespace Somoza\ConsiderDonating\Command\Common;

use Composer\Package\PackageInterface;
use Composer\Repository\WritableRepositoryInterface;
use Somoza\ConsiderDonating\Donations\ComposerHelper;

class DonationPromptMessages
{
    /** @var string[] */
    private $messages;

    /**
     * DonationList constructor.
     * @param PackageInterface[] $donationPackages
     */
    private function __construct(array $donationPackages)
    {
        $output = [];

        if (count($donationPackages) > 0) {
            // intro message
            $output []= 'This project depends on the generous work of real people.';
            $output []= 'Please consider donating to the following open-source projects:';
            $output []= '';

            $i = 1;
            foreach ($donationPackages as $package) {
                $output [] = \sprintf("    (<info>%s</info>) %s", $i++, $package->getName());
            }

            $output []= '';
            $output []= 'To donate, simply run <comment>composer donate package/name</comment>';
            // $output []= 'To see a list of projects you already support, run "composer list-donations [package/name]"';
        }

        $this->messages = $output;
    }

    /**
     * @param WritableRepositoryInterface $composerLocalRepository
     * @return DonationPromptMessages
     */
    public static function fromComposerLocalRepository(WritableRepositoryInterface $composerLocalRepository)
    {
        $packages = $composerLocalRepository->getCanonicalPackages();
        $helper = new ComposerHelper();
        $donationPackages = $helper->filterDonationPackages($packages);

        return new static($donationPackages);
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
