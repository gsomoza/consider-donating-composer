<?php

namespace Somoza\ConsiderDonating\Command\Common;

use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

final class OpenProcessFactory
{
    /** @var string */
    private $command;

    public function __construct()
    {
        $this->command = \strtolower(\substr(\php_uname('s'), 0, 3)) === 'win'
            ? 'start'
            : 'open';
    }

    /**
     * @param array $openArgs
     * @param array ...$processArgs
     * @return Process
     */
    public function create(array $openArgs, array ...$processArgs): Process
    {
        Assert::minCount($openArgs, 1);
        Assert::allString($openArgs);

        \array_unshift($openArgs, $this->command);
        $command = \implode(' ', $openArgs);

        return new Process($command, ...$processArgs);
    }
}
