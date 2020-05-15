<?php

declare(strict_types = 1);

namespace ServerBackup\Dropbox;

use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use ServerBackup\Exception;

class Configuration {

	/** @var array<string, string|int|bool> $defaults */
	public $defaults = [
	    'dateFolders' => true,
	    'verbose' => false,
	];

	/** @var string $apiUrl */
	public $apiUrl;

	/** @var string $token */
	public $token;

	/** @var bool $dateFolders */
	public $dateFolders;

	/** @var bool $verbose */
	public $verbose;

	/**
	 * @param array<string, string> $config
	 */
	public function __construct(array $config, string $apiUrl = 'https://content.dropboxapi.com/2/files/upload')
	{
		$this->apiUrl = $apiUrl;

		try {
			Validators::assert($config['token'], 'string');
		} catch (AssertionException $th) {
			throw new Exception('Dropbox token is missing in configuration file. See config.neon.example.');
		}

		if (!isset($config['dateFolders']) || !Validators::is($config['dateFolders'], 'bool')) {
			$config['dateFolders'] = $this->defaults['dateFolders'];
		}

		if (!isset($config['verbose']) || !Validators::is($config['verbose'], 'bool')) {
			$config['verbose'] = $this->defaults['verbose'];
		}

		$this->token = $config['token'];
		$this->dateFolders = $config['dateFolders'];
		$this->verbose = $config['verbose'];
	}
}
