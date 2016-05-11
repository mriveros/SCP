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

    <title>SGP-INTN-Imprimir Retenciones</title>
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
    function imprimeRetencion(codigo){
       
			document.getElementById("txtCodigoFactura").value = codigo;
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
                      <h1 class="page-header">Retenciones a Facturas - <small>SGP-INTN</small></h1>
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
                                            <th style='display:none'>Codigo</th>
                                            <th style='display:none'>Codigo</th>
                                            <th>Factura Nro</th>
                                            <th>Fecha</th>
                                            <th>Proveedor</th>
                                            <th>Retencion</th>
                                            <th>Monto Retencion</th>
                                            <th>Fecha</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select pagret.pagret_cod,fac.fac_cod,fac.fac_nro,to_char(fac.fac_fecha,'DD/MM/YYYY') as fac_fecha,
                    pro.pro_razon,ret.ret_nom,pagret.pagret_monto,to_char(pagret.pagret_fecha,'DD/MM/YYYY') as pagret_fecha
                    from facturas fac, proveedores pro,pago_retenciones pagret,retenciones ret
                    where fac.pro_cod=pro.pro_cod 
                    and pagret.fac_cod=fac.fac_cod
                    and ret.ret_cod=pagret.ret_cod";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                     
                        
                        echo "<tr><td style='display:none'>".$row1["pagret_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["fac_cod"]."</td>";
                        echo "<td>".$row1["fac_nro"]."</td>";
                        echo "<td>".$row1["fac_fecha"]."</td>";
                        echo "<td>".$row1["pro_razon"]."</td>";
                        echo "<td>".$row1["ret_nom"]."</td>";
                        echo "<td>".$row1["pagret_monto"]."</td>";
                        echo "<td>".$row1["pagret_fecha"]."</td>";
                        echo "<td>";?>
                        <a onclick='imprimeRetencion(<?php echo $row1["pagret_cod"];?>)' class="btn btn-success btn-xs bg-success" data-toggle="modal" data-target="#modalretencion" role="button">Imprimir Retencion</a>
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
      
        <!-- /#MODAL PARA Imprimir las Retenciones -->
        <div class="modal fade" id="modalretencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-alert"></i> Imprimir Retencion</h3>
				</div>
                                
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form class="form-horizontal" name="borrarform" action="../informes/Imp_retencion.php" onsubmit="return submitForm();" method="post" role="form">
					
                                        <div class="form-group">
							<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								¡¡¡ATENCION!!! ...Se Imprimirá la retención de la Factura
                                                                <input type="hidden" name="txtCodigoFactura" class="form-control" id="txtCodigoFactura"  required="true"/>
							</div>
						</div>
				</div>
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="calcularRetencion" class="btn btn-danger">Imprimir</button>
					</form>
				</div>
			</div>
		</div>
	</div>
    
</html>