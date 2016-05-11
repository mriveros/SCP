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

     <title>SGP-INTN-Detalles</title>
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
</head>

<body>
        <?php 
        include("../funciones.php");
        include("../menu.php");
        conexionlocal();
        ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row" >
                <div class="col-lg-12">
                      <h1 class="page-header">Listado Detalle</h1>
                </div>	
            </div>
            <input type="hidden" name='txtCodigo' id='Codigo'>
            <!-- /.row -->
            <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                Listado de Suministros
                                            </div>
                                            <!-- /.panel-heading -->
                                          
                                            <div class="panel-body" >
                                                <div class="dataTable_wrapper" >
                                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                        <thead>
                                                            <tr class="success">
                                                                <th>Descripcion</th>
                                                                <th>Cantidad</th>
                                                                <th>Precio</th>
                                                                <th>Subtotal</th>
                                                                <th>Exento</th>
                                                                <th>IVA 5%</th>
                                                                <th>IVA 10%</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                        <?php
                                        if  (empty($_POST['txtCodigo'])){$codigo=0;}else{ $codigo = $_POST['txtCodigo'];}
                                        $query = "select pro.pro_nom,cd.comdet_cant,cd.comdet_precio,cd.comdet_subtotal,COALESCE(cd.comdet_exento,0) as comdet_exento,COALESCE(cd.comdet_iva5,0)as comdet_iva5,COALESCE(cd.comdet_iva10,0) as comdet_iva10 
                                        from compras_detalles cd, productos pro
                                        where pro.pro_cod=cd.pro_cod
                                        and cd.ord_cod=$codigo" ;
                                        $result = pg_query($query) or die ("Error al realizar la consulta".$codigo);
                                        while($row1 = pg_fetch_array($result))
                                        {
                                            echo "<tr><td>".$row1["pro_nom"]."</td>";
                                            echo "<td>".$row1["comdet_cant"]."</td>";
                                            echo "<td>".$row1["comdet_precio"]."</td>";
                                            echo "<td>".$row1["comdet_subtotal"]."</td>";
                                            echo "<td>".$row1["comdet_exento"]."</td>";
                                            echo "<td>".$row1["comdet_iva5"]."</td>";  
                                            echo "<td>".$row1["comdet_iva10"]."</td>";
                                        }?>
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
    <!-- /#wrapper -->
</html>