<?php

function get_db($host, $port, $db, $user, $pass) {
    return new PDO(
        'mysql:host='.$host.';port='.$port.';dbname='.$db,
        $user,
        $pass
    );
}