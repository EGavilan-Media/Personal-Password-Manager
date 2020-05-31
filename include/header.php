<?php

    //header.php
    
    session_start();

    if(!isset($_SESSION["email"]))
    {
        header('location: login.html');
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Personal Password Manager</title>
    <!-- Bootstrap v4.4.1 -->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.css">
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Font Awesome Free 5.12.0 -->
    <link rel="stylesheet" type="text/css" href="vendor/fontawesome/css/all.css">
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Password Manager</a><button
            class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-lock"></i></div>
                            Passwords
                        </a>
                        <a class="nav-link" href="category.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                            Categories
                        </a>
                        <a class="nav-link" href="note.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sticky-note"></i></div>
                            Notes
                        </a>
                        <a class="nav-link" href="settings.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                            Settings
                        </a>
                        <a class="nav-link" href="about.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-info-circle"></i></div>
                            About
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $_SESSION['first_name']. " " .$_SESSION['last_name']; ?>
                </div>
            </nav>
        </div>