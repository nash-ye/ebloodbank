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
use EBloodBank\Options;
?>
<!doctype html>
<html lang="<?= EBB\escAttr(p__('language code', 'en')) ?>" dir="<?= EBB\escAttr(p__('text direction', 'ltr')) ?>">
	<head>
		<meta charset="UTF-8">
		<title><?= EBB\escHTML($view->get('title')) ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?= EBB\escURL(EBB\getSiteURL('public/bootstrap/css/bootstrap.min.css')) ?>" />
        <?php if ('rtl' === p__('text direction', 'ltr')) : ?>
        <link rel="stylesheet" href="<?= EBB\escURL(EBB\getSiteURL('public/bootstrap/css/bootstrap-rtl.min.css')) ?>" />
        <?php endif; ?>
		<link rel="stylesheet" href="<?= EBB\escURL(EBB\getSiteURL('public/assets/css/style.css')) ?>" />
	</head>

	<body>

		<!-- Navigation -->
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
						<span class="sr-only"><?= EBB\escHTML(__('Toggle navigation')) ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
                    <a class="navbar-brand" href="<?= EBB\escURL(EBB\getHomeURL()) ?>"><?= EBB\escHTML(Options::getOption('site_name')) ?></a>
				</div>
				<div id="navbar-collapse" class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-left flip">
						<li><a href="<?= EBB\escURL(EBB\getHomeURL()) ?>"><?= EBB\escHTML(__('Home')) ?></a></li>
                        <?= EBB\getDonorsLink(['content' => __('Donors'), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getCitiesLink(['content' => __('Cities'), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getDistrictsLink(['content' => __('Districts'), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getUsersLink(['content' => __('Users'), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getSettingsLink(['content' => __('Settings'), 'before' => '<li>', 'after' => '</li>']) ?>
					</ul>
					<ul class="nav navbar-nav navbar-right flip">
                        <?php if (EBB\isUserLoggedIn()) : ?>
                        <?= EBB\getEditUserLink(['id' => EBB\getCurrentUserID(), 'content' => sprintf(__('Hello, <b>%s</b>!'), EBB\getCurrentUser()->get('name')), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getLogoutLink(['before' => '<li>', 'after' => '</li>']) ?>
                        <?php else: ?>
                        <?= EBB\getLoginLink(['content' => __('Anonymous, Log In?'), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getSignupLink(['before' => '<li>', 'after' => '</li>']) ?>
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
                <h1><?= EBB\escHTML($view->get('title')) ?></h1>
            </header>

