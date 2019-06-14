<?php
namespace Gabriel\ConsiderDonating\Donations;

use Composer\Package\PackageInterface;

final class ComposerHelper
{
    /**
     * Given an array of composer packages, returns those that accept donations
     *
     * @param array $packages
     * @return PackageInterface[]
     */
    public function filterDonationPackages(array $packages): array
    {
        return \array_filter(
            $packages,
            function (PackageInterface $package) {
                $extra = $package->getExtra();
                return !empty($extra['donations']);
            }
        );
    }
}
