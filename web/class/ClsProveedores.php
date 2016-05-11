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
    if  (empty($_POST['txtApellidoA'])){$apellidoA='';}else{ $apellidoA= $_POST['txtApellidoA'];}
    if  (empty($_POST['txtRucA'])){$rucA='';}else{ $rucA= $_POST['txtRucA'];}
    if  (empty($_POST['txtRazonA'])){$razonA='';}else{ $razonA= $_POST['txtRazonA'];}
    if  (empty($_POST['txtTelefA'])){$telefA='';}else{ $telefA= $_POST['txtTelefA'];}
    if  (empty($_POST['txtDireccionA'])){$direccionA='';}else{ $direccionA= $_POST['txtDireccionA'];}
    if  (empty($_POST['txtContactoA'])){$contactoA='';}else{ $contactoA= $_POST['txtContactoA'];}
    if  (empty($_POST['txtTelefContactoA'])){$telcontactoA='';}else{ $telcontactoA= $_POST['txtTelefContactoA'];}
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtApellidoM'])){$apellidoM='';}else{ $apellidoM= $_POST['txtApellidoM'];}
    if  (empty($_POST['txtRucM'])){$rucM='';}else{ $rucM= $_POST['txtRucM'];}
    if  (empty($_POST['txtRazonM'])){$razonM='';}else{ $razonM= $_POST['txtRazonM'];}
    if  (empty($_POST['txtTelefM'])){$telefM='';}else{ $telefM= $_POST['txtTelefM'];}
    if  (empty($_POST['txtDireccionM'])){$direccionM='';}else{ $direccionM= $_POST['txtDireccionM'];}
    if  (empty($_POST['txtContactoM'])){$contactoM='';}else{ $contactoM= $_POST['txtContactoM'];}
    if  (empty($_POST['txtTelefContactoM'])){$telcontactoM='';}else{ $telcontactoM= $_POST['txtTelefContactoM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($rucA, 'proveedores', 'pro_ruc')==true){
                echo '<script type="text/javascript">
		alert("El Proveedor ya existe. Intente ingresar otro Proveedor");
                window.location="http://localhost/SGP/web/clientes/ABMcliente.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO proveedores(pro_nom,pro_ape,pro_ruc,pro_razon,pro_tel,pro_dir,pro_contacto,"
                    . "pro_telcontacto,pro_activo) "
                    . "VALUES ('$nombreA','$apellidoA','$rucA','$razonA','$telefA','$direccionA','$contactoA','$telcontactoA','t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/SGP/web/proveedores/ABMproveedor.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update proveedores set pro_nom='$nombreM',"
                    . "pro_ape= '$apellidoM',"
                    . "pro_ruc='$rucM',"
                    . "pro_razon='$razonM',"
                    . "pro_tel='$telefM',"
                    . "pro_dir='$direccionM',"
                    . "pro_contacto='$contactoM',"
                    . "pro_telcontacto='$telcontactoM',"
                    . "pro_activo='$estadoM' "
                    . "WHERE pro_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/SGP/web/proveedores/ABMproveedor.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update proveedores set pro_activo='f' WHERE pro_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/SGP/web/proveedores/ABMproveedor.php");
	}
