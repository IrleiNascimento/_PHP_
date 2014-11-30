<?php
/**
 * Description of is_sessao
 *
 * @author Irlei
 */
session_start();
if (!isset($_SESSION['user'])) {
 header("Location: ../page/login.phtml");
}
?>
