<?php
/**
 * The Header
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $this->get('title') ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="public/assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="public/assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="public/assets/css/style.css" />
	</head>

	<body>

		<!-- Navigation -->
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="navbar-collapse">
						<span class="sr-only"><?php _e('Toggle navigation') ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo getSiteURL() ?>">
                        <?php _e('eBloodBank') ?>
					</a>
				</div>
				<div id="navbar-collapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="<?php echo getSiteURL('/') ?>"><?php _e('Home') ?></a></li>
                        <?php if (isCurrentUserCan('view_donors')) : ?>
                        <li><a href="<?php echo getPageURL('view-donors') ?>"><?php _e('Donors') ?></a></li>
                        <?php endif; ?>
                        <?php if (isCurrentUserCan('view_cities')) : ?>
                        <li><a href="<?php echo getPageURL('view-cities') ?>"><?php _e('Cities') ?></a></li>
                        <?php endif; ?>
                        <?php if (isCurrentUserCan('view_districts')) : ?>
                        <li><a href="<?php echo getPageURL('view-districts') ?>"><?php _e('Districts') ?></a></li>
                        <?php endif; ?>
                        <?php if (isCurrentUserCan('view_users')) : ?>
                        <li><a href="<?php echo getPageURL('view-users') ?>"><?php _e('Users') ?></a></li>
                        <?php endif; ?>
					</ul>
					<ul class="nav navbar-nav pull-right">
                        <?php if (isUserLoggedIn()) : ?>
                        <li><a href="<?php echo getPageURL('edit-user', array( 'id' => getCurrentUserID() )) ?>"><?php printf(__('Hello, <b>%s</b>!'), getCurrentUser()->get('logon')) ?></a></li>
                        <li><a href="<?php echo getPageURL('login', array( 'action' => 'logout' )) ?>"><?php _e('Logout') ?></a></li>
                        <?php else: ?>
                        <li><a href="<?php echo getPageURL('login') ?>"><?php _e('Anonymous, Login?') ?></a></li>
                        <li><a href="<?php echo getPageURL('signup') ?>"><?php _e('Sign up') ?></a></li>
                        <?php endif; ?>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>

		<!-- Page Content -->
		<div class="container">

            <header>
                <h1><?php echo $this->get('title') ?></h1>
            </header>

