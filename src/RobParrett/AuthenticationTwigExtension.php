<?php

Namespace RobParrett;

class AuthenticationTwigExtension extends \Twig_Extension {
    protected $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('isAuthenticated', array($this, 'isAuthenticated')),
            new \Twig_SimpleFunction('isSuperUser', array($this, 'isSuperUser'))
        ];
    }

    public function isAuthenticated()
    {
        return $this->auth->isAuthenticated();
    }
    
    public function isSuperUser()
    {
        return $this->auth->isSuperUser();
    }
}
