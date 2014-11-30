<?php
require_once '../page_controle/is_sessao.php';
$user  = (object) $_SESSION['user'];

if ($nome) {
    ?>
    <div   id="user_info">
        <label >
            <img src="<?php echo $$user->img_perfil; ?>"/><br> <span >  <?php echo $user->nome; ?></span>
        </label>
    </div>
<?php
}?>