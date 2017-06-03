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
                            Banco da liga
                            <a href="<?= base_url('bank/addquota');?>" class="btn btn-primary pull-right">Adicionar cota</a>
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h5>
                            <div class="alert alert-info">
                                <?php if($accumulated){
                                    echo "Total acumulado: R$ ".$accumulated.",00";
                                } ?>
                            </div>
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Visão individual
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php if($individual){ ?>
                                            <thead>
                                                <tr>
                                                    <th>Cartoleiro</th>
                                                    <th>Situação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($individual as $bloke){ ?>
                                                    <tr>
                                                        <td><?php echo $bloke->namecoach ?></td>
                                                        <td <?php if($bloke->amount <= 0){ echo 'class="text-success"'; }else{echo 'class="text-danger"';}?>>
                                                            <?php if($bloke->amount <= 0){
                                                                    echo "Em dia";
                                                                  }else{
                                                                      if($bloke->amount == 1){
                                                                          echo $bloke->amount." Pendente";
                                                                      }else{
                                                                          echo $bloke->amount." Pendentes";                                                                      
                                                                      }
                                                                  } 
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        <?php }else{
                                            echo '<h4>Nenhum pagamento foi efetuado até o momento.</h4>';
                                        }?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Visão Geral
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php if($wallet){ ?>
                                            <thead>
                                                <tr>
                                                    <th>Mês</th>
                                                    <th>Arrecadado</th>
                                                    <th>Prêmios</th>
                                                    <th>Acumulado</th>
                                                    <th> </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($wallet as $reg){ ?>
                                                    <tr>
                                                        <td>
                                                            <?php 
                                                                switch ($reg->reference) {
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
                                                        <td><?php echo "R$ ".$reg->collected.",00" ?></td>
                                                        <td><?php echo "R$ ".$reg->premium.",00" ?></td>
                                                        <td><?php echo "R$ ".$reg->accumulated.",00" ?></td>
                                                        <td><?php 
                                                                if($reg->premium > 0){
                                                                    echo '<p class="fa fa-check" title="Concluído"></p>';
                                                                }else{
                                                                    echo '<p class="fa fa-ellipsis-h" title="Em andamento"></p>';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        <?php }else{
                                            echo '<h4>Nenhum pagamento foi efetuado até o momento.</h4>';
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
