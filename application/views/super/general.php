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
                            Visão geral
                            <a href="<?= base_url('partial');?>" class="btn btn-primary pull-right" title="Voltar"><i class="fa fa-arrow-left fa-fw"></i></a>
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Parcial da rodada
                                <div class="pull-right">
                                    <a class="dropdown-toggle" title="Visão geral" href="#">
                                        <i class="fa fa-list-ul fa-fw"></i>
                                    </a>
                                </div>
                                
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php if($teams){ ?>
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Cartoleiro</th>
                                                    <th>Parcial</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($teams as $team){ ?>
                                                    <tr>
                                                        <td><?php echo $team['nome']; ?></td>
                                                        <td><?php echo $team['cartoleiro']; ?></td>
                                                        <td><?php echo $team['parcial']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        <?php }else{
                                            echo '<h4>Não há nenhum time cadastrado no sistema.</h4>';
                                        }?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Parcial do mês
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php if($ranking){ ?>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Time</th>
                                                    <th>Pontuação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $cont = 1;
                                                    foreach ($ranking as $ranked){ ?>
                                                <tr <?php switch ($cont) {
                                                                    case 1:
                                                                        echo 'class="success"';
                                                                        break;
                                                                    case 7:
                                                                        echo 'class="danger"';
                                                                        break;
                                                                } ?>>
                                                        <td><?php echo $cont; ?></td>
                                                        <td><?php echo $ranked['nome']; ?></td>
                                                        <td><?php echo $ranked['pontos']['mes']; ?></td>
                                                    </tr>
                                                <?php $cont++;
                                                
                                                    } ?>
                                            </tbody>
                                        <?php }else{
                                            echo '<h4>Não há nenhum time cadastrado no sistema.</h4>';
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
