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

        <link rel="stylesheet" href="<?php echo esc_url(getSiteURL('public/assets/css/bootstrap.min.css')) ?>" />
		<link rel="stylesheet" href="<?php echo esc_url(getSiteURL('public/assets/css/font-awesome.min.css')) ?>" />
		<link rel="stylesheet" href="<?php echo esc_url(getSiteURL('public/assets/css/style.css')) ?>" />
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
                    <a class="navbar-brand" href="<?php echo esc_url(getHomeURL()) ?>">
                        <?php _e('eBloodBank') ?>
					</a>
				</div>
				<div id="navbar-collapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="<?php echo esc_url(getHomeURL()) ?>"><?php _e('Home') ?></a></li>
                        <?php echo getDonorsLink(array( 'content' => __('Donors'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getCitiesLink(array( 'content' => __('Cities'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getDistrictsLink(array( 'content' => __('Districts'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getUsersLink(array( 'content' => __('Users'), 'before' => '<li>', 'after' => '</li>' )) ?>
					</ul>
					<ul class="nav navbar-nav pull-right">
                        <?php if (isUserLoggedIn()) : ?>
                        <li><a href="<?php echo esc_url(getEditUserURL(getCurrentUserID())) ?>"><?php printf(__('Hello, <b>%s</b>!'), getCurrentUser()->get('logon')) ?></a></li>
                        <li><a href="<?php echo esc_url(getLogoutURL()) ?>"><?php _e('Logout') ?></a></li>
                        <?php else: ?>
                        <li><a href="<?php echo esc_url(getLoginURL()) ?>"><?php _e('Anonymous, Login?') ?></a></li>
                        <li><a href="<?php echo esc_url(getSignupURL()) ?>"><?php _e('Sign up') ?></a></li>
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

