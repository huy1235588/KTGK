<?php
require 'env.php';

return [
    'app_name' => 'Ha',
    'base_url' => $_ENV['BASE_URL'], // Địa chỉ gốc
    'debug' => true,

    // Cấu hình cơ sở dữ liệu
    'database' => [
        'host' => $_ENV['DB_HOST'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'name' => $_ENV['DB_NAME'],
        'charset' => 'utf8mb4'
    ]
];
