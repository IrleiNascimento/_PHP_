<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContatoControle
 *
 * @author Irlei
 */
require_once '../model/ControlDb.php';

class ContatoControle {

    //put your code here
    private $tipo;
    private $contato;

     public static function getArrayContato($tabela, $coluna, $id) {


        $query = ControlDb::getResultWhere($tabela, $coluna, $id,"=",null);
        $result = array();
        if ($query) {
            while ($row = mysql_fetch_array($query)) {
                $result[] = new Contato($row['telefone'], $row['id']);
            }
            return $result;
        }
    }

    public static function updateContato($tabela, Contato $contato, $id,$rel_id=null) {
        $result=false;
        if ($contato != null) {
            $query=null;
            $tel = $contato->getTelefone();
            $c_id = $contato->getId();
            if ($tel != null && $tel != '' && $c_id != 0) {
                $query = "UPDATE  $tabela SET
                telefone='" . Util::safe($contato->getTelefone()) . "'
                WHERE id=" . $contato->getId();
             $result =   ControlDb::update($query);

            } elseif ($c_id != 0) {
                $query = "DELETE FROM $tabela WHERE id=" . $contato->getId();
                ControlDb::delete($query);
            } elseif ($tel != null && $tel != '') {
                self::insertContato($tabela,$rel_id,$contato, $id);
            }
        }
        return  $result;
    }

    public static function insertContato($tabela,$rel_id, Contato $c, $id = 0) {
        if ($c) {
            $query = "INSERT INTO $tabela (telefone,$rel_id)VALUES
                    ('" . Util::safe($c->getTelefone()) . "'," . $id . ")";
            ControlDb::Insert($query);
            print_r(mysql_error());
        }
    }

}

?>