<?php
/**
 *
 *
 * @author Irlei
 */
require_once '../page_controle/is_sessao.php';
if (isset($_POST['c_pw']) && isset($_POST['req'])) {
    require_once '../model_controle/LoginControle.php';
    require_once '../util/Util.php';
    require_once '../model/ControlDb.php';

    $user = (object)$_SESSION['user'];
    if ($user->id == $_POST['req']) {
        $query = "SELECT usuario_id FROM login WHERE usuario_id = '" . $user->id . "' AND senha= '" . $_POST['c_pw'] . "'";
        $result = ControlDb::select($query);
        if ($result) {
            $_SESSION['mlogin'] = LoginControle::getArray($_POST['req']);
        }else{
                $_SESSION['msg_erro']['mlogin_err']=Util::msg_box(Util::ERRO_AUTENTC_LOGIN);

        }
    }

header('Location:../page/usuario.phtml?acao=edit&id=' . $user->id );
}
?>