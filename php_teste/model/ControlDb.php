<?php
/**
 * Description of ControlDb
 *
 * @author Irlei
 */
require_once 'conexao.php';
require_once '../util/Util.php';

class ControlDb {
//put your code here

    const INSERT = 1;
    const UPDATE = 2;
    const DELETE = 3;
    const SELECT = 4;
    public static function Insert($query, $max_id = false) {
        $conectar = Conexao::conectar();
        $result = mysql_query($query, $conectar);
        if ($result) {
            if ($max_id) {
                $result = mysql_insert_id($conectar);
            }
            return $result;
        }
        return null;
    }

    public static function inciaCbox($tabela, $key) {
        $combo = "";
        $result = self::getAllResult($tabela, 0);
        if ($result)
            foreach ($result['item'] as $r) {
                $id = $r['id'];
                $combo .='<option value="' . $id . '">' . $r["$key"] . '</option>';
            }
        echo $combo;
    }

    public static function update($query) {
        $con = Conexao::conectar();
        $result = mysql_query($query, $con);
        if (!$result)
            return mysql_error() . ' >> ' . $query;
        return $result;
    }

    public static function delete($query) {
        $con = Conexao::conectar();
        return mysql_query($query, $con);
    }

    public static function select($query) {
        if ($query != null) {
            $conectar = Conexao::conectar();
            $result = mysql_query($query, $conectar);
            if ($result) {
                return mysql_fetch_array($result);
            }
        }
        return null;
    }

    public static function countPaginas($tabelas,$where,$max_pg) {
        $query ="select * from $tabelas $where";
        $conectar = Conexao::conectar();
        $result = mysql_query($query, $conectar);
        if($result){
            $nun_rows=mysql_num_rows($result);
            if($nun_rows>0){
                $num=ceil( $nun_rows/ $max_pg);
                 //print_r($query."  RESUTADO =".$num);
          return  $num;
            }
        }
        return 0;
    }

    public static function count($query) {
        $conectar = Conexao::conectar();
        $result = mysql_query($query, $conectar);
        return mysql_num_rows($result);
    }

    public static function getMaxId($tabela, $where='') {

        $query = "SELECT MAX(id) FROM $tabela  $where";
        $conectar = Conexao::conectar();
        $result = mysql_query($query, $conectar);
        if($result){
            $max = mysql_fetch_row($result);
        return $max[0];
        }  else {
            return 0;
        }
    }

    public static function getAllContatos($tabela, $key, $id,$sepador=",") {
        $query = self::getResultWhere($tabela, $key, $id);
        if ($query) {
            $strTels = "";
            $rows = mysql_num_rows($query);
            $count = 0;
            while ($tels = mysql_fetch_array($query)) {
                $count++;
                $strTels .=$tels['telefone'];
                if ($count == $rows)
                    return $strTels;
                $strTels .=$sepador;
            }
        }
        return '';
    }

    public static function getAllResult($tabela, $inico, $fim = null, $colunas = array(), $argument = null, $_id = 0,$ordeBy=null) {
        $_colunas = '*';
        $where = '';
        if (!empty($colunas))
            $_colunas = implode(',', $colunas);
        $conectar = Conexao::conectar();
        $pag = null;
        $array_result['result'] = array();
        $array_result['result']['pag'] = 0;
        $array_result['result']['item'] = array();
        $limit = '';
        if ($fim != null) {
            $limit = "LIMIT $inico,$fim";
            $pag = $fim;
        }
        if ($argument && $_id != 0) {
            $where = "WHERE $argument = $_id";
        } elseif ($_id != 0) {
            $where = "WHERE user_id=" . $_id;
        }
        $result_count = "SELECT $_colunas FROM $tabela $where $ordeBy";
        $execute = $result_count." ".$limit;
        //print_r($execute);
        $result = mysql_query($execute, $conectar);
        if ($result) {
            $num_rows = self::count($result_count);
            if ($num_rows > 0 && $pag != null) {
                if ($num_rows > $fim) {
                    // recebe o total de paginas com numero arrendodado paracima  em caso de decimal
                    $array_result['result']['pag'] = ceil($num_rows / $fim);
                } else {
                    // se quantidade for menor que o limite entao so existe uma pagina
                    $array_result['result']['pag'] = 1;
                }
            }
            while ($row = mysql_fetch_array($result)) {
                $array_result['result']['item'][] = $row;
            }
             $array_result['result']['count']=$num_rows;
        }
        return $array_result['result'];
    }

    public static function getAllResultWhere($tabela, $where,$inico,$ordeBy=null, $fim = null, $colunas = array()) {

        $_colunas = '*';
        if (!empty($colunas))
            $_colunas = implode(',', $colunas);
        $conectar = Conexao::conectar();
        $pag = null;
        $array_result['result'] = array();
        $array_result['result']['pag'] = 0;
        $array_result['result']['item'] = array();
        $limit = '';

        if ($fim != null) {
            $limit = "LIMIT $inico,$fim";
            $pag = $fim;
        }
         $result_count = "SELECT $_colunas FROM $tabela $where $ordeBy";
        $execute = $result_count." ".$limit;
       print_r($execute);
        $result = mysql_query($execute, $conectar);
        if ($result) {
            $num_rows = self::count($result_count);
            if ($num_rows > 0 && $pag != null) {
                if ($num_rows > $fim) {
                    // recebe o total de paginas com numero arrendodado paracima  em caso de decimal
                    $array_result['result']['pag'] = ceil($num_rows / $fim);
                } else {
                    // se quantidade for menor que o limite entao so existe uma pagina
                    $array_result['result']['pag'] = 1;
                }
            }
            while ($row = mysql_fetch_array($result)) {
                $array_result['result']['item'][] = $row;

            }
             $array_result['result']['count']=$num_rows;
        }
        return $array_result['result'];
    }

    public static function getResultFron($tabela, $coluna, $coluna_id, $id) {
        $conectar = Conexao::conectar();
        $execute = "SELECT $coluna FROM $tabela WHERE " . $coluna_id . "=" . $id;
        $query = mysql_query($execute, $conectar);
        if ($query) {
            $result = mysql_fetch_array($query);
            return $result [$coluna];
        }
        return null;
    }

    public static function getResultWhere($tabela, $colun, $where,$oparador="=",$ordeBy=null) {
        $conectar = Conexao::conectar();
        $execute = "SELECT * FROM $tabela WHERE   $colun  $oparador  $where $ordeBy";
       // print_r($execute);
        $result = mysql_query($execute, $conectar);
        if ($result) {
            return $result;
        }
        return null;
    }

    public static function safe($value) {
        return mysql_real_escape_string($value);
    }

}

?>