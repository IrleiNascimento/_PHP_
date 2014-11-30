<?php
/**
 * 
 *
 * @author Irlei
 */
require '../page_controle/is_sessao.php';
require_once '../model/ControlDb.php';
require_once '../model/enum.php';
$pg = 0;
$row_count = 10;
$inicio_row = 0;
if (isset($_GET['pag'])) {
    $pg = $_GET['pag'];
    $inicio_row = ($row_count * $pg) - $row_count;
}
//var_dump($inicio_row);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <link rel="shortcut icon" href="/imagens/missao_icon.png" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" href="../css/style.css" type="text/css"/>
            <script src="../script/ajax.js" type="text/javascript"></script>
            <script src="../script/jquery.min.js" type="text/javascript"></script>
            <title>Missão Criativa</title>
    </head>
    <body >
        <div id="tudo"  >
            <div id="topo_menu">
                <div id="cabecalho">
                    <img  class="missao_logo" src="../imagens/sistem/missao_logo_star.png">
                        <h1>Todos usuários</h1>
                        <a  class="nav_menu_bar lnk_config" href="../page/servico.phtml">anúncios</a>
                        <a  class="nav_menu_bar lnk_config" href="../page/evento.phtml">eventos</a>
                        <a  class="nav_menu_bar lnk_config" href="../page/usuario.phtml">menu</a>
                        <a  class="nav_menu_bar lnk_config" href="../page/home.phtml">início</a>


                </div>
            </div>
            <div class="conteudo" >
                <div class="conteudo_item" >
                    <?php
                    $array = ControlDb::getAllResult('usuario', $inicio_row, $row_count, null);
                    if (!empty($array['item'])) {
                        ?>
                        <table id="tabela_itens">
                            <thead>
                                <th>OP</th>
                                <th>STATUS</th>
                                <th>NOME</th>
                                <th>EMAIL</th>
                                <th>NOME DE USUÁRIO</th>
                            </thead>
                            <tbody>
                                <?php
                                $classTr = "tr_item_r";
                                require_once '../model_controle/LoginControle.php';
                                foreach ($array['item'] as $u):
                                    $status = ControlDb::getResultFron('status_usuario', 'status', 'id', $u['status_id']);
                                    $login_info = LoginControle::getArray($u['id']);
                                    if ($classTr == "tr_item_r") {
                                        $classTr = "tr_item_h";
                                    } else {
                                        $classTr = "tr_item_r";
                                    }
                                    ?>
                                    <tr  class="<?php echo $classTr ?>">
                                        <td> <div id="operacao_item" class="item">
                                                <a href="<?php echo Util::crearLink('detalhes.php', array('acao' => 'detail', 'item' =>enum::USUARIO, 'id' => $u['id'])) ?>">Detalhes</a>
                                            </div></td>
                                        <td><div>
                                                <p> <?php echo $status ?></p>
                                            </div>
                                        </td>
                                        <td><div class="item">
                                                <p> <?php echo $u['nome'] . ' ' . $u['sobrenome'] ?></p>
                                            </div>
                                        </td>
                                        <td><div class="item">
                                                <p> <?php echo $login_info['email'] ?></p>
                                            </div>
                                        </td>
                                        <td> <div class="item">
                                                <p> <?php echo $login_info['username'] ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                       <div id="info_pag">
                            <?php if ($array['pag'] > 1){
                                $count =$array['pag'];
                                for ($i = 2 ; $i <= $count; $i++) {
                                      $class ='lnk_control';
                                      if($pg==$i)
                                          $class .=' lnk_control_ativo';
                                    ?>
                                    <label>
                                        <a class="<?php echo $class; ?>" href="<?php echo Util::crearLink('relat_user.php', array('pag' => $i)) ?>"> <?php echo $i; ?></a>
                                    <?php } }?>
                            </label>
                        </div>

                    <?php } else { ?>
                        <div class="msg_relatorio_fazio">
                            <label>Não existem itens cadastrados...</label>
                        </div>
                    <?php } ?>
                </div><!-- fim div conteudo item -->
            </div>
        </div>
        <?php require_once '../util/mascara.php'; ?>
        <div id="rodade_pe">
            <?php require_once '../util/rodape.php'; ?>
        </div>
    </body>
</html>
<?php unset($_GET['pg']); ?>