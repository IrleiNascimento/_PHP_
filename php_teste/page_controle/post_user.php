<?php

/**
 * Description of post_user
 *
 * @author Irlei
 */
$acao = $_GET['acao'];
if ($acao == 'edit') {
    require_once 'is_sessao.php';
}
require_once '../util/Util.php';
require_once '../model/Error.php';
require_once '../model_controle/UsuarioControle.php';
if (isset($_POST)) {
    $user_controle = new UsuarioControle();
    $user_atual = null;
    $user = $user_controle->getUser($_POST);
    $location = null;
    //url  para devolução em caso de erro
    $location = 'Location: ../page/usuario.phtml?err=(^_^)&acao=' . $acao;
    if ($user != null && $acao == 'edit') {
        //url  para devolução em caso de erro
        $edit_user = array();
        $user_atual = $_SESSION['user'];
        if (is_array($_SESSION['user']))
            $user_atual = (object) $_SESSION['user'];
        $location = 'Location: ../page/usuario.phtml?acao=' . $acao . '&id=' . $_POST['id'];

        $isfile = $_FILES['img']['error'] == 0;
        $old_img = null;
        $url_img = null;
        if ($isfile) {
            $old_img = $user->getImg_perfil();
            $url_img = $user_controle->upload_imagem($_FILES, 'img',Util::DIR_IMAGEM_USUARIO);
            //faz a troca da imagem do evento
            $user_controle->getUser()->setImg_perfil($url_img);

         if($old_img)
            if (strcmp($old_img, $url_img) != 0 &&
                    file_exists(Util::DIR_IMAGEM_USUARIO . $old_img))
                unlink(Util::DIR_IMAGEM_USUARIO . $old_img);


        }elseif (strcmp($_POST['img_perfil'], Util::IMAGEM_PADRAO_USUARIO) == 0
                && strcmp($user_atual->img_perfil, $_POST['img_perfil'])) {
            unlink(Util::DIR_IMAGEM_USUARIO . $url_img);
        }

        if (!$user_controle->editar()) {
            $_SESSION['msg_erro'] = $user_controle->getArrayErros();
            // em caso de erro na edição exclui a imagem
            if (file_exists(DIR_IMAGEM_USUARIO.$url_img)) {
                unlink(DIR_IMAGEM_USUARIO.$url_img);
            }
            $location = 'Location: ../page/usuario.phtml?acao=edit&id=' . $_POST['id'];
        } else {

            $location = 'Location: ../page/usuario.phtml?add=sucess';

            $edit_user = $_POST;
            if(trim($url_img))
            $edit_user['img_perfil'] = $url_img;

            if (isset($_SESSION['mlogin']))
                $_SESSION['msg_box'] = Util::msg_box(Util::SUCESS_DADOS_LOGIN_EDIT);

            if ($_POST['id'] == $user_atual->getId()) {
                $_SESSION['user'] = $edit_user;
            } else {
                $_SESSION['user_tmp'] = $edit_user;
            }

            $_SESSION['msg_box'] = Util::msg_box(Util::SUCESS_DADOS_EDIT);
        }

        unset($user_atual);
        unset($user_controle);
        unset($_SESSION['mlogin']);

    } elseif ($user != null && $acao == 'add') {

        if (!session_id())
            session_start();

        if ($_FILES['img']['error'] == 0) {
            $url_img = $user_controle->upload_imagem($_FILES, 'img',  Util::DIR_IMAGEM_USUARIO);
            $user_controle->getUser()->setImg_perfil($url_img);
        }
        if (!$user_controle->salvar()) {
            //remove a imagem caso o insert falhar
            if (file_exists( Util::DIR_IMAGEM_USUARIO.$url_img)) {
                unlink( Util::DIR_IMAGEM_USUARIO.$url_img);
            }
            $_SESSION ['user'] = $_POST;
            $_SESSION['msg_box'] = Util::msg_box(Util::ERRO_DADOS_INSERT);
            $_SESSION['msg_erro'] = $user_controle->getErros();
            $location = 'Location:../page/novo_usuario.phtml?acao=add';
        } else {
            //pegando todos os dados do usuario
            $_SESSION ['user'] = $_POST;
            $_SESSION ['user']['c_pw'] = '';
            $_SESSION ['user']['id'] = $user_controle->getUser()->getId();
            $_SESSION ['user']['img_perfil'] =  $url_img;       
            $location = 'Location:../page/novo_usuario.phtml?add=sucess';
            $_SESSION['msg_box'] = Util::msg_box(Util::SUCESS_DADOS_INSERT);
        }
        unset($user_controle);
        unset($user);
    }

    header($location);
}
?>