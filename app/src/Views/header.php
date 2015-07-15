<?php
/**
 * The Header
 *
 * @package    eBloodBank
 * @subpackage Views
 */
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $__title ?></title>
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
						<li><a href="<?php echo getSiteURL() ?>"><?php _e('Home') ?></a></li>
                        <?php if (isCurrentUserCan('manage_donors')) : ?>
                        <li><a href="<?php echo getPageURL('manage-donors') ?>"><?php _e('Donors') ?></a></li>
                        <?php endif; ?>
                        <?php if (isCurrentUserCan('manage_cites')) : ?>
                        <li><a href="<?php echo getPageURL('manage-cites') ?>"><?php _e('Cities') ?></a></li>
                        <?php endif; ?>
                        <?php if (isCurrentUserCan('manage_distrs')) : ?>
                        <li><a href="<?php echo getPageURL('manage-distrs') ?>"><?php _e('Districts') ?></a></li>
                        <?php endif; ?>
                        <?php if (isCurrentUserCan('manage_users')) : ?>
                        <li><a href="<?php echo getPageURL('manage-users') ?>"><?php _e('Users') ?></a></li>
                        <?php endif; ?>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>

		<!-- Page Content -->
		<div class="container">
