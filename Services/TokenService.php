<?php

namespace Redacted\PasswordlessBundle\Services;

class TokenService
{
    public function generateToken($secret = '')
    {
        $hash = uniqid($secret, $more_entropy = true);
        for($i = 0; $i < mt_rand(3, 25); $i++)
            $hash = ($i % 2) ? hash('SHA256', $hash) : hash('SHA512', $hash) ;

        return $hash;
    }
}