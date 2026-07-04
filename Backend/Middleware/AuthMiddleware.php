<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public static function verify()
    {
        $headers = getallheaders();

        if(!isset($headers['Authorization'])){
            http_response_code(401);
            exit;
        }

        $token = str_replace(
            "Bearer ",
            "",
            $headers['Authorization']
        );

        $decoded = JWT::decode(
            $token,
            new Key("secret_key",'HS256')
        );

        return $decoded;
    }
}