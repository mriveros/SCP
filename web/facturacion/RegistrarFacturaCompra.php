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

    <title>SGP-INTN-Facturas/Compras</title>
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
                      <h1 class="page-header">Relacionar Facturas de Compras - <small>SGP-INTN</small></h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Ordenes de Compras
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th style='display:none'>Codigo</th>
                                            <th>Orden Nro</th>
                                            <th>Fecha</th>
                                            <th>Proveedor</th>
                                            <th>Monto</th>
                                            <th>IVA</th>
                                            <th>OBS</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select ord.ord_cod,ord.ord_tipo,ord.ord_nro,ord.ord_fecha,
                    ord.ord_termino,ord.ord_res,ord.ord_obs,pro.pro_razon,
                    ord.ord_monto,ord.ord_iva,ord.facturado,obj.obj_cod,
                    dep.dep_cod,pro.pro_cod,fir.fir_cod 
                    from orden_compras ord, proveedores pro,objeto_gastos obj, dependencias dep, firmantes fir 
                    where ord.pro_cod=pro.pro_cod and
                    obj.obj_cod=ord.obj_cod and
                    dep.dep_cod=ord.dep_cod and 
                    ord.fir_cod=fir.fir_cod and
                    ord.facturado='t' and ord.ord_activo='t'";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        $estado=$row1["facturado"];
                        if($estado=='t'){$estado='Activo';}else{$estado='Inactivo';}
                        echo "<tr><td style='display:none'>".$row1["ord_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["pro_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["obj_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["dep_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["fir_cod"]."</td>";
                        echo "<td>".$row1["ord_nro"]."</td>";
                        echo "<td>".$row1["ord_fecha"]."</td>";
                        echo "<td>".$row1["pro_razon"]."</td>";
                        echo "<td>".$row1["ord_monto"]."</td>";
                        echo "<td>".$row1["ord_iva"]."</td>";
                        echo "<td>".$row1["ord_obs"]."</td>";
                        echo "<td style='display:none'>".$row1["ord_res"]."</td>";
                        echo "<td>";?>
                        <a onclick='facturar(<?php echo $row1["ord_cod"];?>)' class="btn btn-success btn-xs bg-success" data-toggle="modal" data-target="#modalagr" role="button">Trazar</a>
                        <?php
                        echo "</td></tr>";
                    }
                    pg_free_result($result);
                    ?>
                                    </tbody>
                                </table>
                            </div>
                           <a  class="btn btn-danger" data-toggle="modal" data-target="#modalbor" role="button">Anular ultima Factura</a>
                           
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
	<div class="modal fade" id="modalagr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-floppy-disk"></i>Relacionar Factura</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form  autocomplete="off" class="form-horizontal" name="agregarform" method="post" role="form" action="../class/ClsFacturas.php" >
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Factura Numero</label>
                                            <div class="col-sm-10">
                                            <input type="hidden" name="txtCodigo" class="form-control" id="txtCodigo"  required="true"/>
                                            <input type="text" name="txtNumeroA" class="form-control" id="txtNumeroA"  required="true" placeholder="Ingrese el Numero de la Factura"/>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Proveedor</label>
                                            <div class="col-sm-10">
                                           <select name="txtProveedorA" class="form-control" id="txtProveedorA" readonly="true">
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
                                            <label  class="col-sm-2 control-label" for="input01">Orden Nro.</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtOrdenCompraA" class="form-control" id="txtOrdenCompraA" readonly="true"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Monto</label>
                                            <div class="col-sm-10">
                                            <input type="number" name="txtMontoA" class="form-control" id="txtMontoA" readonly="true"/>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">IVA</label>
                                            <div class="col-sm-10">
                                            <input type="number" name="txtIVAA" class="form-control" id="txtIVAA" readonly="true" />
                                            </div>
					</div>
                                        </div>
                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                                <button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                               <button type="submit" name="agregar" class="btn btn-primary">Generar</button>
                                        </div>	
                                </form>
				</div>
				
				
			</div>
		</div>

	
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
                                            <label  class="col-sm-2 control-label" for="input01">IVA</label>
                                            <div class="col-sm-10">
                                            <input type="number" name="txtIVAC" class="form-control" id="txtIVAC" required="true" />
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
 
<!-- /#MODAL ANULAR UNA FACTURA CON ORDEN DE COMPRA -->
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
						<div clas s="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
							
							<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								¡¡¡ATENCION!!! ...Ingrese el numero de factura Factura Generada y se eliminará de la base de datos
                                                                <input type="text" name="txtCodigoE" class="form-control" id="txtCodigoE" required="true" />
                                                               
							</div>
						</div>
				
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="borrar" class="btn btn-danger">Anular Factura</button>
					
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
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-certificate"></i> Imprimir Factura</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form class="form-horizontal" name="imprimirform" action="../informes/Imp_CreateFactura.php" onsubmit="return submitForm();" method="post" role="form">
						<div clas s="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
							
							<div clas s="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								Ingrese el Numero de Factura que desea Imprimir
                                                                 <input type="text" name="txtFacturaImprimir" class="form-control" id="txtFacturaImprimir" required="true" />
							</div>
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