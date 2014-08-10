<?php


namespace Deployer\Server\Ssh;


class Configuration {
    private $privateKey;

    private $publicKey;

    private $passPhrase;

    private $pemFile;

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function getPassPhrase()
    {
        return $this->passPhrase;
    }


    public function setPassPhrase($passPhrase)
    {
        $this->passPhrase = $passPhrase;
    }

    public function getPemFile()
    {
        return $this->pemFile;
    }

    public function setPemFile($pemFile)
    {
        $this->pemFile = $pemFile;
    }
} 