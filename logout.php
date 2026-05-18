<?php
/**
 * logout.php — Вихід з облікового запису адміністратора.
 * Знищує сесію та перенаправляє на головну сторінку.
 */
require_once 'auth.php';
logout();
header('Location: index.php');
exit;
?>
