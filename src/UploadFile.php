<?php

namespace ServerBackup\Dropbox;

class UploadFile {

    /** @var Configuration $config */
    public $config;

    public function __construct(Configuration $config)
	{
		$this->config = $config;
	}

    public function upload($filename): string
    {
        $headers = [
            'Authorization: Bearer '. $this->config->token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: '.
            json_encode(
                [
                    "path"=> '/'. basename($filename),
                    "mode" => "add",
                    "autorename" => true,
                    "mute" => false,
                ]
                ),
            ];

        $ch = curl_init($this->config->apiUrl);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);

        $path = $filename;
        $fp = fopen($path, 'rb');
        $filesize = filesize($path);

        curl_setopt($ch, CURLOPT_POSTFIELDS, fread($fp, $filesize));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $response;
    }
}