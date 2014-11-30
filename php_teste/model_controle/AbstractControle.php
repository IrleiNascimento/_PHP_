<?php

/**
 * Description of AbstractControle
 *
 * @author Irlei
 */
require_once '../model/upload_img.php';

abstract class AbstractControle {

    private $erros = array();
    private $id;

    abstract function getId();

    abstract function setId($id);

    abstract function getItem($array_post = null);

    abstract function setItem(Item $evento);

    abstract function getArrayErros();

    abstract function getArrayItem($id);

    abstract function getErros();

    abstract function salvar();

    abstract function delete($id);

    abstract function editar();

    abstract function pesquisa($id);

    abstract function validarItem();

    abstract function prepareSQLquery($tipo);

    abstract function setContatoItem($telefone_s, $id = 0);

    public function upload_imagem($file_img, $atr_name, $dir) {
        if (!empty($file_img[$atr_name]) && strlen($file_img[$atr_name]['name']) > 0) {
            $upload = new Upload($file_img[$atr_name], 140, 120, $dir);
            return $upload->salvar();
        }
    }

}

?>
