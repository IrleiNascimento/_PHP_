<?php
/**
 * 
 *
 * @author Irlei
 */
require_once '../model/ControlDb.php';
require_once '../model/Error.php';
require_once '../model/Login.php';

class LoginControle {

    private static $erros = array();

    public static function insertLogin(Login $login) {
        self::validarLogin($login);
        if (empty(self::$erros)) {
            $query = "INSERT INTO login (username,email,senha,usuario_id)VALUES
                    ('" . Util::safe($login->getUsername()) . "','"
                    . Util::safe($login->getEmail()) . "','"
                    . Util::safe($login->getSenha()) . "',"
                    . $login->getId() . ")";
            $result = ControlDb::Insert($query);
            if (!$result)
                self::$erros[] = new Error('Login', ' Erro ao inserior dados de login :' . $result);
        }
        return self::$erros;
    }

    public static function updateLogin(Login $login) {
        self::validarLogin($login);
        if (empty(self::$erros)) {
            $query = "UPDATE  login SET
            username='" . Util::safe($login->getUsername()) . "',
                 email='" . Util::safe($login->getEmail()) . "',
            senha='" . Util::safe($login->getSenha()) . "'
                WHERE usuario_id=" . $login->getId();
            if (!ControlDb::update($query)) {
                self::$erros[] = new Error('UPDATE LOGIN :', $query);
                self::$erros[] = new Error('UPDATE LOGIN :', mysql_error());
                return self::$erros;
            }
        } else {
            return self::$erros;
        }
    }

    public static function validarLogin(Login $login) {
        if ($login != null) {
            if (!trim($login->getUsername()))
                self::$erros[] = new Error('Username', 'O nome de usuario é invalido');
            if (!trim($login->getEmail())) {
                self::$erros[] = new Error('Email', 'O email parece estar incorreto');
            }
            if (!trim($login->getSenha()))
                self::$erros[] = new Error('Senha', 'Entrada de senha invalida');
        }
    }

    public static function getArray($id, $completo = false) {
        if ($id) {
            $colunas = 'username,email,usuario_id';
            if ($completo)
                $colunas = 'username,email,senha,usuario_id';


            $query = "SELECT $colunas FROM login WHERE usuario_id=$id";
            return ControlDb::select($query);
        }
        return null;
    }

    public static function getLogin($id, $completo = false) {
        if ($id) {
            $array = self::getArray($id, $completo);
            if ($array) {
                $result = new Login($array['username'], $array['email'], null, $array['usuario_id']);
                if ($completo)
                    $result->setSenha($array['senha']);
                return $result;
            }
            return null;
        }
        return null;
    }


}

?>
