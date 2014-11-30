<?php

require_once '../model/conexao.php';

/**
 * Description of Util
 *
 * @author Irlei
 */
class Util {

   //const DOMINIO = 'http://www.missaocriativa.com.br/';
    const DOMINIO = 'http://localhost/';

    //const DIR_APP ='http://www.missaocriativa.com.br/c_app/';
    const DIR_APP ='http://localhost/backup/c_app/';

     // const PAG_LOGIN = 'www.missaocriativa.com.br/c_app/page/login.phtml';
    
    const PAG_LOGIN ='localhost/backup/c_app/page/login.phtml';
    const DIR_IMAGEM_SERVICO = "../imagens/sv/";
    const DIR_IMAGEM_EVENTOS = "../imagens/ev/";
    const DIR_IMAGEM_USUARIO = "../imagens/u/";
    const IMAGEM_SISTEM = "../imagens/sistem/";
    const IMAGEM_PADRAO_EVENTO = "padrao_img_evento.jpg";
    const IMAGEM_PADRAO_SERVICO = "padrao_img_evento.jpg";
    const IMAGEM_PADRAO_USUARIO = "padrao_perfil.jpg";
    const DIR_IMAGEM_ITEM_PADRAO ="../imagens/sistem/padrao_img_evento.jpg";
    const DIR_IMAGEM_USUARIO_PADRAO ="../imagens/sistem/padrao_perfil.jpg";
    const IMG_SIZE = 88192;
    //mensagens   de alerta e confimarção
    const ERRO_LOGIN = 0;
    const ERRO_AUTENTC_LOGIN = 1;
    const SUCESS_DADOS_INSERT = 3;
    const ERRO_DADOS_INSERT = 4;
    const SUCESS_DADOS_EDIT = 5;
    const SUCESS_DADOS_LOGIN_EDIT = 6;

    private static $uf = array(
        "----",
        "Acre",
        "Alagoas",
        "Amazonas",
        "Amap&aacute;",
        "Bahia",
        "Cear&aacute;",
        "Distrito Federal",
        "Esp&iacute;rito Santo",
        "Goi&agrave;s",
        "Maranh&atilde;o",
        "Minas Gerais",
        "Mato Grosso do Sul",
        "Mato Grosso",
        "Par&aacute",
        "Para&iacuteba",
        "Pernambuco",
        "Piau&iacute;",
        "Paran&aacute;",
        "Rio de Janeiro",
        "Rio Grande do Norte",
        "Rond&ocirc;nia",
        "Roraima",
        "Rio Grande do Sul",
        "Santa Catarina",
        "Sergipe",
        "S&atilde;o Paulo",
        "Tocantins");

    public static function formatarData($data, $i = 0) {
        $hora = '';
        $rData = '';

        if (strstr($data, " ")) {
            $tmp = explode(" ", $data);
            $data = $tmp[0];
            $hora = $tmp[1];
        }

        if (strstr($data, "/"))
            $rData = implode("-", array_reverse(explode("/", trim($data))));
        if (strstr($data, "-"))
            $rData = implode("/", array_reverse(explode("-", trim($data))));

        return $i != 0 ? $rData . ' ' . $hora : $rData;
    }

    public static function crearLink($pagina, array $params = array()) {
        if ($pagina != null) {
            return $pagina . '?' . http_build_query($params);
        } else {

            return'#';
        }
    }

    public static function safe($value) {
        return mysql_escape_string($value);
    }

    public static function obtercidades($uf) {
        $con = Conexao::conectar(Conexao::LOCALIDADES_ControlDb);
        $execute = "SELECT * FROM cidade WHERE estado=" . $uf." ORDER BY nome";
        $query = mysql_query($execute, $con);
        $result = '<option id="cid_0" value="0">-----</option>';
        if ($query) {
            while ($row = mysql_fetch_array($query)) {
                $id = $row['id'];
                $nome = $row['nome'];
                $cid_id = 'cid' . $id;
                $result.= '<option id="' . $cid_id . '" value="' . $id . '">' . $nome . '</option>"';
            }
            return $result;
        }
        return $result;
    }

    public static function emailExiste($email, $id = 0) {
        $con = Conexao::conectar();
        $where = '';
        if ($id != 0 && is_numeric($id)) {
            $where = " AND usuario_id <> $id";
        }
        $execute = "SELECT email FROM login WHERE email='" . $email . "'" . $where;

        $result = mysql_query($execute, $con);

        return mysql_num_rows($result) > 0;
    }

    public static function getUF($index) {
        if ($index)
            return self::$uf[$index];
    }

    public static function getCidadeFromID($id) {
        if ($id) {
            $con = Conexao::conectar(Conexao::LOCALIDADES_ControlDb);
            $execute = "SELECT nome FROM cidade WHERE id=$id";
            $query = mysql_query($execute, $con);
            if ($query) {
                while ($row = mysql_fetch_array($query)) {
                    return $row['nome'];
                }
            }
            return self::$uf[$index];
        }
    }

    public static function validarEmail($email, $id = 0, $dist = false) {
        //verificar se e-mail esta no formato correto de escrita
        $ereg = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
        if (!preg_match($ereg, $email)) {
            $mensagem = 'E-mail Inválido!';
            return $mensagem;
        } else {
            //print_r($email);
            $dominio = explode('@', $email);
            if (!checkdnsrr($dominio[1])) {
                $mensagem = "E-mail Inválido!  [$dominio[1]]";
                return $mensagem;
            } elseif (self::emailExiste($email, $id)) {
                return "$email ja existe ! ";
            } else {
                return true;
            }// Retorno true para indicar que o e-mail é valido
        }
    }

    public static function log($msg, $code = null) {
        if (!$code)
            $code = '';
        var_dump('<br>[' . $msg . ' : ' . $code . ']');
    }

    public static function msg_box($tipo) {
        switch ($tipo) {
            case self::ERRO_AUTENTC_LOGIN:
                return '<i class="erro">**Houve um problema na autenticação. [ Senha incorreta ].<i/>';
                break;
            case self::ERRO_LOGIN:
                return '<i class="erro">**Nome de usuário ou Senha incorretos!<i/>';
                break;
            case self::SUCESS_DADOS_INSERT:
                return '<i class="sucess">**Os dados foram inseridos com sucesso.<i/>';
                break;
            case self::ERRO_DADOS_INSERT:
                return '<i class="erro">**Não foi possivel completar o cadastro.<i/>';
                break;
            case self::SUCESS_DADOS_EDIT:
                return '<i class="sucess">**Os dados foram atualizados.<i/>';
                break;
            case self::SUCESS_DADOS_LOGIN_EDIT:
                return '<i class="sucess">**Os dados de login foram atualizados.<i/>';
                break;
            default:
                break;
        }
    }

    //reduzir uma string para vizualizaçõés previas
    public static function min_str($str, $limit) {
        if ($str) {
            if (strlen($str) > $limit) {
                return substr($str, 0, $limit) . "...";
            }
            return $str;
        }
    }

    public static function post($url, $fields) {
        if ($url) {
            //url-ify the data for the POST
            $fields_string = '';
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
//open connection
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            $result = curl_exec($ch);
//fecha a conexão
            if (!$result) {
                return curl_error($ch);
            }
            curl_close($ch);
            return $result;
        }
        return null;
    }

}

?>