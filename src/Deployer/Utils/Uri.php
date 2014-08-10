<?php


namespace Deployer\Utils;


class Uri {

    protected $config;

    public function __construct($uri)
    {
        $this->config = is_array($uri)?$uri:$this->parse($uri);
    }

    public function getScheme()
    {
        return !empty($this->config['scheme'])?$this->config['scheme']:'ssh';
    }

    public function getLogin()
    {
        return $this->config['login'];
    }

    public function getPassword()
    {
        return $this->config['password'];
    }

    public function getHost()
    {
        return $this->config['host'];
    }

    public function getPort()
    {
        return !empty($this->config['port'])?$this->config['port']:22;
    }

    public function getPath()
    {
        return isset($this->config['isHome'])?substr($this->config['path'], 1):$this->config['path'];
    }

    protected function parse($uri)
    {
        $regex = '((?P<scheme>(.*)?)\:\/\/)?'; // Scheme
        $regex .= '((?P<login>[a-z0-9\+\!\*\(\),;\?&=\$_\.\-]+)(\:(?P<password>[a-z0-9\+\!\*\(\),;\?&=\$_\.\-]+))?@)?'; // User and Pass
        $regex .= '(?P<host>([a-z0-9\-\.]*))'; // Host or IP
        $regex .= '(\:(?P<port>[0-9]{2,5}))?'; // Port
        $regex .= '(?P<path>\/((?P<isHome>(~\/))?([a-z0-9+\$_-]\.?)+))*\/?'; // Path
        //$regex .= '(?P<query>\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?'; // GET Query
        //$regex .= '(#(?P<anchor>[a-z_.-][a-z0-9+\$_.-]*))?'; // Anchor

        if ( !preg_match('/^'.$regex.'$/i', $uri, $matches) ) {
            throw new \InvalidArgumentException('Invalid URI');
        }

        $uri = array();
        foreach ( $matches as $key => $value ) {
            if ( !is_int($key) ) {
                $uri[$key] = $value;
            }
        }
        return $uri;
    }
} 