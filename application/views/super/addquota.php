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
                        <h1 class="page-header">Adicionar cota</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <form role="form" method="get" action="<?= base_url('bank/savequota'); ?>">
                            <div class="form-group">
                                <label for="league">Cartoleiro</label>
                                <select class="form-control" id="team" name="team" required="true">
                                    <option value="0">    </option>
                                    <?php foreach ($teams as $team){ ?>
                                        <option value="<?= $team->idteam; ?>"> <?php echo $team->namecoach; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Adicionar</button>
                            <a class="btn btn-danger" href="<?= base_url('bank/cancel'); ?>">Cancelar</a>
                        </form>
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
