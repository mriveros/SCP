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

    <title>SGP-INTN-Orden Pago</title>
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
    function eliminar(codigo){
        
			document.getElementById("txtCodigoE").value = codigo;
                       
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
                      <h1 class="page-header">Orden Pagos - <small>SGP-INTN</small></h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Ordenes de Pagos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th>Codigo Pago</th>
                                            <th>Proveedor</th>
                                            <th>Fuente</th>
                                            <th>Banco</th>
                                            <th>Cuenta</th>
                                            <th>Monto Total</th>
                                            <th>Deducciones</th>
                                            <th>Nro. Cheque</th>
                                            <th>Fecha</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select pag.pag_cod,pro.pro_razon as proveedores,
                    ban.ban_nom,
                    cuen.cuen_nom,
                    pag.pag_monto_total,
                    pag.pag_deducciones,
                    pag.pag_fuente,
                    to_char(pag.pag_fecha,'DD/MM/YYYY') as pag_fecha,
                    pag.pag_nrocheque
                    from pagos_creados pag, proveedores pro, bancos ban, cuentas cuen, firmantes fir
                    where pag.pro_cod=pro.pro_cod and
                    pag.ban_cod=ban.ban_cod and
                    pag.cuen_cod=cuen.cuen_cod and
                    pag.fir_cod=fir.fir_cod";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        $estado=$row1["facturado"];
                        if($estado=='t'){$estado='Activo';}else{$estado='Inactivo';}
                        echo "<tr><td>".$row1["pag_cod"]."</td>";
                        echo "<td>".$row1["proveedores"]."</td>";
                        echo "<td>".$row1["pag_fuente"]."</td>";
                        echo "<td>".$row1["ban_nom"]."</td>";
                        echo "<td>".$row1["cuen_nom"]."</td>";
                        echo "<td>".$row1["pag_monto_total"]."</td>";
                        echo "<td>".$row1["pag_deducciones"]."</td>";
                        echo "<td>".$row1["pag_nrocheque"]."</td>";
                        echo "<td>".$row1["pag_fecha"]."</td>";
                        echo "<td>";?>
                        <a onclick='eliminar(<?php echo $row1["pag_cod"];?>)' class="btn btn-danger btn-xs active" data-toggle="modal" data-target="#modalbor" role="button">Borrar</a>
                        <?php
                        echo "</td></tr>";
                    }
                    pg_free_result($result);
                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <a  class="btn btn-primary" data-toggle="modal" data-target="#modalagr" role="button">Nueva Orden de Pago</a>
                            <a  class="btn btn-info" data-toggle="modal" data-target="#modalprueba" role="button">Agregar Detalle de Pago</a>
                            <a class="btn btn-danger" data-toggle="modal" data-target="#modalImprimirOrden" role="button">Imprimir Orden de Pago</a>
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
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-floppy-disk"></i> Agregar Registro</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form  autocomplete="off" class="form-horizontal" name="agregarform" method="post" role="form" action="../class/ClsCrearPagos.php" >
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Fuente</label>
                                            <div class="col-sm-10">
                                           <select name="txtFuenteA" id="txtFuenteA" required="true">
                                            <option value="10">Fuente 10</option>
                                            <option value="30">Fuente 30</option>
                                          </select>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Proveedor</label>
                                            <div class="col-sm-10">
                                           <select name="txtProveedorA" class="form-control" id="txtProveedorA" required="true">
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
                                            <label  class="col-sm-2 control-label" for="input01">Banco</label>
                                            <div class="col-sm-10">
                                           <select name="txtBancoA" class="form-control" id="txtBancoA" required="true">
                                                <?php
                                                //esto es para mostrar un select que trae datos de la BDD
                                                conexionlocal();
                                                $query = "Select ban_cod,ban_des from bancos where ban_activo='t' ";
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
                                            <label  class="col-sm-2 control-label" for="input01">Cuenta</label>
                                            <div class="col-sm-10">
                                           <select name="txtCuentaA" class="form-control" id="txtCuentaA" required="true">
                                                <?php
                                                //esto es para mostrar un select que trae datos de la BDD
                                                conexionlocal();
                                                $query = "Select cuen_cod,cuen_des  from cuentas where cuen_activo='t' ";
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
                                            <label  class="col-sm-2 control-label" for="input01">Firmante</label>
                                            <div class="col-sm-10">
                                           <select name="txtFirmanteA" class="form-control" id="txtFirmanteA" required="true">
                                                <?php
                                                //esto es para mostrar un select que trae datos de la BDD
                                                conexionlocal();
                                                $query = "Select fir_cod,fir_nom ||' '|| fir_ape  from firmantes where fir_activo='t' ";
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
                                            <label  class="col-sm-2 control-label" for="input01">Numero Cheque</label>
                                            <div class="col-sm-10">
                                            <input type="text" name="txtNroChequeA" class="form-control" id="txtNroChequeA" required="true"/>
                                            </div>
					</div>
                                        <div class="modal-footer">
                                            <button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" name="agregar" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
			</div>
                    </div>
				<!-- Modal Footer -->
				
                               
				
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
                                    <form class="form-horizontal" name="borrarform" action="../class/ClsCrearPagos.php" onsubmit="return submitForm();" method="post" role="form">
						<div class="form-group">
							<input type="numeric" name="txtCodigoE" class="hide" id="txtCodigoE" />
							<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								¡¡¡ATENCION!!! ...Se borrara el siguiente registro..
							</div>
						</div>
				</div>
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="borrar" class="btn btn-danger">Borrar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
        <div class="modal fade" id="modalprueba" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <embed wmode="transparent" src="IngDetalle.php" width="700" height="700" />
                </div>
            </div>
        </div>
        
         <!-- /#MODAL PARA IMPRIMIR ORDEN DE COMPRA -->
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
                                    <form class="form-horizontal" name="imprimirform" action="../informes/Imp_OrdenCompra.php" onsubmit="return submitForm();" method="post" role="form">
						<div class="form-group">
							
							<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								Ingrese el codigo de Pago que desea Imprimir..
                                                                 <input type="text" name="txtOrdenImprimir" class="form-control" id="txtOrdenImprimir" required="true" />
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