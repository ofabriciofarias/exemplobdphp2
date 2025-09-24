<?php

namespace App\Model;

use PDO;

class Noticia extends BaseModel {
    private $id;
    private $titulo;
    private $texto;
    private $data_insercao;

    // Getters and Setters

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function getDataInsercao() {
        return $this->data_insercao;
    }

    public function setDataInsercao($data_insercao) {
        $this->data_insercao = $data_insercao;
    }

    // CRUD methods

    public function findAll() {
        $query = "SELECT * FROM noticias";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT * FROM noticias WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO noticias (titulo, texto) VALUES (:titulo, :texto)";
        $stmt = $this->db->prepare($query);

        // Clean data
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->texto = htmlspecialchars(strip_tags($this->texto));

        // Bind data
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':texto', $this->texto);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE noticias SET titulo = :titulo, texto = :texto WHERE id = :id";
        $stmt = $this->db->prepare($query);

        // Clean data
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->texto = htmlspecialchars(strip_tags($this->texto));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':texto', $this->texto);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM noticias WHERE id = :id";
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
