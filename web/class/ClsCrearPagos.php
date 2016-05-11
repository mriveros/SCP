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

    if  (empty($_POST['txtCodigo'])){$codigoA='';}else{ $codigoA = $_POST['txtCodigo'];}
    if  (empty($_POST['txtFuenteA'])){$fuenteA='';}else{ $fuenteA= $_POST['txtFuenteA'];}
    if  (empty($_POST['txtProveedorA'])){$proveedorA='';}else{ $proveedorA= $_POST['txtProveedorA'];}    
    if  (empty($_POST['txtBancoA'])){$bancoA='';}else{ $bancoA= $_POST['txtBancoA'];}
    if  (empty($_POST['txtCuentaA'])){$cuentaA='';}else{ $cuentaA= $_POST['txtCuentaA'];}
    if  (empty($_POST['txtFirmanteA'])){$firmanteA='';}else{ $firmanteA= $_POST['txtFirmanteA'];}
    if  (empty($_POST['txtNroChequeA'])){$chequeA='';}else{ $chequeA= $_POST['txtNroChequeA'];}
    //DAtos para el Eliminar
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
     if(isset($_POST['agregar'])){
         if(func_existeDato(0, 'pagos_creados', 'pag_cod')==true){
                echo '<script type="text/javascript">
		alert("Error al crear la orden de pago. Contacte con el Programador");
                window.location="http://localhost/SGP/web/orden_pagos/Crear_Pago.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO pagos_creados(pro_cod,ban_cod,cuen_cod,fir_cod,pag_fuente,pag_fecha,pag_nrocheque,pag_activo)"
                    . "VALUES ($proveedorA,$bancoA,$cuentaA,$firmanteA,$fuenteA,now(),$chequeA,'t');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/SGP/web/orden_pagos/Crear_Pago.php");
                }         
     }
      if(isset($_POST['borrar'])){
            pg_query("delete from pagos_creados where pag_cod=$codigoElim") or die('Error al realizar SQL');
             header("Refresh:0; url=http://localhost/SGP/web/orden_pagos/Crear_Pago.php");
	}
    