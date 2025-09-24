<?php

namespace App\Controller;

use App\Model\Usuario; // Import the Usuario model

class HomeController extends BaseController {
    public function index() {
        $data = [];
        if (isset($_SESSION['user_id'])) { // Assuming 'user_id' is the session key
            $usuarioModel = new Usuario();
            $user = $usuarioModel->findById($_SESSION['user_id']);
            if ($user) {
                $data['loggedInUser'] = $user['nome'];
            }
        }
        $this->render('home/index', $data);
    }
}