<?php

function require_and_check_auth($db, $request) {
    $now = date('Y-m-d H:i:s');

    if ($token = $request['token']) {
        return $db->query("SELECT * FROM tokens WHERE token = '$token' AND expires_at > '$now'")->fetch();
    }
}

return [
    'list' => function ($db, $request) {
        return json_encode($db->query(
            "SELECT * FROM movies ORDER BY movie_id, watched_on"
        )->fetchAll());
    },
    'markWatched' => function ($db, $request) {
        if (!require_and_check_auth($db, $request)) {
            http_response_code(401);
            return json_encode([
                'message' => 'You are not permitted to complete this action'
            ]);
        }
        
        $movie_id = $request['movie_id'];
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $result = $db->query(
            "UPDATE movies SET watched_on = \"$now\" WHERE movie_id = $movie_id"
        );

        if (!$result) {
            http_response_code(500);
            return json_encode($db->errorInfo());
        } else {
            return true;
        }
    },
    'add' => function ($db, $request) {
        if (!require_and_check_auth($db, $request)) {
            http_response_code(401);
            return json_encode([
                'message' => 'You are not permitted to complete this action'
            ]);
        }

        $title = $request['title'];
        $result = $db->query(
            "INSERT INTO movies (title) VALUES ('$title')"
        );

        if (!$result) {
            http_response_code(500);
            return json_encode($db->errorInfo());
        } else {
            return true;
        }
    }
];