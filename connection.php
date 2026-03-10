<?php
$host = getenv('DB_HOST');
$db   = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT') ?: 4000;
$charset = 'utf8mb4';

// Path to the downloaded CA certificate from TiDB Cloud
$ssl_ca = __DIR__ . '/isrgrootx1.pem';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,  // <— enable SSL
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // optional
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}