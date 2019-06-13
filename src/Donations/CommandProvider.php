<?php
namespace Gabriel\ConsiderDonating\Donations;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Gabriel\ConsiderDonating\Donations\Command\Donate;

final class CommandProvider implements CommandProviderCapability
{
    /**
     * @inheritDoc
     */
    public function getCommands()
    {
        return [
            new Donate(),
        ];
    }
}
