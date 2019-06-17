<?php
namespace Somoza\ConsiderDonating\Command;

use function file_get_contents;
use Composer\Command\BaseCommand;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use Somoza\CliAuth\AuthenticationResult;
use Somoza\CliAuth\AuthServer;
use Somoza\CliAuth\Server;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Authenticate extends BaseCommand
{
    const ARG_EMAIL = 'email';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('donate:authenticate');
        $this->setAliases(['donate:login']);
        $this->setDescription(
            'Allows you to keep track of your donations across multiple computers, VMs, containers, etc.'
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting server...');

        $server = AuthServer::fromFile(__DIR__.'/../../pub/index.html');

        $server->authenticate()->then(
            function ($result) use ($output) {
                if ($result instanceof AuthenticationResult) {
                    $request = $result->getRequest();
                    $output->writeln('Token1: ' . $request->getUri());
                    $output->writeln(print_r($request->getBody()->getContents(), true));
                }
            },
            function ($reason) use ($output) {
                $message = PHP_EOL . "Error! ";
                if ($reason instanceof \Exception) {
                    $message .= get_class($reason) . ': ' . $reason->getMessage();
                    $message .= PHP_EOL . 'TRACE: ' . $reason->getTraceAsString();
                    if ($previous = $reason->getPrevious()) {
                        $message .= PHP_EOL . 'Previous: '  . get_class($previous) . ': ' . $previous->getMessage();
                        $message .= PHP_EOL . 'TRACE: ' . $previous->getTraceAsString();
                    }
                }
                $output->writeln($message);
            }
        );

        $output->writeln('Done!');
    }
}
