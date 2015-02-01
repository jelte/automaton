<?php


namespace Automaton\Server\Ssh;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;

class PhpSeclibConnection implements ConnectionInterface
{
    /**
     * @var \Net_SFTP
     */
    private $session;

    public function __construct(\Net_SFTP $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function run($command)
    {
        $result = $this->session->exec($command);
        if ($this->session->getStdError()) {
            throw new \RuntimeException($this->session->getStdError());
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function runInteractively($command, $inputLine, $endline, InputInterface $input, OutputInterface $output, HelperSet $helperSet)
    {
        $this->session->write($command . "\n");
        /** @var QuestionHelper $question */
        $question = $helperSet->get('dialog');
        while ($outp = $this->session->read("#({$inputLine})|({$endline})#", NET_SSH2_READ_REGEX)) {
            if ( preg_match("#{$endline}#", $outp) ) break;
            $answer = $question->ask($input, $output, $outp);
            $this->session->write($answer."\n");
        }

        if ($this->session->getStdError()) {
            throw new \RuntimeException($this->session->getStdError());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($local, $remote)
    {
        if (!$this->session->put($remote, $local, NET_SFTP_LOCAL_FILE)) {
            throw new \RuntimeException(implode($this->session->getSFTPErrors(), "\n"));
        }
    }

    public function mkdir($path)
    {
        $this->session->mkdir($path, -1, true);
    }

    /**
     * {@inheritdoc}
     */
    public function download($local, $remote)
    {
        if (!$this->session->get($remote, $local)) {
            throw new \RuntimeException(implode($this->session->getSFTPErrors(), "\n"));
        }
    }
}
