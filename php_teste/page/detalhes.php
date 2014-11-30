<?php
/**
 *
 *
 * @author Irlei
 */
require_once '../page_controle/is_sessao.php';
require_once '../model/enum.php';
require_once '../util/Util.php';
$tipo = null;
$link = '#';
$pag =
        $menu_lnk = 'exibir_detalhes.php';
$lista_lnk = '#';
$require = 'exibir_detalhes.php';
if (isset($_GET['acao']) && $_GET['acao'] == 'detail') {
    $tipo = $_GET['item'];
    $id = $_GET['id'];
    if ($tipo == enum::SERVICO) {
        require_once '../model_controle/ServicoControle.php';
        $controle = new ServicoControle();
        $_SESSION['item'] = $controle->getArrayItem($id);
        $menu_lnk = '../page/servico.phtml';
        $lista_lnk = '../page/relat_servico.php';
        $link = Util::crearLink('servico.phtml', array('acao' => 'edit', 'id' => $_GET['id']));
        $pag = 'servico';
    } elseif ($tipo == enum::EVENTO) {
        require_once '../model_controle/EventoControle.php';
        $controle = new EventoControle();
        $_SESSION['item'] = $controle->getArrayItem($id);
        $menu_lnk = '../page/evento.phtml';
        $lista_lnk = '../page/relat_ev.php';
        $link = Util::crearLink('evento.phtml', array('acao' => 'edit', 'id' => $_GET['id']));
        $pag = 'evento';
    } elseif ($tipo == enum::USUARIO) {
        require_once '../model_controle/UsuarioControle.php';
        $controle = new UsuarioControle();
        $_SESSION['user_tmp'] = $controle->getArray($id);
        $menu_lnk = 'usuario.phtml';
        $lista_lnk = '../page/relat_user.php';
        $require = 'detalhes_user.php';
        $link = Util::crearLink('usuario.phtml', array('acao' => 'edit', 'id' => $_GET['id']));
        $pag = 'usuario';
    } else {
        header("location: ../page/home.phtml");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" href="/imagens/missao_icon.png" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../css/style.css" type="text/css"/>
        <script src="../script/js.js" type="text/javascript"></script>
        <script src="../script/ajax.js" type="text/javascript"></script>
        <script src="../script/jquery.min.js" type="text/javascript"></script>

        <title>Missão Criativa</title>
    </head>
    <body>
        <div id="tudo">
            <div id="topo_menu">
                <div id="cabecalho">
                    <img class="missao_logo" src="../imagens/sistem/missao_logo_star.png">
                    <h1>Detalhes </h1>
<?php if ($pag != 'usuario') { ?>
                        <a  class="nav_menu_bar_op nav_menu_bar"  href="javascript:popup_confirme('<?php echo $id . '\',\'' . $pag ?>');">excluir</a>
<?php } ?>
                    <a  class="nav_menu_bar_op nav_menu_bar" href="<?php echo $link; ?>">editar</a>
                    <a class="nav_menu_bar" href="<?php echo $lista_lnk; ?>">todos</a>
                    <a  class="nav_menu_bar" href="<?php echo $menu_lnk; ?>">menu</a>
                    <a  class="nav_menu_bar" href="home.phtml">Início</a>
                </div>
            </div>
            <div class="conteudo">

<?php require_once $require; ?>

            </div>
                <?php require_once '../util/mascara.php'; ?>
        </div>

        <div id="rodade_pe">
            <?php require_once '../util/rodape.php'; ?>
        </div>
    </body>
</html>