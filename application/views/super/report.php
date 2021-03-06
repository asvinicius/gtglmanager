<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
ini_set(“display_errors”, 0 );
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
        <link href="<?= base_url('assets/vendor/metisMenu/metisMenu.min.css');?>" rel="stylesheet">
        <link href="<?= base_url('assets/dist/css/sb-admin-2.css');?>" rel="stylesheet">
        <link href="<?= base_url('assets/vendor/morrisjs/morris.css');?>" rel="stylesheet">
        <link href="<?= base_url('assets/vendor/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div id="wrapper">
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Informações
                            <a href="<?= base_url('report/closemonth');?>" class="btn btn-primary pull-right">Concluir mês</a>
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-check fa-fw"></i> Conclusões                            
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php if($detail){ ?>
                                            <thead>
                                                <tr>
                                                    <th title="Mês">Mês</th>
                                                    <th title="Campeão">Campeão</th>
                                                    <th title="Lanterna">Lanterna</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($detail as $line){ ?>
                                                    <tr>
                                                        <td>
                                                            <?php 
                                                                switch ($line->month) {
                                                                    case 1:
                                                                        echo "MAI";
                                                                        break;
                                                                    case 2:
                                                                        echo "JUN";
                                                                        break;
                                                                    case 3:
                                                                        echo "JUL";
                                                                        break;
                                                                    case 4:
                                                                        echo "AGO";
                                                                        break;
                                                                    case 5:
                                                                        echo "SET";
                                                                        break;
                                                                    case 6:
                                                                        echo "OUT";
                                                                        break;
                                                                    case 7:
                                                                        echo "NOV";
                                                                        break;
                                                                    case 8:
                                                                        echo "DEZ";
                                                                        break;
                                                                    default:
                                                                        echo "---";
                                                                        break;
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $line->champion; ?></td>
                                                        <td><?php echo $line->worse ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        <?php }else{
                                            echo '<h4>Sem informação disponível.</h4>';
                                        }?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?= base_url('assets/vendor/jquery/jquery.min.js');?>"></script>
        <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.min.js');?>"></script>
        <script src="<?= base_url('assets/vendor/metisMenu/metisMenu.min.js');?>"></script>
        <script src="<?= base_url('assets/vendor/raphael/raphael.min.js');?>"></script>
        <script src="<?= base_url('assets/vendor/morrisjs/morris.min.js');?>"></script>
        <script src="<?= base_url('assets/data/morris-data.js');?>"></script>
        <script src="<?= base_url('assets/dist/js/sb-admin-2.js');?>"></script>
    </body>
</html>
