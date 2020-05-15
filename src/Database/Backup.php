<?php

declare(strict_types = 1);

namespace ServerBackup\Database;

use MySQLDump;
use ServerBackup\Configuration;

class Backup {

	/** @var string $dataFolder */
	public $dataFolder;

	/** @var bool $compress */
	public $compress;

	/** @var array<string, string> $database */
	public $database;

	/** @var string $filePath */
	private $filePath;

	/** @var mixed $db */
	private $db;

	public function __construct(Configuration $config, string $rootDir)
	{
		$this->dataFolder = $rootDir . $config->dataFolder;
		$this->compress = $config->compress;
		$this->database = $config->database;
	}

	public function save(string $dbName): ?string
	{
		if (!$dbName) {
			return null;
		}

		$dump = $this->getDumper($dbName);

		$this->filePath = $this->getFilePath($dbName);

		$dump->save($this->filePath);
		$this->db->close();
		return $this->filePath;
	}

	public function clean(): void
	{
		unlink($this->filePath);
	}

	private function getDumper(string $dbName): MySQLDump
	{
		$this->db = new \mysqli($this->database['server'], $this->database['user'], $this->database['password'], $dbName);
		return new MySQLDump($this->db);
	}

	private function getFilePath(string $dbName): string
	{
		$dateTime = new \DateTime();
		$extension = '.sql' . ($this->compress ? '.gz' : '');
		return $this->dataFolder . '/' . $dateTime->format('Y-m-d-h-i-s') . '-' . $dbName . $extension;
	}

}
