<?php

namespace App\Controller;

use App\Model\Noticia;

class NoticiaController extends BaseController {

    private $noticiaModel;

    public function __construct() {
        $this->noticiaModel = new Noticia();
    }

    private function isLoggedIn() {
        return isset($_SESSION[UsuarioController::SESSION_USER_ID]);
    }

    public function index() {
        $noticias = $this->noticiaModel->findAll();
        $this->render('noticias/index', ['noticias' => $noticias]);
    }

    public function show($id) {
        $this->sendResponse($this->noticiaModel->findById($id));
    }

    public function create() {
        if (!$this->isLoggedIn()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        $data = $this->getRequestData();
        $this->noticiaModel->setTitulo($data['titulo']);
        $this->noticiaModel->setTexto($data['texto']);

        if ($this->noticiaModel->create()) {
            $this->sendResponse(['message' => 'Noticia created'], 201);
        } else {
            $this->sendResponse(['error' => 'Error creating noticia'], 500);
        }
    }

    public function update($id) {
        if (!$this->isLoggedIn()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        $data = $this->getRequestData();
        $this->noticiaModel->setId($id);
        $this->noticiaModel->setTitulo($data['titulo']);
        $this->noticiaModel->setTexto($data['texto']);

        if ($this->noticiaModel->update()) {
            $this->sendResponse(['message' => 'Noticia updated']);
        } else {
            $this->sendResponse(['error' => 'Error updating noticia'], 500);
        }
    }

    public function delete($id) {
        if (!$this->isLoggedIn()) {
            $this->sendResponse(['error' => 'Unauthorized'], 403);
            return;
        }
        if ($this->noticiaModel->delete($id)) {
            $this->sendResponse(['message' => 'Noticia deleted']);
        } else {
            $this->sendResponse(['error' => 'Error deleting noticia'], 500);
        }
    }
}