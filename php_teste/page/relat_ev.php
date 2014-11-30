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

$user= (object)$_SESSION['user'];
if (isset($_GET['pag'])) {
    $pg = $_GET['pag'];
    $inicio_row = ($row_count * $pg) - $row_count;
}
//var_dump($inicio_row);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" href="/imagens/missao_icon.png" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../css/style.css" type="text/css"/>
        <script src="../script/ajax.js" type="text/javascript"></script>
        <script src="../script/jquery.min.js" type="text/javascript"></script>
        <script src="../script/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <title>Missão Criativa</title>
    </head>
    <body >
        <div id="tudo">
            <div id="topo_menu">
                <div id="cabecalho">
                    <img class="missao_logo" src="../imagens/sistem/missao_logo_star.png">
                    <h1>Eventos</h1>
                    <a  class="nav_menu_bar lnk_config" href="../page/evento.phtml?acao=add">novo</a>
                    <a  class="nav_menu_bar lnk_config" href="../page/evento.phtml">menu</a>
                    <a  class="nav_menu_bar lnk_config" href="../page/home.phtml">início</a>
                </div>
            </div>
 <div class="conteudo" >                <?php
                $coluns = array('id,titulo', 'descricao', 'data_evento', 'cidade');
                $array = ControlDb::getAllResult('evento', $inicio_row, $row_count, $coluns, $user->id);
                if (!empty($array['item'])) {
                    ?>
                    <div class="conteudo_item">
                        <table id="tabela_itens">
                            <thead>
                            <th>OP</th>
                            <th>TITULO</th>
                            <th>DESCRIÇÃO</th>
                            <th >DATA</th>
                            <th >CIDADE</th>
                            </thead><tbody>
                                <?php
                                $classTr = "tr_item_r";
                                require_once '../model_controle/LoginControle.php';

                                foreach ($array['item'] as $ev):

                                    if ($classTr == "tr_item_r") {
                                        $classTr = "tr_item_h";
                                    } else {
                                        $classTr = "tr_item_r";
                                    }
                                    ?>
                                    <tr  class="<?php echo $classTr ?>">
                                        <td style="width:80px;"> <div id="operacao_item" class="item">
                                                <a href="<?php echo Util::crearLink('detalhes.php', array('acao' => 'detail', 'item' => enum::EVENTO, 'id' => $ev['id'])) ?>">Detalhes</a>
                                            </div></td>

                                        <td><div class="item">
                                                <p> <?php echo $ev['titulo'] ?></p>
                                            </div>
                                        </td>
                                        <td><div style="text-align: left; "class="item">
                                                <p> <?php echo Util::min_str($ev['descricao'],50); ?></p>
                                            </div>
                                        </td>
                                        <td style="width:180px;"> <div class="item">
                                                <p> <?php echo Util::formatarData($ev['data_evento'],0) ?></p>
                                            </div>
                                        </td>
                                        <td> <div class="item">
                                                <p> <?php echo Util::getCidadeFromID($ev['cidade']); ?></p>
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
                                        <a class="<?php echo $class; ?>" href="<?php echo Util::crearLink('relat_ev.php', array('pag' => $i)) ?>"> <?php echo $i; ?></a>
                                    <?php } }?>
                            </label>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="msg_relatorio_fazio">
                        <label>Não existem itens cadastrados...</label>
                    </div>
                <?php } ?>
            </div><!-- fim da div conteudo -->
        </div>
        <?php require_once '../util/mascara.php'; ?>
        <div id="rodade_pe">
            <?php require_once '../util/rodape.php'; ?>
        </div>
    </body>
</html>
<?php unset($_SESSION['user']['item']); ?>