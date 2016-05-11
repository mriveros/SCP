<?php
session_start();
if(!isset($_SESSION['codigo_usuario']))
header("Location:http://localhost/SGP/login/acceso.html");
$catego=  $_SESSION["categoria_usuario"];

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SGP-INTN-Facturas</title>
    <!-- Bootstrap Core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
	<!-- DataTables CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="../../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>
	
    <!-- DataTables JavaScript -->
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>
	    
	<!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
			responsive: true
        });
    });
    function facturar(codigo){
        $('tr').click(function() {
        indi = $(this).index();
	//var codigoOrden=document.getElementById("dataTables-example").rows[indi+1].cells[0].innerText;
	var codigoProveedor=document.getElementById("dataTables-example").rows[indi+1].cells[1].innerText;
        var monto=document.getElementById("dataTables-example").rows[indi+1].cells[8].innerText;
        var iva=document.getElementById("dataTables-example").rows[indi+1].cells[9].innerText;
        var ordenNro=document.getElementById("dataTables-example").rows[indi+1].cells[5].innerText;

        document.getElementById("txtCodigo").value = codigo;
        document.getElementById("txtProveedorA").value = codigoProveedor;
	document.getElementById("txtMontoA").value = monto;
        document.getElementById("txtIVAA").value = iva;
        document.getElementById("txtOrdenCompraA").value = ordenNro;
        });
		};
       function  facturarEnviar(codigo){
        document.getElementById("txtFacturar").value = codigo;
        });        
    </script>
	
</head>

<body>
    <div id="wrapper">
        <?php 
        include("../funciones.php");
        if ($catego==1){
             include("../menu.php");
        }elseif($catego==2){
             include("../menu_usuario.php");
        }elseif($catego==3){
             include("../menu_supervisor.php");
        }
        conexionlocal();
        ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                      <h1 class="page-header">Crear Facturas - <small>SGP-INTN</small></h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Facturas Creadas
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th style='display:none'>Codigo</th>
                                            <th>Factura Nro</th>
                                            <th>Fecha</th>
                                            <th>Proveedor</th>
                                            <th>Concepto</th>
                                            <th>Monto</th>
                                            <th>IVA</th>
                                            
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select fac.pro_cod,fac.con_cod,fac.fac_cod,fac.fac_nro,pro.pro_razon,con.con_nom,
                    fac.fac_monto,fac.fac_iva,fac.fac_fecha,fac.fac_activo
                    from facturas fac, proveedores pro,conceptos con
                    where fac.pro_cod=pro.pro_cod and
                    fac.con_cod=con.con_cod and
                    fac.fac_activo='t'";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        $estado=$row1["fac_activo"];
                        if($estado=='t'){$estado='Activo';}else{$estado='Inactivo';}
                        echo "<tr><td style='display:none'>".$row1["fac_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["pro_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["con_cod"]."</td>";
                        echo "<td>".$row1["fac_nro"]."</td>";
                        echo "<td>".$row1["fac_fecha"]."</td>";
                        echo "<td>".$row1["pro_razon"]."</td>";
                        echo "<td>".$row1["con_nom"]."</td>";
                        echo "<td>".$row1["fac_monto"]."</td>";
                        echo "<td>".$row1["fac_iva"]."</td>";
                        echo "</tr>";
                    }
                    pg_free_result($result);
                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <a  class="btn btn-primary" data-toggle="modal" data-target="#modalcrearFactura" role="button">Crear Factura</a>
                            <a class="btn btn-danger" data-toggle="modal" data-target="#modalImprimirOrden" role="button">Imprimir Factura</a>
                            <a  class="btn btn-default" data-toggle="modal" data-target="#modalbor" role="button">Anular ultima Factura</a>
                        </div>
                        <!-- /.panel-body -->
                        
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<!-- /#MODAL AGREGACIONES -->
	<div class="modal fade" id="modalcrearFactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-floppy-disk"></i>Crear Nueva Factura</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form  autocomplete="off" class="form-horizontal" name="agregarform" method="post" role="form" action="../class/ClsFacturas.php" >
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Factura Numero</label>
                                            <div class="col-sm-10">
                                            <input type="hidden" name="txtCodigoC" class="form-control" id="txtCodigoC"  required="true"/>
                                            <input type="text" name="txtNumeroC" class="form-control" id="txtNumeroC"  required="true"/>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Proveedor</label>
                                            <div class="col-sm-10">
                                           <select name="txtProveedorC" class="form-control" id="txtProveedorC" required="true">
                                                <?php
                                                //esto es para mostrar un select que trae datos de la BDD
                                                conexionlocal();
                                                $query = "Select pro_cod,pro_razon from proveedores where pro_activo='t' ";
                                                $resultadoSelect = pg_query($query);
                                                while ($row = pg_fetch_row($resultadoSelect)) {
                                                echo "<option value=".$row[0].">";
                                                echo $row[1];
                                                echo "</option>";
                                                }
                                                ?>
                                             </select>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Concepto</label>
                                            <div class="col-sm-10">
                                           <select name="txtConceptoC" class="form-control" id="txtConceptoC" required="true">
                                                <?php
                                                //esto es para mostrar un select que trae datos de la BDD
                                                conexionlocal();
                                                $query = "Select con_cod,con_nom from conceptos where con_activo='t' ";
                                                $resultadoSelect = pg_query($query);
                                                while ($row = pg_fetch_row($resultadoSelect)) {
                                                echo "<option value=".$row[0].">";
                                                echo $row[1];
                                                echo "</option>";
                                                }
                                                ?>
                                             </select>
                                            </div>
					</div>
                                       
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Monto</label>
                                            <div class="col-sm-10">
                                            <input type="number" name="txtMontoC" class="form-control" id="txtMontoC" required="true"/>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Observacion</label>
                                            <div class="col-sm-10">
                                                <textarea name="txtObservacionC" id="txtObservacionC" cols="60" rows="7"></textarea> 
                                            </div>
					</div>
                                         <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input03">IVA</label>
                                            <div class="col-sm-10">
                                            <div class="radio">
                                            <label><input type="radio" name="txtIVAC" value="1" checked /> IVA 5%</label></br>
                                            <label><input type="radio" name="txtIVAC" value="2" /> IVA 10 %</label></br>
                                            <label><input type="radio" name="txtIVAC" value="3" /> EXENTAS</label></br>
                                            </div>
                                            </div>
					</div>	
                                        </div>
                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                                <button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                               <button type="submit" name="crearFactura" class="btn btn-primary">Crear Factura</button>
                                        </div>	
                                </form>
				</div>
				
				
			</div>
		</div>

	
	
        	
        
    
<!-- /#MODAL ELIMINACIONES -->
	<div class="modal fade" id="modalbor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-trash"></i> Borrar Registro</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form class="form-horizontal" name="borrarform" action="../class/ClsFacturas.php" onsubmit="return submitForm();" method="post" role="form">
						<div class="form-group">
							
							<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								¡¡¡ATENCION!!! ...Se anulará la ultima Factura Generada y se eliminará de la base de datos
                                                               <input type="number" name="txtCodigoE" class="form-control" id="txtCodigoE" required="true"/>
							</div>  
                                                    
						</div>
				
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="borrarCrearFactura" class="btn btn-danger">Anular Factura</button>
					
				</div>
                                </form>
                                </div>
			</div>
		</div>
	</div>
  
           <!-- /#MODAL PARA IMPRIMIR FACTURA CREADA -->
        <div class="modal fade" id="modalImprimirOrden" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-trash"></i> Imprimir Orden de Compra</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form class="form-horizontal" name="imprimirform" action="../informes/Imp_CreateFactura.php" onsubmit="return submitForm();" method="post" role="form">
						<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Ingrese el Numero de Factura que desea Imprimir
                                                <input type="text" name="txtFacturaImprimir" class="form-control" id="txtFacturaImprimir" required="true" />
						</div>
				
                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                        <button type="" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" name="ImprimirOrden" class="btn btn-danger">Imprimir</button>

                                                </div>
                                 </form>
                                </div>
                               
                            </div>
			</div>
		</div>
              <!-- /#MODAL PARA FACTURAR Y ENVIAR LA FACTURA CREADA -->
        <div class="modal fade" id="modalImprimirOrden" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-trash"></i> Imprimir Orden de Compra</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form class="form-horizontal" name="imprimirform" action="../informes/Imp_CreateFactura.php" onsubmit="return submitForm();" method="post" role="form">
						<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Se enviara esta factura para su posterior retencion
                                                <input type="hide" name="txtFacturar" class="form-control" id="txtFacturar" required="true" />
						</div>
				
                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                        <button type="" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" name="ImprimirOrden" class="btn btn-danger">Imprimir</button>

                                                </div>
                                 </form>
                                </div>
                               
                            </div>
			</div>
		</div>
	
    
</html>