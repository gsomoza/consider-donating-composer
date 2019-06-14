<?php
namespace Gabriel\ConsiderDonating;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Gabriel\ConsiderDonating\Command\Donate;
use Gabriel\ConsiderDonating\Command\PrintList;

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
