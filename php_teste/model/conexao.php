<?php
/**
 * Description of Conexao
 *
 * @author Irlei
 */
class Conexao {

     const PADRAO_ControlDb = "nome_do_banco";
    const LOCALIDADES_ControlDb = "nome_do_banco";

    public static function conectar($db = null) {
         $conectar = mysql_connect("host", "usuario", "senha");

        if ($db) {
            mysql_select_db($db, $conectar);
        } else {
            mysql_select_db(self::PADRAO_ControlDb, $conectar);
        }
        if (!$conectar)
            header("Location: ../login.phtml?err=" . mysql_error() . '###' . $conectar);
        mysql_set_charset('utf8');
        return $conectar;
    }

}?>