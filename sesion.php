<?php 

session_start();



function validarLogin(){
	if ( !isset($_SESSION['logueado']) ) { header('Location:index.php'); }
}