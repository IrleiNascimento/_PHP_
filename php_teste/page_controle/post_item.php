<?php
/**
 * 
 *
 * @author Irlei
 */
require 'is_sessao.php';
require_once '../util/Util.php';
require_once '../model/Error.php';
require_once '../model/enum.php';
if (isset($_POST) && isset($_GET['item'])) {
unset($_SESSION['msg_erro']);
    $user = (object) $_SESSION['user'];
    $acao = $_GET['acao'];
    $tp_item = $_GET['item'];
    $current_dir = Util::DIR_IMAGEM_SERVICO;
    $img_padrao = Util::IMAGEM_PADRAO_SERVICO;

    $pag = 'servico.phtml';
    if ($tp_item == enum::EVENTO) {
        require_once '../model_controle/EventoControle.php';
        $controle = new EventoControle();
        $current_dir = Util::DIR_IMAGEM_EVENTOS;
        $img_padrao = Util::IMAGEM_PADRAO_EVENTO;
        $pag = 'evento.phtml';
    } elseif ($tp_item == enum::SERVICO) {
        require_once '../model_controle/ServicoControle.php';
        $controle = new ServicoControle();
    }
    //obtendo o id de sessão do usuario
    $controle->setId($user->id);
    $item = $controle->getItem($_POST);
    $location = null;
$img_name='';
    if ($item != null && $acao == 'edit') {

        $location = 'Location: ../page/' . $pag . '?acao=edit&id=' . $_POST['id'];

        $isfile = $_FILES['img']['error'] == 0;

        if ($isfile) {
            $old_img = $item->getImg_url();

            $img_name = $controle->upload_imagem($_FILES, 'img', $current_dir);

            //faz a troca da imagem do evento
            $item->setImg_url($img_name);
          if(trim($old_img))
            if (file_exists($current_dir.$old_img)) {
                unlink($current_dir.$old_img);
            }


        }elseif (strcmp($_POST['img_perfil'], $img_padrao) == 0
                && strcmp($user_atual->img_perfil, $_POST['img_perfil'])) {
            unlink($current_dir . $img_name);
        }

        if (!$controle->editar()) {
            $_SESSION['msg_erro'] = $controle->getArrayErros();
            $location = 'Location: ../page/' . $pag . '?acao=edit&id=' . $_POST['id'];
            //faz a troca da imagem do evento
            if(trim($img_name))
            if (file_exists($current_dir.$img_name)) {
                unlink($current_dir.$img_name);
            }
            $erros =$controle->getArrayErros();

            $err =(object)$erros [0];

            $_SESSION['msg_box'] = $err->message;
        } else {

            // se tudo estiver ok entao atualiza o array para mostra o resultado ao usuário
            $_SESSION['item'] = $_POST;
            $_SESSION['item']['tp'] = $tp_item;
            if (file_exists($current_dir.$img_name)) {
                $_SESSION['item']['url_img'] = $img_name;
            } else {
                $_SESSION['item']['url_img'] = $old_img;
            }
            $location = 'Location: ../page/' . $pag . '?add=sucess';
            $_SESSION['msg_box'] = Util::msg_box(Util::SUCESS_DADOS_EDIT);
        }
        // print_r($location);
        //var_dump(  $_SESSION['item']);
        header($location);
    } elseif ($item != null && $acao == 'add') {

        if ($_FILES['img']['error'] == 0) {
            $img_name = $controle->upload_imagem($_FILES, 'img', $current_dir);
            $item->setImg_url($img_name);
        }

        $controle->setContatoItem($_POST['telefone']);
        if ($tp_item == enum::SERVICO) {
            $item->setDataTime(gmDate("Y-m-d H:i:s"));
             $_SESSION['item']['dt_entrada']= $item->getDataTime();
        } // pegando a hora atual
        if (!$controle->salvar()) {
            $_SESSION['msg_erro'] = $controle->getArrayErros();
            $location = 'Location: ../page/' . $pag . '.?acao=add';
            $_SESSION['item'] = $_POST;
            $_SESSION['msg_box'] = Util::msg_box(Util::ERRO_DADOS_INSERT);
            //remove a imagem caso o insert falhar
            if (file_exists($current_dir . $img_name)) {
                unlink($current_dir . $img_name);
            }
        } else {

            $_SESSION['item'] = $_POST;
            $_SESSION['item']['id'] = $controle->getItem()->getId();
            $_SESSION['item']['tp'] = $tp_item;
            if (file_exists($current_dir.$img_name)) {
                $_SESSION['item']['url_img'] = $img_name;

            }
            unset($_SESSION['msg_erro']);
            $location = 'Location: ../page/' . $pag . '?add=sucess';
            $_SESSION['msg_box'] = Util::msg_box(Util::SUCESS_DADOS_INSERT);
        }
    }
    header($location);
}
?>