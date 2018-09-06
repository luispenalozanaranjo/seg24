<?php
session_start();
if(isset($_SESSION['filtroUS_nombre']))
unset($_SESSION['filtroUS_nombre']);

if(isset($_SESSION['filtroUS_estado']))
unset($_SESSION['filtroUS_estado']);

if(isset($_SESSION['filtroUS_perfil']))
unset($_SESSION['filtroUS_perfil']);
?>