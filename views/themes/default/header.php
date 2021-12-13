<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php echo cms_get_seo_meta_robot(); ?>

    <title><?php echo cms_meta_title(get_system_option(array('option_name'=>'website_name')), "|"); ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php bloginfo('template_directory'); ?>/resources/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="<?php bloginfo('template_directory'); ?>/resources/css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php bloginfo('template_directory'); ?>/resources/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href='<?php bloginfo('template_directory'); ?>/resources/fonts/font_1.css' rel='stylesheet' type='text/css'>
    <link href='<?php bloginfo('template_directory'); ?>/resources/fonts/font_2.css' rel='stylesheet' type='text/css'>

    <link href='<?php bloginfo('template_directory'); ?>/style.css' rel='stylesheet' type='text/css'>
    <link href='<?php bloginfo('template_directory'); ?>/style.css' rel='stylesheet' type='text/css'>

    <?php echo cms_get_canonical(); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php bloginfo("installation_url"); ?>"><?php echo get_system_option(array('option_name'=>'website_name')) ?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggsling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                <?php cms_display_menu( array('name' => 'Main Menu') ); ?>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <?php if (cms_is_login()): ?>
                    <li>
                        <a href="javascript:void(0)">Welcome, <?php echo cms_get_login_user_info("firstname"); ?></a>
                        <ul>
                            <li><a href="<?php echo bloginfo("baseurl"); ?>customer/logout/">Logout</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li>
                        <a href="<?php echo bloginfo("baseurl"); ?>customer/login/">Login / Sign Up</a>
                    </li>
                    <?php endif ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('<?php bloginfo('template_directory'); ?>/resources/img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                    <?php if (cms_get_post_title() !=""): ?>
                        <h1><?php echo cms_get_post_title() ?></h1>
                    <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </header>