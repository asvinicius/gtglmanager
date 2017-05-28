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
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Por favor, faça login</h3>
                        </div>
                        <div class="panel-body">
                            <?php if($loginfail != null){ ?>
                                <div class="alert alert-<?php echo $loginfail["class"]; ?>"> <?php echo $loginfail["message"]; ?> </div>
                            <?php } ?>
                            <form method="get" action="<?= base_url('login/signin');?>" >
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                    </div>
                                    <button type="submit" class="btn btn-lg btn-success btn-block">Entrar</button>
                                </fieldset>
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
