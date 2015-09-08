<?php
/**
 * The Header
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
?>
<!doctype html>
<html lang="<?php echo EBB\escAttr(p__('language code', 'en')) ?>" dir="<?php echo EBB\escAttr(p__('text direction', 'ltr')) ?>">
	<head>
		<meta charset="UTF-8">
		<title><?php echo EBB\escHTML($this->get('title')) ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?php echo EBB\escURL(EBB\getSiteURL('public/bootstrap/css/bootstrap.min.css')) ?>" />
        <?php if ('rtl' === p__('text direction', 'ltr')) : ?>
        <link rel="stylesheet" href="<?php echo EBB\escURL(EBB\getSiteURL('public/bootstrap/css/bootstrap-rtl.min.css')) ?>" />
        <?php endif; ?>
		<link rel="stylesheet" href="<?php echo EBB\escURL(EBB\getSiteURL('public/assets/css/style.css')) ?>" />
	</head>

	<body>

		<!-- Navigation -->
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
						<span class="sr-only"><?php __e('Toggle navigation') ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
                    <a class="navbar-brand" href="<?php echo EBB\escURL(EBB\getHomeURL()) ?>"><?php __e('eBloodBank') ?></a>
				</div>
				<div id="navbar-collapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-left flip">
						<li><a href="<?php echo EBB\escURL(EBB\getHomeURL()) ?>"><?php __e('Home') ?></a></li>
                        <?php echo EBB\getDonorsLink(array( 'content' => __('Donors'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo EBB\getCitiesLink(array( 'content' => __('Cities'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo EBB\getDistrictsLink(array( 'content' => __('Districts'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo EBB\getUsersLink(array( 'content' => __('Users'), 'before' => '<li>', 'after' => '</li>' )) ?>
					</ul>
					<ul class="nav navbar-nav navbar-right flip">
                        <?php if (EBB\isUserLoggedIn()) : ?>
                        <?php echo EBB\getEditUserLink(array( 'id' => EBB\getCurrentUserID(), 'content' => sprintf(__('Hello, <b>%s</b>!'), EBB\getCurrentUser()->get('name')), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo EBB\getLogoutLink(array( 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php else: ?>
                        <?php echo EBB\getLoginLink(array( 'content' => __('Anonymous, Log In?'), 'before' => '<li>', 'after' => '</li>' )) ?>
                        <?php echo EBB\getSignupLink(array( 'before' => '<li>', 'after' => '</li>' )) ?>
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
                <h1><?php echo EBB\escHTML($this->get('title')) ?></h1>
            </header>

