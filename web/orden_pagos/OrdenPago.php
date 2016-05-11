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

    <title>SGP-INTN-Orden Pagos</title>
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
    function realizarPago(codigo){
        
        $('tr').click(function() {
        indi = $(this).index();
       
        //var codigoFactura=document.getElementById("dataTables-example").rows[indi+1].cells[0].innerText;
	var numeroFactura=document.getElementById("dataTables-example").rows[indi+1].cells[2].innerText;
        var monto=document.getElementById("dataTables-example").rows[indi+1].cells[5].innerText;
        
        document.getElementById("txtCodigo").value = codigo;
        document.getElementById("txtFacturaA").value = numeroFactura;
        document.getElementById("txtMontoA").value = monto;
       
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
                      <h1 class="page-header">Generar Ordenes de Pago - <small>SGP-INTN</small></h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Facturas
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th style='display:none'>Codfactura</th>
                                            <th style='display:none'>Codproveedor</th>
                                            <th>Factura Nro</th>
                                            <th>Fecha</th>
                                            <th>Proveedor</th>
                                            <th>Monto</th>
                                            <th>IVA</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select fac.pro_cod,fac.fac_cod,fac.fac_nro,pro.pro_razon,
                    fac.fac_monto,fac.fac_iva,fac.fac_fecha,fac.fac_activo
                    from facturas fac, proveedores pro
                    where fac.pro_cod=pro.pro_cod and
                    fac.fac_activo='t' and fac.fac_retencion='t'
                    ";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        $estado=$row1["fac_activo"];
                        if($estado=='t'){$estado='Activo';}else{$estado='Inactivo';}
                        echo "<tr><td style='display:none'>".$row1["fac_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["pro_cod"]."</td>";
                        echo "<td>".$row1["fac_nro"]."</td>";
                        echo "<td>".$row1["fac_fecha"]."</td>";
                        echo "<td>".$row1["pro_razon"]."</td>";
                        echo "<td>".$row1["fac_monto"]."</td>";
                        echo "<td>".$row1["fac_iva"]."</td>";
                        echo "<td>";?>
                        <a onclick='realizarPago(<?php echo $row1["fac_cod"];?>)' class="btn btn-success btn-xs bg-success" data-toggle="modal" data-target="#agregarform" role="button">Generar Pago</a>
                        <?php
                        echo "</td></tr>";
                    }
                    pg_free_result($result);
                    ?>
                                    </tbody>
                                </table>
                            </div>
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
      
        <!-- /#MODAL PARA GENERAR ORDEN DE PAGO -->
        <div class="modal fade" id="agregarform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-floppy-disk"></i> Agregar Registro</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form  autocomplete="off" class="form-horizontal" name="agregarform" action="../class/ClsPagos.php" method="post" role="form">
					<div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Factura Nro.</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtFacturaA" class="form-control" id="txtFacturaA" readonly="true" />
                                                <input type="hidden" name="txtCodigo" class="form-control" id="txtCodigo"  />
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Cuentas</label>
                                            <div class="col-sm-10">
                                           <select name="txtCuentasC" class="form-control" id="txtCuentasC" required="true">
                                                <?php
                                                //esto es para mostrar un select que trae datos de la BDD
                                                conexionlocal();
                                                $query = "Select cuen_cod,cuen_nom from cuentas where cuen_activo='t' ";
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
                                            <label  class="col-sm-2 control-label" for="input03">Pago/Fuente</label>
                                            <div class="col-sm-10">
                                            <div class="radio">
                                            <label><input type="radio" name="txtFuenteA" value="10" checked /> Fuente 10</label></br>
                                            <label><input type="radio" name="txtFuenteA" value="30" /> Fuente 30</label></br>
                                            
                                            </div>
                                            </div>
					</div>	
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Monto</label>
                                            <div class="col-sm-10">
                                            <input type="text" name="txtMontoA" class="form-control" id="txtMontoA" readonly="true" />
                                            </div>
					</div>
                                         <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Numero Cheque</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtChequeA" class="form-control" id="txtChequeA" placeholder="ingrese nro de cheque" required="true" />
                                            </div>
					</div>
                                       
				<!-- Modal Footer -->
                                    <div class="modal-footer">
                                            <button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" name="agregar" class="btn btn-primary">Realizar Pago</button>
                                    </div>
                                 </form>
				</div>
				</div>
			</div>
		</div>
    
</html>