<?php


namespace Automaton\Server;


use Symfony\Component\Console\Output\OutputInterface;

class DryRunServer extends AbstractServer
{
    /**
     * @var ServerInterface
     */
    private $server;

    public function __construct(ServerInterface $server, OutputInterface $output)
    {
        $this->server = $server;
        $this->output = $output;
    }

    public function getName()
    {
        return $this->server->getName();
    }

    public function cwd($path)
    {
        return $this->server->cwd($path);
    }

    /**
     * {@inheritdoc}
     */
    public function run($command)
    {
        $this->output->writeln(sprintf('[%s] run(%s)', $this->server->getName(), $command));
    }

    /**
     * {@inheritdoc}
     */
    public function upload($local, $remote)
    {
        $this->output->writeln(sprintf('[%s] upload(%s, %s)', $this->server->getName(), $local, $remote));
    }
}
