<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
ini_set(“display_errors”, 0 );
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <title>GT Grades League</title>

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
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-brand" title="GT BET" href="<?= base_url();?>">GT Grades League</a>
                </div>
                 <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" title="Logout" href="<?= base_url('login/signout');?>">
                            <i class="fa fa-sign-out fa-fw"></i>
                        </a>
                    </li>
                 </ul>
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a title="Parciais" href="<?= base_url('partial');?>"><i class="fa fa-refresh fa-fw"></i> Parciais</a>
                            </li>
                        </ul>
                        <ul class="nav" id="side-menu">
                            <li>
                                <a title="Geral" href="<?= base_url('overall');?>"><i class="fa fa-list-ol fa-fw"></i> Ranking Geral</a>
                            </li>
                        </ul>
                        <ul class="nav" id="side-menu">
                            <li>
                                <a title="Mês" href="<?= base_url('monthly');?>"><i class="fa fa-calendar fa-fw"></i> Ranking Mês</a>
                            </li>
                        </ul>
                        <ul class="nav" id="side-menu">
                            <li>
                                <a title="Banco" href="<?= base_url('bank');?>"><i class="fa fa-usd fa-fw"></i> Banco</a>
                            </li>
                        </ul>
                        <ul class="nav" id="side-menu">
                            <li>
                                <a title="Informações" href="<?= base_url('report');?>"><i class="fa fa-info fa-fw"></i> Informações</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
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
