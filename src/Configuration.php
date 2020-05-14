<?php

namespace ServerBackup\Dropbox;

class Configuration {

    /** @var string $apiUrl */
    public $apiUrl;

    /** @var string $token */
    public $token;

    public function __construct(string $token, string $apiUrl = 'https://content.dropboxapi.com/2/files/upload')
    {
        $this->apiUrl = $apiUrl;
        $this->token = $token;
    }    

}
