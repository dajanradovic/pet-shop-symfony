<?php

namespace App\EventListener;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\Security;


class ApiLoginListener
{

    private $security;

    public function __construct(Security $security){
        $this->security = $security;
    }
    public function __invoke(){

        if(!$this->security->getUser()->isVerified()){
            throw new CustomUserMessageAccountStatusException('Your need to verify your email address in order to be able to login');

        }
    }
}