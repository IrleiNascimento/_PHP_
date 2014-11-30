<?php
/**
 * Description of Servico
 *
 * @author Irlei
 */
require_once 'Item.php';
class Servico extends Item{
    //put your code here

     private $periodo;
     private $status;

    function __construct($id, $titulo, $descricao, $dataTime, $uf,$cidade, $endereco, $latitude, $logitude, $img_url, $link, $rede_social,$email, $tipo,$periodo,$contato=null,$status=false) {
        parent::__construct($id, $titulo, $descricao, $dataTime,$uf, $cidade, $endereco, $latitude, $logitude, $img_url, $link, $rede_social, $email,$tipo,$contato);
        $this->periodo=$periodo;
        $this->status=$status;
        }

    public function getPeriodo() {
        return $this->periodo;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }


}

?>
