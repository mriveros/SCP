<?php
 include '../funciones.php';
 conexionlocal();
 if  (empty($_POST['codigo'])){$codigoCompra='';}else{$codigoCompra=$_POST['codigo'];}
 if  (empty($_POST['txtProductoA'])){$producto='';}else{$producto=$_POST['txtProductoA'];}
 if  (empty($_POST['txtCantidadA'])){$cantidad='';}else{$cantidad=$_POST['txtCantidadA'];}
 if  (empty($_POST['txtPrecioA'])){$precio='';}else{$precio=$_POST['txtPrecioA'];}
 if  (empty($_POST['txtCodigoE'])){$codigoDetalle='';}else{$codigoDetalle=$_POST['txtCodigoE'];}
 if  (empty($_POST['txtExentaA'])){$exento=0;}else{$exento=$_POST['txtExentaA'];}
 if  (empty($_POST['txtIVA'])){$iva=0;}else{$iva=$_POST['txtIVA'];}



//-------------------Obtenemos el codigo de Cabecera----------------------------
 $query = "Select max(ord_cod) from orden_compras;";
 $resultado=pg_query($query);
 $row=  pg_fetch_array($resultado);
 $codcabecera=$row[0];
//------------------------Agregar-----------------------------------------------
 if(isset($_POST['agregar'])){
     
     if($iva==1){
         
        $query = "INSERT INTO compras_detalles(ord_cod,pro_cod,comdet_cant,comdet_precio,comdet_subtotal,comdet_iva5) "
                . "VALUES ($codcabecera,$producto,$cantidad,$precio,$cantidad*$precio,((($cantidad*$precio)/1.05)*0.05));";
        pg_query($query)or die('Error al realizar la carga agregar 5');
        calcularMonto($codcabecera);
        header("Refresh:0; url=http://localhost/SGP/web/orden_compras/IngDetalle.php");
        
    }elseif($iva==2){
        if ($exento>0)
            {
            $MONTO_IVA_CALCULAR=($cantidad*$precio)-$exento;
            $query = "INSERT INTO compras_detalles(ord_cod,pro_cod,comdet_cant,comdet_precio,comdet_subtotal,comdet_iva10,comdet_exento) "
                . "VALUES ($codcabecera,$producto,$cantidad,$precio,$cantidad*$precio,(($MONTO_IVA_CALCULAR)/11),$exento);";
            pg_query($query)or die('Error al realizar la carga agregar 10 ');
            calcularMonto($codcabecera);
            }
        
        else{
        $query = "INSERT INTO compras_detalles(ord_cod,pro_cod,comdet_cant,comdet_precio,comdet_subtotal,comdet_iva10) "
                . "VALUES ($codcabecera,$producto,$cantidad,$precio,$cantidad*$precio,(($cantidad*$precio)/11));";
        pg_query($query)or die('Error al realizar la carga agregar exenta ');
        calcularMonto($codcabecera);
        }
        header("Refresh:0; url=http://localhost/SGP/web/orden_compras/IngDetalle.php");
        
    }elseif($iva==3){
        
        $query = "INSERT INTO compras_detalles(ord_cod,pro_cod,comdet_cant,comdet_precio,comdet_subtotal) "
                . "VALUES ($codcabecera,$producto,$cantidad,$precio,$cantidad*$precio);";
        pg_query($query)or die('Error al realizar la carga agregar exenta ');
        header("Refresh:0; url=http://localhost/SGP/web/orden_compras/IngDetalle.php");
        
    }

}
//------------------------Borrar------------------------------------------------
  if(isset($_POST['borrar'])){
        pg_query("delete from compras_detalles WHERE comdet_cod=$codigoDetalle");
       
        header("Refresh:0; url=http://localhost/SGP/web/orden_compras/IngDetalle.php");
  }
  
  function calcularMonto( $codcabecera){
    
            $query = "Select sum(comdet_subtotal)as subtotal,sum(comdet_exento) as exento,"
                    . "sum(comdet_iva5) as iva5,"
                    . "sum(comdet_iva10) as iva10 "
                    . "from compras_detalles where ord_cod=$codcabecera;";
            $resultado=pg_query($query);
            $row=  pg_fetch_array($resultado);
            $montoTotal=$row[0];
            $ivaTotal10=$row[2];
            $ivaTotal5=$row[3];
            $ExentaTotal=$row[1];
            $IVAGENERAL=($ivaTotal10+$ivaTotal5);
                    //calculo de con descuento de exenta
            $query = "update orden_compras set ord_monto=$montoTotal,ord_iva=$IVAGENERAL where ord_cod=$codcabecera;";
            pg_query($query)or die('Error al realizar la carga');
           
            
        
  }
  
  
?>