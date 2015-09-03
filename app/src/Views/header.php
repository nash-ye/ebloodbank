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
		<title><?php echo escHTML($this->get('title')) ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?php echo escURL(getSiteURL('public/bootstrap/css/bootstrap.min.css')) ?>" />
		<link rel="stylesheet" href="<?php echo escURL(getSiteURL('public/assets/css/style.css')) ?>" />
	</head>

	<body>

		<!-- Navigation -->
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
						<span class="sr-only"><?php _e('Toggle navigation') ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
                    <a class="navbar-brand" href="<?php echo escURL(getHomeURL()) ?>"><?php _e('eBloodBank') ?></a>
				</div>
				<div id="navbar-collapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-left">
						<li><a href="<?php echo escURL(getHomeURL()) ?>"><?php _e('Home') ?></a></li>
                        <?php echo getDonorsLink(array( 'content' => __('Donors'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getCitiesLink(array( 'content' => __('Cities'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getDistrictsLink(array( 'content' => __('Districts'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getUsersLink(array( 'content' => __('Users'), 'before' => '<li>', 'after' => '</li>' )) ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
                        <?php if (isUserLoggedIn()) : ?>
                        <?php echo getEditUserLink(array( 'id' => getCurrentUserID(), 'content' => sprintf(__('Hello, <b>%s</b>!'), getCurrentUser()->get('name')), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getLogoutLink(array( 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php else: ?>
                        <?php echo getLoginLink(array( 'content' => __('Anonymous, Log In?'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo getSignupLink(array( 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php endif; ?>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>

		<!-- Page Content -->
		<div class="container">

            <header class="page-header">
                <h1><?php echo escHTML($this->get('title')) ?></h1>
            </header>

