<?php

use Laminas\Text\Figlet\Figlet;

$rootDir = dirname(__DIR__, 2);
$backDir = dirname(__DIR__);
$envPath = $rootDir . '/.env';
$envExamplePath = $rootDir . '/.env.example';
$envExampleFile = file_get_contents($rootDir . '/.env.example');

// Get current user
exec('echo $USER', $user);
$user = $user[0];

// Get project folder
exec('dirname "$PWD"', $projectFolder);
$projectFolder = substr($projectFolder[0], strrpos($projectFolder[0], '/') + 1);

// Get devCluster
exec('hostname', $devCluster);
$devCluster = "dev" . substr($devCluster[0], -1);

// Load autoload
if (file_exists($backDir . '/vendor/autoload.php')) {
  require_once($backDir . '/vendor/autoload.php');
}

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable($rootDir);
if (file_exists($rootDir . '/.env')) {
  $envFile = file_get_contents($rootDir . '/.env');
  $dotenv->safeLoad();
}

function logo()
{
  $i = 0;
  while ($i < 100) {
    echo "\n";
    $i++;
  }
  $logo = new Figlet();
  echo $logo->render('KRONOS');
}

function generateRandomString($length = 10)
{
  return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

//? Start installation

logo();

function changeEnv($key, $value, $default)
{
  global $envFile;
  if (!str_contains(
    $envFile,
    sprintf($key . '="' . $value . '"', $value),
  )) {
    $envFile = str_replace(
      $key . '="' . $default . '"',
      sprintf($key . '="' . $value . '"', $value),
      $envFile
    );
  }
}

function changeEnvExample($key, $value, $default)
{
  global $envExampleFile;
  if (!str_contains(
    $envExampleFile,
    sprintf($key . '="' . $value . '"', $value),
  )) {
    $envExampleFile = str_replace(
      $key . '="' . $default . '"',
      sprintf($key . '="' . $value . '"', $value),
      $envExampleFile
    );
  }
}

if (!file_exists("$rootDir/.env")) {
  echo "No .env file has been found in your root directory. \nCopying and configuring a new .env file from .env.example \n\n";
  shell_exec("cp $rootDir/.env.example $rootDir/.env");
  // Load .env file
  $dotenv = Dotenv\Dotenv::createImmutable($rootDir);
  if (file_exists($rootDir . '/.env')) {
    $envFile = file_get_contents($rootDir . '/.env');
    $dotenv->safeLoad();
  }
}

//! APP_NAME
$name = readline('Enter your project name: ');
changeEnv('APP_NAME', $name, 'Kronos');

//! DB_NAME
$dbName = readline('Enter your database name : ');
changeEnv('DB_NAME', $dbName, 'project');

echo 'Project installation in progress...';

//! API_BASE_URL
if ($devCluster == 'dev7' || $devCluster == 'dev8') {
  changeEnv('API_BASE_URL', "http://$projectFolder.$user.$devCluster", 'http://project.tld');
} else {
  changeEnv('API_BASE_URL', "http://$projectFolder.$devCluster", 'http://project.tld');
}

//! BO_PASSWORD
if (!$_ENV['BO_PASSWORD']) {
  $password = generateRandomString(20);
  changeEnv('BO_PASSWORD', $password, '');

  // Change .env.example too
  changeEnvExample('BO_PASSWORD', $password, '');
  file_put_contents($envExamplePath, $envExampleFile);
} else {
  $password = $_ENV['BO_PASSWORD'];
}

// Save .env
file_put_contents($envPath, $envFile);

//? Create database if not exists
$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sql) === TRUE) {
  // echo "Database created successfully \n";
} else {
  echo "Error creating database: " . $conn->error . "\n";
}
$conn->close();

if (!file_exists("$backDir/index.php")) {
  shell_exec('wp core download --locale=fr_FR --skip-content');
}

shell_exec('wp core install --url=' . $_ENV['API_BASE_URL'] . ' --title=' . $_ENV['APP_NAME'] . ' --admin_user=' . $_ENV['BO_USER'] . ' --admin_password=' . $password . ' --admin_email=kronos@agoravita.com --skip-email');
shell_exec('git clone --depth 1 git@avonline2.agoravita.com:kronos-framework/front-end/head.git ../front && rm -rf ../front/.git && cd ../front && yarn install');
