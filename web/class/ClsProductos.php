<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de Compras y Pagos SGP-INTN
 */
session_start();
$codusuario=  $_SESSION["codigo_usuario"];

    include '../funciones.php';
    conexionlocal();
    
    //Datos del Form Agregar
    if  (empty($_POST['txtNombreA'])){$nombreA='';}else{ $nombreA = $_POST['txtNombreA'];}
    if  (empty($_POST['txtIVAA'])){$ivaA='';}else{ $ivaA = $_POST['txtIVAA'];}
    if  (empty($_POST['txtPrecioA'])){$precioA='';}else{ $precioA= $_POST['txtPrecioA'];}
    if  (empty($_POST['txtDescripcionA'])){$descripcionA='';}else{ $descripcionA= $_POST['txtDescripcionA'];}
    if  (empty($_POST['txtExentaA'])){$exentaA='f';}else{ $exentaA= 't';}
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtIVAM'])){$ivaM='';}else{ $ivaM = $_POST['txtIVAM'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtPrecioM'])){$precioM='';}else{ $precioM= $_POST['txtPrecioM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    if  (empty($_POST['txtExentaM'])){$exentaM='f';}else{ $exentaM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'productos', 'pro_nom')==true){
                echo '<script type="text/javascript">
		alert("El Producto ya existe. Ingrese otro Producto.");
                window.location="http://localhost/SGP/web/productos/ABMproducto.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO productos(iva_cod,pro_nom,pro_des,pro_precio,pro_activo,pro_exentas)"
                    . "VALUES ($ivaA,'$nombreA','$descripcionA',$precioA,'t','$exentaA');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/SGP/web/productos/ABMproducto.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update productos set pro_nom='$nombreM',"
                    . "pro_des= '$descripcionM',"
                    . "pro_precio= '$precioM',"
                    . "iva_cod= '$ivaM',"
                    . "pro_exentas= '$exentaM',"
                    . "pro_activo='$estadoM'"
                    . "WHERE pro_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/SGP/web/productos/ABMproducto.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update productos set pro_activo='f' WHERE pro_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/SGP/web/productos/ABMproducto.php");
            
	}
        