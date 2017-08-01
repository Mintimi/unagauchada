<?php session_start(); ?>
<?php $pag='Tienda'; ?>
<?php require 'includes/encabezadoLog.php'; ?>
<?php 

if ($_SESSION['logueado']) {
	

	if (isset($_GET['e'])) { 
		$error = $_GET['e']; 
	} else { $error = false; }

	$errorTipo = array();
	$errorTipo[1]['text'] = 'Compre créditos si desea publicar una Gauchada.';
	$errorTipo[1]['type'] = 'error';
	$errorTipo[2]['text'] = 'Saldo insuficiente, por favor ingrese otra tarjeta.';
	$errorTipo[2]['type'] = 'error';
	$errorTipo[3]['text'] = 'Tarjeta inválida';
	$errorTipo[3]['type'] = 'error';


	if (!empty($_POST)) {
		if ( !empty($_POST['credito']) && !empty($_POST['nomyape']) && !empty($_POST['tarjeta']) && !empty($_POST['cvc']) ) {
			$tarjeta=$_POST['tarjeta'];
			$split=str_split($tarjeta,2);


			if ( $split[0]=="01" ) {
				header("location:tienda.php?e=3");	
			} else if ($tarjeta=='2345678912345678') {
				header("location:tienda.php?e=2");
			} else {
				$cred=$_POST['credito'];
				$id=$_SESSION['idUsuario'];

				$query = "SELECT 
							idUsuario,
					    	clave,
					   		nombre
						FROM
					    	usuario
						WHERE
					   	 idUsuario = '$id';";

				$result = fetch($query,true);

				$idUsuario=$result['idUsuario'];

				if (!empty($result)) {

					$query = "INSERT INTO preciocredito (`creditos`, `precio`) VALUES ('$cred', '50');";

					$res = accion($query, true);
						if($res || $res==0) {
							$lastInsertId = $res;
						}else { die();}

					$query = "INSERT INTO compracredito (`usuarioid`, `preciocreditoid`) VALUES ('$idUsuario', '$lastInsertId');";
					$res = accion($query, true);
					header("location:perfil.php?msj=1");
				
					
				}
			}
		}
	}
	 ?>

	<div>
	<?php $query="SELECT precio FROM precioCredito WHERE idPrecioCredito=1";
	$precio=fetch($query,true);
	$pre=$precio['precio'];
	 ?>

		<section class="menu">
			<?php if ($error) { ?> 
			<h2 class="<?php echo $errorTipo[$error]['type']; ?>"> 
				<?php echo $errorTipo[$error]['text']; ?>
			</h2>
			<?php } ?> 
			<h3 class="titulo">Comprar créditos</h3>
			<form method="POST" action="">
				<label>¿Cuántos vas a comprar?</label>
				<select name="credito" id="credito" onchange="fun(this.value, <?php echo $pre ?>)" required>
					<option value="0">Seleccione</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
				</select>
				<br><br>
				<div>
					<label>Precio Total:
					<input type="text" id="oculto" name="oculto" readonly>
					</label></div><br>
				<label>Nombre y Apellido:
				<input type="text" name="nomyape" value="<?php echo (isset($_POST['nomyape'])) ? $_POST['nomyape'] : '' ; ?>" required>

				</label><br><br>
				<label>Número de tarjeta:
				<input type="text" name="tarjeta" id="tarjeta" pattern="[0-9]{16}" maxlength="16" required>
				</label><br><br>
				<label>CVC:
				<input type="text" name="cvc" pattern="[0-9]{3,4}" maxlength="4" required>
				</label><br><br>
				<button>Comprar!</button>
			</form>
		</section>
	</div>
	<?php } else { header("location:index.php"); } ?>

	<script> 
		/*function fun(val){
	  		//document.getElementById("oculto").value = val * 50;
	  		val = val * 50;
	  		$('#oculto').val(val);
		}*/
	</script>
	<?php require 'includes/footer.php'; ?>