<?php

namespace ServerBackup\Database;

use MySQLDump;

class Backup {

    /** @var string $dataFolder */
    public $dataFolder;

    /** @var bool $compress */
    public $compress;

    /** @var array $database */
    public $database;

    public function __construct(string $dataFolder, array $database, $compress = false)
	{
        $this->dataFolder = $dataFolder;
        $this->compress = $compress;
        $this->database = $database;
    }   
    
    public function save(string $dbName): string
    {
        $db = new \mysqli($this->database['server'], $this->database['user'], $this->database['password'], $dbName);
        $dump = new MySQLDump($db);
        $date = new \DateTime();

        $fileName = $this->dataFolder . '/' .$date->format('Y-m-d-h-i-s'). '-' .  $dbName. '.sql' . ($this->compress ? '.gz' : '');

        $dump->save($fileName);
        $db->close(); 
        return $fileName;
    }

}