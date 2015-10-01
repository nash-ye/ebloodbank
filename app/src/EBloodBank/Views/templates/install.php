<?php
/**
 * Install page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

?>
<!doctype html>
<html lang="<?= EBB\escAttr(p__('language code', 'en')) ?>" dir="<?= EBB\escAttr(p__('text direction', 'ltr')) ?>">
	<head>
		<meta charset="UTF-8">
		<title><?= EBB\escHTML(__('eBloodBank &rsaquo; Installation')) ?></title>
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
		<nav class="navbar" role="navigation">
			<div class="container">
				<div class="navbar-header">
                    <a class="navbar-brand" href="<?= EBB\escURL(EBB\getHomeURL()) ?>">
                        <?= EBB\escHTML(__('eBloodBank')) ?>
					</a>
				</div>
			</div>
		</nav>

		<!-- Page Content -->
		<div class="container">

            <?php if ($view->get('status') === 'installing') : ?>

                <?php if ($view->get('step') === 1) : ?>

                    <header>
                        <h1><?= EBB\escHTML(__('Configuration')) ?></h1>
                    </header>

                    <?php $view->displayView('notices') ?>

                    <p><?= EBB\escHTML(__('Welcome to eBloodBank, Before getting started, we need some information on the database. You will need to know the following items before proceeding.')) ?></p>

                    <ol>
                        <li><?= EBB\escHTML(__('Database name')) ?></li>
                        <li><?= EBB\escHTML(__('Database username')) ?></li>
                        <li><?= EBB\escHTML(__('Database password')) ?></li>
                        <li><?= EBB\escHTML(__('Database host')) ?></li>
                    </ol>

                    <p><?= EBB\escHTML(__('We’re going to use this information to setup the database. You should open "app/config.php" file in a text editor, fill in your information, and save it.')) ?></p>

                    <form id="form-install" class="form-horizontal" method="POST">

                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary"><?= EBB\escHTML(__('Run the install')) ?></button>
                            </div>
                        </div>

                    </form>

                <?php elseif ($view->get('step') === 2) : ?>

                    <header>
                        <h1><?= EBB\escHTML(__('Installation')) ?></h1>
                    </header>

                    <?php $view->displayView('notices') ?>

                    <p><?= EBB\escHTML(__('Welcome to the installation process! Just fill in the information below and you’ll be on your way to using the eBloodBank premium system.')) ?></p>

                    <form id="form-install" class="form-horizontal" method="POST">

                        <fieldset>

                            <legend><?= EBB\escHTML(__('Site Information')) ?></legend>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="site_name"><?= EBB\escHTML(__('Site Name')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" name="site_name" id="site_name" class="form-control" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="site_email"><?= EBB\escHTML(__('Site E-mail')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="email" name="site_email" id="site_email" class="form-control" required />
                                </div>
                            </div>

                        </fieldset>

                        <fieldset>

                            <legend><?= EBB\escHTML(__('Administrator Account')) ?></legend>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="user_name"><?= EBB\escHTML(__('Name')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" name="user_name" id="user_name" class="form-control" value="" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="user_email"><?= EBB\escHTML(__('E-mail')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="email" name="user_email" id="user_email" class="form-control" value="" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="user_pass"><?= EBB\escHTML(__('Password')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="password" name="user_pass_1" id="user_pass_1" class="form-control" value="" placeholder="<?= EBB\escAttr(__('Type your password')) ?>" autocomplete="off" />
                                    &nbsp;
                                    <input type="password" name="user_pass_2" id="user_pass_2" class="form-control" value="" placeholder="<?= EBB\escAttr(__('Type your password again')) ?>" autocomplete="off" />
                                </div>
                            </div>

                        </fieldset>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary"><?= EBB\escHTML(__('Install eBloodBank')) ?></button>
                            </div>
                        </div>

                    </form>

                <?php endif; ?>

            <?php elseif ($view->get('status') === 'installed') : ?>

                <header>
                    <h1><?= EBB\escHTML(__('Already Installed')) ?></h1>
                </header>

                <p><?= EBB\escHTML(__('You have already installed eBloodBank.')) ?></p>

            <?php endif; ?>

		</div>

        <script src="<?= EBB\escURL(EBB\getSiteURl('/public/jquery/jquery.min.js')) ?>"></script>
		<script src="<?= EBB\escURL(EBB\getSiteURl('/public/bootstrap/js/bootstrap.min.js')) ?>"></script>

	</body>

</html>
