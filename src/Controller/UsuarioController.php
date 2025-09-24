<?php

namespace App\Controller;

use App\Model\Usuario;

class UsuarioController extends BaseController {

    private $usuarioModel;

    // Define constants for session keys
    const SESSION_USER_ID = 'user_id';
    const SESSION_USER_TIPO = 'user_tipo';

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        // Use $_POST for form submissions
        $data = $_POST;

        // Basic input validation
        if (!isset($data['login']) || !isset($data['senha'])) {
            // For form submissions, redirect back with an error
            $_SESSION['error_message'] = 'Login e senha são obrigatórios.';
            header('Location: /exemplobdphp2/login');
            exit();
        }

        $user = $this->usuarioModel->findByLogin($data['login']);

        if ($user && password_verify($data['senha'], $user['senha'])) {
            $_SESSION[self::SESSION_USER_ID] = $user['id'];
            $_SESSION[self::SESSION_USER_TIPO] = $user['tipo_usuario'];
            // For successful login, redirect to home or a dashboard
            header('Location: /exemplobdphp2/');
            exit();
        } else {
            $_SESSION['error_message'] = 'Credenciais inválidas.';
            header('Location: /exemplobdphp2/login');
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /exemplobdphp2/'); // Redirect to home page after logout
        exit();
    }

    public function registerForm() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error_message'] = 'Para acessar o cadastro, você precisa estar logado.';
            header('Location: /exemplobdphp2/login');
            exit();
        }
        $this->render('usuario/register');
    }

    public function register() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error_message'] = 'Para efetuar o cadastro, você precisa estar logado.';
            header('Location: /exemplobdphp2/login');
            exit();
        }
        // Use $_POST for form submissions
        $data = $_POST;

        if (!isset($data['nome']) || !isset($data['email']) || !isset($data['senha'])) {
            $this->render('usuario/register', ['error' => 'Todos os campos são obrigatórios.']);
            return;
        }

        // Assuming 'email' is used as 'login' for the Usuario model
        $this->usuarioModel->setNome($data['nome']);
        $this->usuarioModel->setLogin($data['email']); // Using email as login
        $this->usuarioModel->setSenha($data['senha']);
        $this->usuarioModel->setTipoUsuario('user'); // Default type for new registrations

        if ($this->usuarioModel->create()) {
            $this->render('usuario/register', ['success' => 'Usuário cadastrado com sucesso!']);
        } else {
            $this->render('usuario/register', ['error' => 'Erro ao cadastrar usuário. Tente novamente novamente.']);
        }
    }

    public function loginForm() {
        $data = [];
        if (isset($_SESSION['error_message'])) {
            $data['error'] = $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Clear the message after displaying
        }
        $this->render('usuario/login', $data);
    }

    private function isLoggedIn() {
        return isset($_SESSION[self::SESSION_USER_ID]);
    }

    private function isAdmin() {
        return isset($_SESSION[self::SESSION_USER_TIPO]) && $_SESSION[self::SESSION_USER_TIPO] === 'admin';
    }

    public function index() {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        $this->sendResponse($this->usuarioModel->findAll());
    }

    public function show($id) {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        $this->sendResponse($this->usuarioModel->findById($id));
    }

    public function create() {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        $data = $this->getRequestData();

        // More robust validation needed here
        if (!isset($data['nome']) || !isset($data['login']) || !isset($data['senha']) || !isset($data['tipo_usuario'])) {
            $this->sendResponse(['error' => 'Missing required fields for user creation'], 400);
            return;
        }

        $this->usuarioModel->setNome($data['nome']);
        $this->usuarioModel->setLogin($data['login']);
        $this->usuarioModel->setSenha($data['senha']); // Password will be hashed in the model
        $this->usuarioModel->setTipoUsuario($data['tipo_usuario']);

        if ($this->usuarioModel->create()) {
            $this->sendResponse(['message' => 'Usuario created'], 201);
        } else {
            $this->sendResponse(['error' => 'Error creating usuario'], 500);
        }
    }

    public function update($id) {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        $data = $this->getRequestData();

        // More robust validation needed here
        if (!isset($data['nome']) || !isset($data['login']) || !isset($data['tipo_usuario'])) {
            $this->sendResponse(['error' => 'Missing required fields for user update'], 400);
            return;
        }

        $this->usuarioModel->setId($id);
        $this->usuarioModel->setNome($data['nome']);
        $this->usuarioModel->setLogin($data['login']);
        $this->usuarioModel->setTipoUsuario($data['tipo_usuario']);

        if ($this->usuarioModel->update()) {
            $this->sendResponse(['message' => 'Usuario updated']);
        } else {
            $this->sendResponse(['error' => 'Error updating usuario'], 500);
        }
    }

    public function delete($id) {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        if ($this->usuarioModel->delete($id)) {
            $this->sendResponse(['message' => 'Usuario deleted']);
        } else {
            $this->sendResponse(['error' => 'Error deleting usuario'], 500);
        }
    }
}