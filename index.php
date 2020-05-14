<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use ServerBackup\Dropbox\Configuration;
use ServerBackup\Dropbox\UploadFile;
use ServerBackup\Database\Backup;
use Nette\Neon\Neon;

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/src/UploadFile.php');
require_once(__DIR__ . '/src/Configuration.php');
require_once(__DIR__ . '/src/Backup.php');

try {
    $neon = Neon::decode(file_get_contents(__DIR__. '/config.neon'));
} catch (\Nette\Neon\Exception $e) {
	die('Invaid config: ' . $e->getMessage(). "\n");
}

$configuration = new Configuration($neon['parameters']['dropboxToken']);
$backup = new Backup(__DIR__. $neon['parameters']['dataFolder'], $neon['parameters']['database'], true);
$upload = new UploadFile($configuration);

foreach ($neon['parameters']['databases'] as $key => $dbName) {
    $fileName = $backup->save($dbName);
    if (file_exists($fileName)) {
        $uploadResponse = $upload->upload($fileName);

        $json = json_decode($uploadResponse);

        if ($json->id) {
            unlink($fileName);
        }
    }
}