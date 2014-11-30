<?php

/**
 * Description of Usuario
 *
 * @author Irlei
 */
require_once 'ControlDb.php';
require_once 'Login.php';

class Usuario {

    private $id;
    private $nome;
    private $sobrenome;
    private $dt_nascimento;
    private $status;
    private $uf;
    private $cidade;
    private $endereco;
    private $telefone;
    private $img_perfil;
    private $login;

    function __construct($id, $nome, $sobrenome, $dt_nascimento, $status, $uf, $cidade, $endereco, $telefone = null, $img_perfil = null, Login $login = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->sobrenome = $sobrenome;
        $this->dt_nascimento = $dt_nascimento;
        $this->status = $status;
        $this->uf = $uf;
        $this->cidade = $cidade;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->img_perfil = $img_perfil;
        $this->login = $login;
    }

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

    public function getSobrenome() {
        return $this->sobrenome;
    }

    public function setSobrenome($sobrenome) {
        $this->sobrenome = $sobrenome;
    }

    public function getDt_nascimento() {
        return $this->dt_nascimento;
    }

    public function setDt_nascimento($dt_nascimento) {
        $this->dt_nascimento = $dt_nascimento;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin(Login $login) {
        $this->login = $login;
    }


    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getUf() {
        return $this->uf;
    }

    public function setUf($uf) {
        $this->uf = $uf;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function getImg_perfil() {
       
        return $this->img_perfil;
    }

    public function setImg_perfil($img_peril) {
        $this->img_perfil = $img_peril;
    }

}

?>
