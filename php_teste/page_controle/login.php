<?php
require_once '../model_controle/UsuarioControle.php';
if (isset($_POST['username']) && isset($_POST['senha'])) {
    require_once '../model/conexao.php';
    $username = $_POST['username'];
    $pw = ($_POST['c_pw']);
    $err = '';
    $coluna = 'username';
    if (filter_var($username, FILTER_VALIDATE_EMAIL))
        $coluna = 'email';

    $conectar = Conexao::conectar();

    $execute = "SELECT username,email,usuario_id FROM login WHERE $coluna = '" . $username . "' AND senha= '" . $pw . "'";

    $query = mysql_query($execute, $conectar);

    if ($query) {
        $result = mysql_fetch_array($query);
        $id = $result['usuario_id'];
        if ($id) {
          $user_controle = new UsuarioControle();
            session_start();
//pegando todos os dados do usuario
            $_SESSION ['user'] =$user_controle->getArray($id,false);
//acrescentado informações da tabela de login
            $_SESSION ['user']['username'] = $result['username'];
            $_SESSION ['user']['email'] = $result['email'];           
            header("Location: ../page/home.phtml?id=" . $id);
        }
    }
    require_once '../util/Util.php';
    $err = Util::msg_box(Util::ERRO_AUTENTC_LOGIN).':'.$username;
 // Devolvendo u,a resposta de erro para o login  via POST
//set POST variables
    $url = 'http://'.Util::PAG_LOGIN;
    $fields = array('username' => urlencode($username),
        'err' => $err
    );
    Util::post($url, $fields);
}?>