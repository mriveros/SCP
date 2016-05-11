<?php
 include '../funciones.php';
 conexionlocal();
 if  (empty($_POST['codigo'])){$codigoCompra='';}else{$codigoCompra=$_POST['codigo'];}
 if  (empty($_POST['txtProductoA'])){$producto='';}else{$producto=$_POST['txtProductoA'];}
 if  (empty($_POST['txtPrecioA'])){$precio='';}else{$precio=$_POST['txtPrecioA'];}
 if  (empty($_POST['txtCodigoE'])){$codigoDetalle='';}else{$codigoDetalle=$_POST['txtCodigoE'];}
 if  (empty($_POST['txtEstadoM'])){$tipoDetalleM=0 ;}else{ $tipoDetalleM= $_POST['txtEstadoM'];}

//-------------------Obtenemos el codigo de Cabecera----------------------------
 $query = "Select max(pag_cod) from pagos_creados;";
 $resultado=pg_query($query);
 $row=  pg_fetch_array($resultado);
 $codcabecera=$row[0];
//------------------------Agregar-----------------------------------------------
 if(isset($_POST['agregar'])){
     
    
         
        $query = "INSERT INTO pagos_detalles(pag_cod,con_cod,pag_det_monto,tipo_detalle) "
                . "VALUES ($codcabecera,$producto,$precio,$tipoDetalleM);";
        pg_query($query)or die('Error al cargar el detalle');
        calcularMonto($codcabecera);
        header("Refresh:0; url=http://localhost/SGP/web/orden_pagos/IngDetalle.php");
        
    

}
//------------------------Borrar------------------------------------------------
  if(isset($_POST['borrar'])){
        pg_query("delete from pagos_detalles WHERE pagdet_cod=$codigoDetalle");
        calcularMonto($codcabecera);
        header("Refresh:0; url=http://localhost/SGP/web/orden_pagos/IngDetalle.php");
  }
  
  function calcularMonto( $codcabecera){
            $montoTotalSUMA=0;$montoTotalResta=0;
            
            $query = "Select COALESCE(sum(pag_det_monto),0)as subtotal from pagos_detalles where pag_cod=$codcabecera and tipo_detalle=1;";
            $resultado=pg_query($query)or die('Error al realizar la carga 1');
            $row=  pg_fetch_array($resultado);
            $montoTotalSUMA=$row[0];
            
            $query = "Select COALESCE(sum(pag_det_monto),0)as subtotal from pagos_detalles where pag_cod=$codcabecera and tipo_detalle=0;";
            $resultado=pg_query($query)or die('Error al realizar la carga 2');
            $row=  pg_fetch_array($resultado);
            $montoTotalResta=$row[0];
            //Monto Total Restado
           
            $query = "update pagos_creados set pag_monto_total=$montoTotalSUMA where pag_cod=$codcabecera;";
            pg_query($query)or die('Error la consulta de monto total'.$codcabecera);
           
            $query = "update pagos_creados set pag_deducciones=$montoTotalResta where pag_cod=$codcabecera;";
            pg_query($query)or die('Error la consulta de deducciones'); 
  }
?>