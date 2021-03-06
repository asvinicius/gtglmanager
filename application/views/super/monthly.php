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
                            Ranking do Mês
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <?php if($ranking){ ?>
                                    <thead>
                                        <tr>
                                            <th title="Posição">#</th>
                                            <th title="Nome do cartoleiro">Cartoleiro</th>
                                            <th title="Nome do time">Time</th>
                                            <th title="Pontuação total">Pontuação</th>
                                            <th title="Patrimônio total">Patrimônio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $cont = 1;
                                            $final = 0;
                                            foreach ($ranking as $ranked) {
                                                $final = $final+1;
                                            }
                                            foreach ($ranking as $ranked){ ?>
                                        <tr <?php switch ($cont) {
                                                            case 1:
                                                                echo 'class="success"';
                                                                break;
                                                            case $final:
                                                                echo 'class="danger"';
                                                                break;
                                                        } ?>>
                                                <td><?php echo $cont; ?></td>
                                                <td><?php echo $ranked->namecoach ?></td>
                                                <td><?php echo $ranked->nameteam ?></td>
                                                <td><?php echo number_format($ranked->rating, 2) ?></td>
                                                <td><?php echo "C$ ".$ranked->patrimony ?></td>
                                            </tr>
                                        <?php $cont++;

                                            } ?>
                                    </tbody>
                                <?php }else{
                                    echo '<h4>O ranking não está disponível no momento.</h4>';
                                }?>
                            </table>
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
