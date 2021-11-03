<?php
// This is not a full migrations system
// This is just all the queries necessary to bootstrap the database

require_once('config/db.php');
require_once('secret/variables.php');

$db = get_db(
    DATABASE_CONNECTION_INFO['host'],
    DATABASE_CONNECTION_INFO['port'],
    DATABASE_CONNECTION_INFO['db'],
    DATABASE_CONNECTION_INFO['user'],
    DATABASE_CONNECTION_INFO['pass'],
);

$db->beginTransaction();

$result = $db->query(
    "CREATE TABLE movies (
        movie_id INT NOT NULL AUTO_INCREMENT,
        title VARCHAR(255),
        watched_on DATETIME,
        PRIMARY KEY (movie_id)
    )"
);

if (!$result) {
    var_dump($db->errorInfo());
    $db->rollBack();
    exit();
}

$result = $db->query(
    "CREATE TABLE admin_passwords (
        admin_password_id INT NOT NULL AUTO_INCREMENT,
        hash VARCHAR(255),
        PRIMARY KEY (admin_password_id)
    )"
);

if (!$result) {
    var_dump($db->errorInfo());
    $db->rollBack();
    exit();
}

$result = $db->query(
    "CREATE TABLE tokens (
        token_id INT NOT NULL AUTO_INCREMENT,
        token VARCHAR(255),
        expires_at DATETIME NOT NULL,
        PRIMARY KEY (token_id)
    )"
);

if (!$result) {
    var_dump($db->errorInfo());
    $db->rollBack();
    exit();
}

$db->commit();