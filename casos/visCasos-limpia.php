<?php
session_start();
if(isset($_SESSION['filtroCA_codigo']))
unset($_SESSION['filtroCA_codigo']);

if(isset($_SESSION['filtroCA_delito']))
unset($_SESSION['filtroCA_delito']);

if(isset($_SESSION['filtroCA_profesional']))
unset($_SESSION['filtroCA_profesional']);

if(isset($_SESSION['filtroCA_etapa']))
unset($_SESSION['filtroCA_etapa']);

if(isset($_SESSION['filtroCA_rut']))
unset($_SESSION['filtroCA_rut']);

if(isset($_SESSION['filtroCA_nombre']))
unset($_SESSION['filtroCA_nombre']);

if(isset($_SESSION['filtroCA_viaingreso']))
unset($_SESSION['filtroCA_viaingreso']);

if(isset($_SESSION['filtroCA_region']))
unset($_SESSION['filtroCA_region']);

if(isset($_SESSION['filtroCA_comuna']))
unset($_SESSION['filtroCA_comuna']);

if(isset($_SESSION['filtroCA_numcaso']))
unset($_SESSION['filtroCA_numcaso']);

/*if(isset($_SESSION['filtroCA_fnacimiento']))
unset($_SESSION['filtroCA_fnacimiento']);*/

if(isset($_SESSION['filtroCA_direccion']))
unset($_SESSION['filtroCA_direccion']);

if(isset($_SESSION['filtroCA_finsercion_inicio']))
unset($_SESSION['filtroCA_finsercion_inicio']);

if(isset($_SESSION['filtroCA_finsercion_fin']))
unset($_SESSION['filtroCA_finsercion_fin']);
?>