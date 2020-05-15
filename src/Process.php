<?php

declare(strict_types = 1);

namespace ServerBackup;

use ServerBackup\Database\Backup;
use ServerBackup\Dropbox\UploadFile;

class Process {

	/** @var Configuration $configuration */
	private $configuration;

	/** @var Backup $backup */
	private $backup;

	/** @var UploadFile $dropboxUpload */
	private $dropboxUpload;

	public function __construct(string $configFile, string $rootDir)
	{
		$this->configuration = new Configuration($configFile);
		$this->backup = new Backup($this->configuration, $rootDir);
		$this->dropboxUpload = new UploadFile($this->configuration->dropbox);
	}

	public function run(): void
	{
		foreach ($this->configuration->databases as $key => $dbName) {
			$filePath = $this->backup->save($dbName);
			if ($filePath && file_exists($filePath)) {
				$uploadResponse = $this->dropboxUpload->upload($filePath);
				if ($uploadResponse && $uploadResponse->id) {
					$this->backup->clean();
				}
			}
		}
	}
}
