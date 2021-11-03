<?php

return [
    'auth' => function ($db, $request) {
        if (!$request['password']) {
            http_response_code(400);
            echo json_encode([
                'message' => "No password provided"
            ]);
        } else {
            $hash = hash('sha256', $request['password']);

            if (!$db->query(
                "SELECT * FROM admin_passwords WHERE `hash` = \"$hash\""
            )->fetchAll()) {
                http_response_code(403);
                echo json_encode([
                    'message' => "Invalid password"
                ]);
            } else {
                $token = bin2hex(random_bytes(16));
                $expires = date('Y-m-d H:i:s', time() + 3600);

                $db->query(
                    "INSERT INTO tokens (token, expires_at) VALUES ('$token', '$expires')"
                );

                echo json_encode([
                    'token' => $token
                ]);
            }
        }
    }
];