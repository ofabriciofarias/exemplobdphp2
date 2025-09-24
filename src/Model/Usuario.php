<?php

namespace App\Model;

use PDO;

class Usuario extends BaseModel {
    private $id;
    private $nome;
    private $login;
    private $senha;
    private $tipo_usuario;
    private $data_criacao;

    // Getters and Setters

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getTipoUsuario() {
        return $this->tipo_usuario;
    }

    public function setTipoUsuario($tipo_usuario) {
        $this->tipo_usuario = $tipo_usuario;
    }

    public function getDataCriacao() {
        return $this->data_criacao;
    }

    public function setDataCriacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    // CRUD methods

    public function findAll() {
        $query = "SELECT id, nome, login, tipo_usuario, data_criacao FROM usuarios";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT id, nome, login, tipo_usuario, data_criacao FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByLogin($login) {
        $query = "SELECT * FROM usuarios WHERE login = :login";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO usuarios (nome, login, senha, tipo_usuario) VALUES (:nome, :login, :senha, :tipo_usuario)";
        $stmt = $this->db->prepare($query);

        // Clean data
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);
        $this->tipo_usuario = htmlspecialchars(strip_tags($this->tipo_usuario));

        // Bind data
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':senha', $this->senha);
        $stmt->bindParam(':tipo_usuario', $this->tipo_usuario);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE usuarios SET nome = :nome, login = :login, tipo_usuario = :tipo_usuario WHERE id = :id";
        $stmt = $this->db->prepare($query);

        // Clean data
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->tipo_usuario = htmlspecialchars(strip_tags($this->tipo_usuario));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':tipo_usuario', $this->tipo_usuario);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);

        // Clean data
        $id = htmlspecialchars(strip_tags($id));

        // Bind data
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
