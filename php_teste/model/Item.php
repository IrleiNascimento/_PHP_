<?php

/**
 * Description of Item
 *
 * @author Irlei
 */
class Item {

    private $id;
    private $tipo;
    private $titulo;
    private $descricao;
    private $img_url;
    private $uf;
    private $cidade;
    private $link;
    private $rede_social;
    private $email;
    private $contato;
    private $dataTime;
    private $endereco;
    private $latitude;
    private $logitude;
    private $user_id;

    public function __construct($id, $titulo, $descricao, $dataTime, $uf, $cidade, $endereco, $latitude, $logitude, $img_url, $link, $rede_social, $email, $tipo, $contato = null) {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->img_url = $img_url;
        $this->uf = $uf;
        $this->cidade = $cidade;
        $this->link = $link;
        $this->email = $email;
        $this->rede_social = $rede_social;
        $this->dataTime = $dataTime;
        $this->endereco = $endereco;
        $this->latitude = $latitude;
        $this->logitude = $logitude;
        $this->contato = $contato;
    }

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

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getUf() {
        return $this->uf;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getRede_social() {
        return $this->rede_social;
    }

    public function setRede_social($rede_social) {
        $this->rede_social = $rede_social;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    public function setUf($uf) {
        $this->cidade = $uf;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    public function getLogitude() {
        return $this->logitude;
    }

    public function setLogitude($logitude) {
        $this->logitude = $logitude;
    }

    public function getLink() {
        return $this->link;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function getContato() {
        return $this->contato;
    }

    public function setContato($contato) {
        $this->contato = $contato;
    }

    public function getDataTime() {
        return $this->dataTime;
    }

    public function setDataTime($dataTime) {
        $this->dataTime = $dataTime;
    }

    public function getImg_url() {
        return $this->img_url;
    }

    public function setImg_url($img_url) {
        $this->img_url = $img_url;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

}

?>
