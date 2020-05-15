<?php

declare(strict_types = 1);

namespace ServerBackup\Dropbox;

class UploadFile {

	/** @var Configuration $config */
	public $config;

	/** @var string $filename */
	public $filename;

	public function __construct(Configuration $config)
	{
		$this->config = $config;
	}

	/**
	 * @return mixed json decoded bropbox response
	 */
	public function upload(string $filename)
	{
		$this->filename = $filename;
		$headers = [
			'Authorization: Bearer ' . $this->config->token,
			'Content-Type: application/octet-stream',
			'Dropbox-API-Arg: ' .
			json_encode([
				'path' => $this->getFilePath(),
				'mode' => 'add',
				'autorename' => true,
				'mute' => false,
			]),
		];

		$ch = curl_init($this->config->apiUrl);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);

		$path = $filename;
		$fp = fopen($path, 'rb');
		$filesize = filesize($path);

		curl_setopt($ch, CURLOPT_POSTFIELDS, fread($fp, $filesize));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ($this->config->verbose) {
			curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug
		}

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return $response ? json_decode($response) : null;
	}

	public function getFilePath(): string
	{
		$folder = '';
		if ($this->config->dateFolders) {
			$dateTime = new \DateTime();
			$folder = '/' . $dateTime->format('Y-m-d');
		}

		return $folder . '/' . basename($this->filename);
	}
}
