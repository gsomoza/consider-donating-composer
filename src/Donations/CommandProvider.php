<?php
namespace Gabriel\ConsiderDonating\Donations;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Gabriel\ConsiderDonating\Donations\Command\Donate;
use Gabriel\ConsiderDonating\Donations\Command\PrintList;

final class CommandProvider implements CommandProviderCapability
{
    /**
     * @inheritDoc
     */
    public function getCommands()
    {
        return [
            new Donate(),
            new PrintList(),
        ];
    }
}
