
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ONM-Ingreso Detalle</title>
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
    
    </script>
	<script type="text/javascript">
		function Redirigir(){
			window.location="http://localhost/app/ONM/web/menu.php";
		};
                function eliminar(codigo){
			document.getElementById("txtCodigoE").value = codigo;
		};
	</script>
</head>

<body>
        <?php 
        include("../funciones.php");
        conexionlocal();
        ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                      <h1 class="page-header">Compras Detalle</h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Lista Detalle
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th style='display:none'>Codigo</th>
                                            <th>Suministro</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Exentas</th>
                                            <th>Subtotal</th>
                                            <th>Borrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "Select max(ord_cod) from orden_compras;";
                    $resultado=pg_query($query);
                    $row=  pg_fetch_array($resultado);
                    $codcabecera=$row[0];
                    $query = "select comdet.comdet_cod,pro.pro_nom,comdet.comdet_cant,
                            comdet.comdet_precio,(comdet.comdet_cant*comdet.comdet_precio) as comdet_subtotal,comdet_iva,comdet_exento 
                            from compras_detalles comdet,orden_compras ord, productos pro 
                            where ord.ord_cod=comdet.ord_cod
                            and comdet.pro_cod=pro.pro_cod
                            and ord.ord_cod=$codcabecera";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        
                        echo "<tr><td style='display:none'>".$row1["comdet_cod"]."</td>";
                        echo "<td>".$row1["pro_nom"]."</td>";
                        echo "<td>".$row1["comdet_cant"]."</td>";
                        echo "<td>".$row1["comdet_precio"]."</td>";
                        echo "<td>".$row1["comdet_exento"]."</td>";
                        echo "<td>".$row1["comdet_subtotal"]."</td>";
                        echo "<td>";?>
                        <a onclick='eliminar(<?php echo $row1["comdet_cod"];?>)' class="btn btn-danger btn-xs active" data-toggle="modal" data-target="#modalbor" role="button">Borrar</a>
                        <?php
                        echo "</td></tr>";
                    }
                    pg_free_result($result);
                    ?>
                                    </tbody>
                                </table>
                                 <a class="btn btn-primary" data-toggle="modal" data-target="#modalagr" role="button">Nuevo Detalle</a>
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
                                    <form  autocomplete="off" class="form-horizontal" name="agregarform" action="../class/ClsComprasDetalle.php" method="post" role="form">
						
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Cantidad</label>
                                            <div class="col-sm-10">
                                            <input type="number" name="txtCantidadA" class="form-control" id="txtCantidadA" placeholder="ingrese cantidad" required />
                                            </div>
					</div>
					<div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Descripcion</label>
                                            <div class="col-sm-10">
                                                <select name="txtProductoA" class="form-control" id="txtProductoA" required>
                                                    <?php
                                                    //esto es para mostrar un select que trae datos de la BDD
                                                    conexionlocal();
                                                    $query = "Select pro_cod,pro_nom from productos where pro_activo='t'";
                                                    $resultadoSelect = pg_query($query);
                                                    while ($row = pg_fetch_row($resultadoSelect)) {
                                                        echo "<option value=" . $row[0] . ">";
                                                        echo $row[1];
                                                        echo "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Precio</label>
                                            <div class="col-sm-10">
                                            <input type="number" name="txtPrecioA" id="precio" class="form-control" id="txtPrecioA" />
                                            </div>
					</div>

                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Exentas</label>
                                            <div class="col-sm-10">
                                            <input type="number" name="txtExentaA" id="exentas" class="form-control" id="txtPrecioA" />
                                            </div>
					</div>
                                        <div class="checkbox">
                                            <label><input type="radio" name="txtIVA" value="1"  />IVA 5</label>
                                            <label><input type="radio" name="txtIVA" value="2" checked />IVA 10</label>
                                            <label><input type="radio" name="txtIVA" value="3"  />Excentas</label>
                                        </div>
                                        <div class="modal-footer">
					<button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="agregar" class="btn btn-primary">Agregar</button>
					
                                        </div>
                                    </form>
				</div>
				
				<!-- Modal Footer -->
				
                                
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
                                    <form class="form-horizontal" name="borrarform" action="../class/ClsComprasDetalle.php" onsubmit="return submitForm();" method="post" role="form">
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
    
</html>