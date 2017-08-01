<?php 
error_reporting(0);

function conexion($srv='localhost',$usr='root',$pass='',$bd='mydb'){
	$con = mysqli_connect($srv,$usr,$pass,$bd);
	if ($con) {
		return $con;
	} else return false;
}

function fetch($query='',$individual=false){
	
	$con = conexion();

	$res = mysqli_query($con,$query);

	if (!$res) return false;

	$data=array();
	
	while ( $row = mysqli_fetch_assoc($res) ) {
		$data[] = $row;
	}

	if ( !$individual ) {
		return $data;
	} else {
		return $data[0];
	}

}

function accion( $query=false, $lastId=false ){

	if ( $query ) {
		
		$con = conexion();

		$res = mysqli_query($con,$query);
		$lastInsertId = mysqli_insert_id($con);

		if ($res) {
			if ($lastId) {
				return $lastInsertId;
			} else { return true; }

		} else return false;

	} else return false;

}


