<?php

namespace Redacted\PasswordlessBundle\Services;

use Doctrine\Common\Cache\RedisCache;
use Symfony\Component\VarDumper\VarDumper;

class CacheService
{
    public $_prefix = "pswdls";

    public function getStore()
    {
        // For now, we use redis. We will support more later
        $redis = new \Redis();
        $redis->connect('localhost', 6379);
        $driver = new RedisCache();
        $driver->setRedis($redis);

        return $driver;
    }

    public function setToken($email, $token)
    {
        $store = $this->getStore();
        $email = $this->hash($email);
        $token = $this->hash($token);
        $store->save($this->getKey($token), $email, 5000);
    }

    public function verifyToken($email, $token)
    {
        $store = $this->getStore();
        $email = $this->hash($email);
        $token = $this->hash($token);

        $data = $store->fetch($this->getKey($token));

        if(!$data)
            throw new \Exception('Token not found');
        if($data != $email)
            throw new \Exception('Invalid email to token');

        return true;
    }


    /**
     * Returns a pre-formatted key string
     *
     * @param $token
     * @return string
     */
    private function getKey($token)
    {
        return sprintf('%s_%s', $this->_prefix, $token);
    }

    /**
     * Creates a secure hash for a provided item
     *
     * @param $item
     * @param int $cost
     * @return bool|string
     */
    private function hash($item, $cost = 10)
    {
        return hash('SHA256', $item); //password_hash($item, PASSWORD_BCRYPT, array('cost'=>$cost));
    }
}