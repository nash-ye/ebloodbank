<?php
/**
 * Page header template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

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

        <script src="<?= EBB\escURL(EBB\getSiteURl('/public/jquery/jquery.min.js')) ?>"></script>
	</head>

	<body>

		<!-- Navigation -->
		<nav class="navbar" role="navigation">
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
                        <?= EBB\getHomeLink(['content' => EBB\escHTML(__('Home')), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getDonorsLink(['content' => EBB\escHTML(__('Donors')) . EBB\getPendingDonorsCountBadge(), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getCitiesLink(['content' => EBB\escHTML(__('Cities')), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getDistrictsLink(['content' => EBB\escHTML(__('Districts')), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getUsersLink(['content' => EBB\escHTML(__('Users')) . EBB\getPendingUsersCountBadge(), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getSettingsLink(['content' => EBB\escHTML(__('Settings')), 'before' => '<li>', 'after' => '</li>']) ?>
					</ul>
					<ul class="nav navbar-nav navbar-right flip">
                        <?php if (EBB\isUserLoggedIn()) : ?>
                        <?= EBB\getEditUserLink(['id' => EBB\getCurrentUserID(), 'content' => sprintf(__('Hello, <b>%s</b>!'), EBB\escHTML(EBB\getCurrentUser()->get('name'))), 'before' => '<li>', 'after' => '</li>']) ?>
                        <?= EBB\getLogoutLink(['before' => '<li>', 'after' => '</li>']) ?>
                        <?php else : ?>
                        <?= EBB\getLoginLink(['content' => EBB\escHTML(__('Anonymous, Log In?')), 'before' => '<li>', 'after' => '</li>']) ?>
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

            <?php if (! $view->get('page_header.hide')) : ?>
            <header class="page-header">
                <h1><?= EBB\escHTML($view->isExists('page_header.title') ? $view->get('page_header.title') : $view->get('title')) ?></h1>
            </header>
            <?php endif; ?>
