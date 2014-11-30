<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author Irlei
 */
class Login {

    private $senha;
    private $username;
    private $email;
    private $id;
    private $audit;

    function __construct($username, $email,$senha=null,$id=0, $audit=null) {
        $this->senha = $senha;
        $this->username = $username;
        $this->email= $email;
        $this->id = $id;
        $this->audit = $audit;
    }


    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

        public function getAudit() {
        return $this->audit;
    }

    public function setAudit($audit) {
        $this->audit = $audit;
    }


}

?>
