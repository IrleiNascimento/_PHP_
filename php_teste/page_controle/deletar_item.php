<?php
/**
 *
 *
 * @author Irlei
 */
require_once 'is_sessao.php';
require_once '../model/enum.php';
if (isset($_GET['id']) && isset($_GET['tp'])) {
    //var_dump($_GET);
    $controle = null;
    $req = '';
   if ($_GET['tp'] == enum::EVENTO) {
        require_once '../model_controle/EventoControle.php';

        $result = EventoControle::delete($_GET['id']);

        if ($result) {
            header("Location: ../page/relat_ev.php");
        } else {
            $req = 'evento.phtml';
        }


        }elseif ($_GET['tp'] == enum::SERVICO) {
        require_once '../model_controle/ServicoControle.php';

        $result =ServicoControle::delete($_GET['id']);
        if ($result) {
           header("Location: ../page/relat_servico.php");
        } else {
            $req = 'servico.phtml';
        }
    } else{
    require_once '../util/Util.php';
    $link = Util::crearLink($req, array('acao' => 'detail', 'id' => $_GET['id']));
    // em caso de falha devolve a requisição
    header('Location:'.$link);

    }
}
?>