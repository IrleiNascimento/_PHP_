<?php

/**
 * Description of Item
 *
 * @author Irlei
 */
require_once 'Item.php';

class Evento extends Item {

    const CULTURA = 1;
    const FESTA = 2;

    //put your code here
    function __construct($id, $titulo, $descricao, $dataTime,$uf, $cidade, $endereco, $latitude, $logitude, $img_url, $link, $rede_social,$email, $tipo, $contato=null) {
    parent::__construct($id, $titulo, $descricao, $dataTime, $uf,$cidade, $endereco, $latitude, $logitude, $img_url, $link, $rede_social, $email,$tipo,$contato);
    }

    static function getTipoFrom($int_value) {
        switch ($int_value) {
            case self::CULTURA:
                return 'Cultura';
                break;
            case self::FESTA:
                return 'Festa';
                break;
            default :
                return'';
        }
    }

}

?>
