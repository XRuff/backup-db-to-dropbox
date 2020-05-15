<?php

declare(strict_types = 1);

namespace ServerBackup;

use Nette\Neon;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use ServerBackup\Dropbox\Configuration as DropboxConfig;

class Configuration {

	/** @var array<string, string|int|bool> $defaults */
	public $defaults = [
	'compress' => true,
	'dateFormat' => 'Y-m-d-h-i-s',
	'verbose' => false,
	];

	/** @var DropboxConfig $dropbox */
	public $dropbox;

	/** @var array<string, string> $database */
	public $database;

	/** @var array<int, string> $databases */
	public $databases;

	/** @var bool $compress */
	public $compress;

	/** @var string $dateFormat */
	public $dateFormat;

	/** @var string $dataFolder */
	public $dataFolder;

	public function __construct(string $congifPath)
	{
		try {
			$content = file_get_contents($congifPath);
			$config = Neon\Neon::decode($content ? $content : '');
		} catch (Neon\Exception $e) {
			die('Invaid config: ' . $e->getMessage() . "\n");
		}

		Validators::assert($config['parameters'], 'array');

		$params = $config['parameters'];

		try {
			Validators::assertField($params, 'dropbox', 'array');
		} catch (AssertionException $th) {
			var_dump($params['dropbox']);
			throw new Exception('Dropbox part is missing in configuration file. See config.neon.example.');
		}

		try {
			Validators::assertField($params, 'databases', 'array');
		} catch (AssertionException $th) {
			throw new Exception('Collection of databases names is missing in configuration file. See config.neon.example.');
		}

		Validators::assert($params['databases'], 'array');

		if (!isset($params['compress']) || !Validators::is($params['compress'], 'bool')) {
			$params['compress'] = $this->defaults['compress'];
		}

		if (!isset($params['dateFormat']) || !Validators::is($params['dateFormat'], 'bool')) {
			$params['dateFormat'] = $this->defaults['dateFormat'];
		}

		$this->dropbox = new DropboxConfig($params['dropbox']);

		$this->database = $params['database'];
		$this->compress = $params['compress'];
		$this->dateFormat = $params['dateFormat'];
		$this->dataFolder = $params['dataFolder'];
		$this->databases = $params['databases'];
	}

}
