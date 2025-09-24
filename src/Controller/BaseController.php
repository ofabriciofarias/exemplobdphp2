<?php

namespace App\Controller;

abstract class BaseController {

    protected function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function getRequestData() {
        return json_decode(file_get_contents('php://input'), true);
    }

    protected function render($view, $data = []) {
        extract($data);
        include __DIR__ . "/../View/{$view}.php";
    }
}