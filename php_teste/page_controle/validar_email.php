<?php
/**
 *
 *
 * @author Irlei
 */
$email = '';
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    require_once '../util/Util.php';
    $id=$_GET['id'];
    echo Util::validarEmail($email,$id);
}
?>
