<?php
namespace Somoza\ConsiderDonating;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Somoza\ConsiderDonating\Command\Authenticate;
use Somoza\ConsiderDonating\Command\Donate;
use Somoza\ConsiderDonating\Command\PrintList;

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
            new Authenticate(),
        ];
    }
}
