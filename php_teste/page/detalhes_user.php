<?php
/**
 *
 *
 * @author Irlei
 */
require_once '../page_controle/is_sessao.php';
require_once '../model_controle/UsuarioControle.php';
require_once '../util/Util.php';
require_once '../model_controle/LoginControle.php';

if(isset($_SESSION['user_tmp'])){
    $user = (object) $_SESSION['user_tmp'];
}else {
    $user = (object) $_SESSION['user'];

}

    $info_login = (object) LoginControle::getArray($user->id);


?>
<div class="conteudo_item">
    <div id="img_detalhes_item">
        <img id="img_perfil" src="<?php echo Util::DIR_IMAGEM_USUARIO.$user->img_perfil ?>"/>
    </div>
    <div id="detalhes_item">
        <label class="detalhes_item">
            <span id="status">Status:</span>
            <output class="output_text"  ><?php echo UsuarioControle::getStatusFrom($user->status) ?></output>
        </label>

        <label class="detalhes_item">
            <span id="tipo">Nome:</span>
            <output class="output_text" id="nome" ><?php echo ($user->nome . ' ' . $user->sobrenome) ?></output>
        </label>

        <label class="detalhes_item">
            <span id="periodo">Nome de Usuario:</span>
            <output class="output_text"  ><?php echo $info_login->username ?></output>
        </label>

        <label class="detalhes_item">
            <span >Email:</span>
            <output class="output_text"  id="email"><?php echo $info_login->email ?></output>
        </label>
        <label class="detalhes_item">
            <span id="descricao">Telefone:</span>
            <output class="output_text" id="telefone" ><?php echo $user->telefone ?></output>
        </label>

        <label class="detalhes_item"><span>Nascimento:</span>
            <output class="output_text" ><?php echo Util::formatarData($user->dt_nascimento) ?></output></label>

        <label class="detalhes_item">
            <span id="endereco">UF :</span>
            <output class="output_text"  ><?php echo Util::getUF($user->uf); ?></output>
        </label>
        <label class="detalhes_item">
            <span id="telefone">Cidade:</span>
            <output class="output_text"  ><?php echo Util::getCidadeFromID($user->cidade) ?></output>

        </label>
        <label class="detalhes_item">
            <span id="data">Endereço:</span>
            <output class="output_text" ><?php echo $user->endereco ?></output>
        </label>
    </div>
</div>