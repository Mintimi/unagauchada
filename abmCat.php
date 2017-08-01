<?php session_start(); ?>
<?php $pag='Gestión'; 
require 'includes/encabezadoLog.php'; 

$msj =  array();
$msj[1]['text'] = 'Categoría existente';
$msj[1]['type'] = 'error';
$msj[2]['text'] = 'Categoría agregada exitosamente';
$msj[2]['type'] = 'ok';
$msj[3]['text'] = 'No hubo modificación';
$msj[3]['type'] = 'ok';
$msj[4]['text'] = 'La categoría se modificó correctamente';
$msj[4]['type'] = 'ok';
$msj[5]['text'] = 'La categoría se eliminó correctamente';
$msj[5]['type'] = 'ok';
$msj[6]['text'] = 'Categoría en uso! No se puede eliminar.';
$msj[6]['type'] = 'error';
$msj[7]['text'] = 'Se cambió la visibilidad de la categoría';
$msj[7]['type'] = 'ok';
 
if (isset($_GET['m'])) {
	$num = $_GET['m'];
} ?>

<?php if ($_SESSION['logueado'] && $_SESSION['admin'] == 1) { ?>

	<?php if (isset($_GET['op']) && $_GET['op']=='new' && !empty($_POST['cat'])) {
			$cat=$_POST['cat'];
			$cat=strtolower($cat);
			$cat=ucfirst($cat);
			$query="SELECT Categoria FROM categoria WHERE Categoria='$cat'";
			$existe=fetch($query,true);
			if ($existe) {
				header('location:abmCat.php?m=1'); exit();
			}else{
				$query="INSERT INTO categoria (`Categoria`, `habilitado`) VALUES ('$cat','1');";
				accion($query);
				header('location:abmCat.php?m=2'); exit();
			}
	} else if ($_GET['op']=='mod') { ?>
		
		<?php	$id=$_GET['id']; 

				$query="SELECT Categoria FROM categoria WHERE idCategoria='$id'";
				$cate=fetch($query,true);	

				$papita=$cate['Categoria'];
				

				if (isset($_GET['form'])) {
					$id=$_POST['id'];
					$cat=$_POST['cat'];
					$cat=strtolower($cat);
					$cat=ucfirst($cat);

					$query="SELECT Categoria FROM categoria WHERE idCategoria='$id'";
					$cate=fetch($query,true);	
					$papita=$cate['Categoria'];
					
					$query="SELECT Categoria
						FROM categoria WHERE Categoria='$cat'";
					$existe=fetch($query,true);
					
					if ($cate['Categoria']==$cat) {
						
						$existe=false;
						header('location:abmCat.php?m=3'); exit();
					} else if (empty($existe)){
						$query="UPDATE categoria SET `Categoria`='$cat' WHERE `idCategoria`='$id';";
						accion($query);
						header('location:abmCat.php?m=4'); exit();

					} else if (!empty($existe)){
						header("location:abmCat.php?op=mod&m=1&papita=$papita&id=$id"); exit();
					}

				}
		?>
		<div class="menu">

		 	<p><h3 class="titulo">Modificar Categoría</h3>
		 	<div id="volver">
			 	<a href="abmCat.php">Volver</a>
			 </div>	
			 <?php if (isset($_GET['papita'])) {
			 	$papita=$_GET['papita'];
			 } ?>	
	 	
		 	<div>
		 		<h3 class="<?php echo $msj[$num]['type']; ?>"><?php  echo $msj[$num]['text'];?></h3>
		 	</div>
		</div>
		<div id="cajita">
			<form method="POST" action="abmCat.php?op=mod&form=ok">
				<input type="text" name="cat" value="<?php echo $papita; ?>" placeholder="Ingrese categoría" required >
				<input type="hidden" name="id" value="<?php echo $id ?>">
				<button>Modificar</button>
			</form>
		</div>

	<?php } else if ($_GET['op']=='del') { 
				$query="SELECT * FROM categoria WHERE habilitado=1";
				$existe=fetch($query);
				if (count($existe)>1) {
					# code...
				}
				$id=$_GET['id']; 
				$query="SELECT * FROM gauchadacat where catId=$id";
				$gauchadas=fetch($query);
				if (count($gauchadas)==0) {
					echo "entra";
					$query= "DELETE FROM `categoria` WHERE `idCategoria`='$id'";
					accion($query);
					header('location:abmCat.php?m=5'); 	
				} else { header('location:abmCat.php?m=6'); }
			?>


<?php } else if ($_GET['op']=='change') {
			$id=$_GET['id'];
			$query="SELECT habilitado FROM categoria WHERE idCategoria=$id";
			$visible=fetch($query,true);
			if ($visible['habilitado']==1) {
				$cambiar=0;
			}else if ($visible['habilitado']==0) { $cambiar=1; }
			$query="UPDATE `categoria` SET `habilitado`='$cambiar' WHERE `idCategoria`='$id';";
			accion($query);
			header("location:abmCat.php?m=7");
} else { ?>
	
	<div class="menu">
	 	<p><h3 class="titulo">Categorías</h3>
	 	<div id="volver">
		 	<a href="gestion.php">Volver</a>
		 </div>			 	
	 	<div>
	 		<h3 class="<?php echo $msj[$num]['type']; ?>"><?php  echo $msj[$num]['text'];?></h3>
	 	</div>
	</div>

	<section id="cajita">
		<div id="agregar">
			<form method="POST" action="abmCat.php?op=new">
				<input type="text" name="cat" placeholder="Ingrese categoría nueva" required>
				<button>Agregar</button><br>
			</form>
		</div>

		<?php $query="SELECT Categoria,
							idCategoria,
							habilitado
						FROM categoria";
		$cates=fetch($query);
		$query="SELECT * FROM categoria WHERE habilitado=1";
		$categorias=fetch($query);
		$cant=count($categorias);
		foreach ($cates as $value) { ?>
			<div id="cont">
				<p><?php echo $value['Categoria']; ?></p>
				<div id="opt">
					
					<?php if ($value['idCategoria']!=1) { ?>
						<a href="abmCat.php?op=change&id=<?php echo $value['idCategoria']; ?>"><?php echo ($value['habilitado']==1) ? "Deshabilitar" : "Habilitar" ; ?></a>	
						<a href="abmCat.php?op=del&id=<?php echo $value['idCategoria']; ?>">Eliminar</a>
					<?php } ?>
					<a href="abmCat.php?op=mod&id=<?php echo $value['idCategoria']; ?>">Modificar</a>
				</div>	
			</div>
	<?php }  ?>


		 
	</section>
	<?php } ?>

<?php } ?>
<?php require 'includes/footer.php'; ?>
