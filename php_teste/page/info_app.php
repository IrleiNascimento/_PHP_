<?php
require_once '../page_controle/is_sessao.php';
require_once'../util/Util.php';
$is_status = isset($_SESSION['user']['status']);
$nome = null;

if ($is_status) {
    $id = $_SESSION['user']['id'];
    $nome = $_SESSION['user']['username'];
    $img_user = $_SESSION['user']['img_perfil'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <link rel="shortcut icon" href="/imagens/missao_icon.png" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="../css/style.css" type="text/css"/>
            <script src="../script/ajax.js" type="text/javascript"></script>
            <script src="../script/js.js" type="text/javascript"></script>
            <script src="../script/jquery.min.js" type="text/javascript"></script>
            <title>Missão Criativa</title>
    </head>
    <body>
        <div id="tudo" >
            <div id="topo_menu">
                <div id="cabecalho">
                    <a href="http://www.missaocriativa.com.br">
                        <img  class="missao_logo" src="../imagens/sistem/missao_logo_star.png"/>
                    </a><h1>Missão Criativa</h1>
                    <a  class="nav_menu_bar lnk_config" href="../page_controle/logout.php">Sair</a>
                    <a class="nav_menu_bar lnk_config" href="<?php echo Util::crearLink('../page/usuario.phtml', array('acao' => 'edit', 'id' => $id)) ?>">alterar dados da conta</a>
                    <div id="user_info">
                        <?php if ($is_status) { ?>
                            <label >
                                <img src="<?php echo $img_user; ?>"/><br> <span >  <?php echo $nome ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div><!--cabeçalho menu-->
            </div><!--navegação-->
            <div class="conteudo">
               <h1>  adicinar infomações do sistema<h1>
            </div><!--Conteudo-->
        </div><!--todo site-->
        <div id="rodade_pe">
            <?php require_once '../util/rodape.php'; ?>
        </div><!-- Roda pé-->
    </body>
</html>