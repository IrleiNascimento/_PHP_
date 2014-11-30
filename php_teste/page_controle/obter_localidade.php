<?php
//require_once '../page_controle/is_sessao.php';
$uf = 0;
if (isset($_GET['uf']))
    $uf = $_GET['uf'];
if ($uf) {
    require_once '../util/Util.php';
    echo Util::obtercidades($uf);
} 
?>
