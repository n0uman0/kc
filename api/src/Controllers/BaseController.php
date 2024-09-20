<?php
namespace KnowledgeCity\Controllers;

class BaseController{

    protected function jsonResponse(int $response_code, $response = [])
    {
        header('Content-Type: application/json');
        http_response_code($response_code);
        echo json_encode($response);
        exit();
    }

}