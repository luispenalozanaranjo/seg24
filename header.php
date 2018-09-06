<?php
	echo '
	<div id="content-header">  
		<header>
			<div class="contenedor" id="contenedorHeader">
				<div class="logo">
					'.$_SESSION['glologo'].'</a>
				</div>
				<div class="tituloSistema">
					<h1>SEG24Horas</h1>
					<h6>Sistema de Evaluaci&oacute;n y Gesti&oacute;n</h6>
					<span>Subsecretaría de Prevención del Delito - Ministerio del Interior y Seguridad Pública</span>
				</div>
				<div class="infoUser">
					<h4>Bienvenido(a) <a href="#" id="cambio-pass" title="Cambiar Contrase&ntilde;a"></a></h4>
					<div class="nombre">'.$_SESSION['glonombre'].' '.$_SESSION['glopaterno'].'</div>
					<div class="perfil">'.$_SESSION['gloperfil'].'</div>
					<div class="cerrarSesion">
					    
						<a href="../index.php" id="logout" title="Cerrar Sesi&oacute;n"></a>
					</div>
				</div>
			</div>
		</header>';
?>
