<?php

/**
 * Description of Contato
 *
 * @author Irlei
 */
class Contato {

    private $id;
    private $telefone;

    public function __construct($telefone, $id = 0) {
        $this->telefone = $telefone;
        $this->id = $id;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function getContato(array $array) {
        if ($array) {
            return new Contato(
                            $array['telefone'],
                            $array['id']);
        }
        return null;
    }

    public function getId(){
        return $this->id;
    }

    public  function getArrayContato() {
        return array(
            'id' => $this->id,
            'telefone' => $this->telefone);
    }

}

?>
