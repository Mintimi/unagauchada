<?php require_once 'bd.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Una Gauchada | <?php echo $pag; ?></title>
		<link rel="stylesheet" href="styles/style.css">
	</head>
	
<body>		
	<div id="wrap">	
		<div id="logo">
			<a href="index.php">
			<img src="img/logo.png" alt="logo">

			</a>
		</div>
		<nav id="nav">
			
			<a href="index.php" class="<?php echo ($pag=='Home')?'selected':''; ?>"> Home </a>
			
			<?php if (!$_SESSION['logueado']) {?> 

				<a href="login.php" class="<?php echo ($pag=='LogIn')?'selected':''; ?>"> Iniciar Sesión </a>
				<a href="registro.php" class="<?php echo ($pag=='Registro')?'selected':''; ?>"> Registrarse</a> 

			<?php } else if($_SESSION['logueado']){ ?>

				<a href="tienda.php" class="<?php echo ($pag=='Tienda')?'selected':''; ?>"> Comprar </a>
				<a href="publicarG.php" class="<?php echo ($pag=='Publicar Gauchada')?'selected':''; ?>">Publicar Gauchada</a>
				<a href="perfil.php" class="<?php echo ($pag=='Perfil')?'selected':''; ?>">Mi perfil</a>

			<?php } if($_SESSION['logueado'] && $_SESSION['admin']==1){ ?>

				<a href="gestion.php" class="<?php echo ($pag=='Gestión')?'selected':''; ?>">Gestión</a>

			 <?php } 

			 if ($_SESSION['logueado']){ ?>
				<a href="logout.php"> Cerrar Sesión </a>
			<?php } ?> 
		
		</nav>