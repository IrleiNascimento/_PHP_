<?php
/**
 *
 *
 * @author Irlei
 */
session_start();
if (isset($_SESSION['user'])) {
  $_SESSION = array();
  if(isset($_COOKIE[session_name()])){
    setcookie(session_name(), '', time() - 1000, '/');
  }
 session_destroy();
}
header("Location: ../page/login.phtml");
?>