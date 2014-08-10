<?php


namespace Deployer\Stage;


interface StageInterface {
    public function getName();

    public function getServers();
} 