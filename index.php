<?php

declare(strict_types = 1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use ServerBackup\Process;

require_once(__DIR__ . '/vendor/autoload.php');

require_once(__DIR__ . '/src/Exception.php');
require_once(__DIR__ . '/src/Dropbox/UploadFile.php');
require_once(__DIR__ . '/src/Dropbox/Configuration.php');
require_once(__DIR__ . '/src/Database/Backup.php');
require_once(__DIR__ . '/src/Configuration.php');
require_once(__DIR__ . '/src/Process.php');

$process = new Process(__DIR__ . '/config/config.neon', __DIR__);
$process->run();
