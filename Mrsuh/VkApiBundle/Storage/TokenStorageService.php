<?php

namespace Mrsuh\VkApiBundle\Storage;

class TokenStorageService
{
    /**
     * token
     */
    private $token;

    /**
     *  path to token file
     */
    private $file_path;

    /**
     * TokenStorageService constructor.
     * @param $file_path
     */
    public function __construct($file_path)
    {
        $this->file_path = $file_path;
        $this->token = file_exists($file_path) ? file_get_contents($file_path) : null;
    }

    /**
     * @return null|string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        file_put_contents($this->file_path, $token);
    }
}