<?php
/**
 * Install page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;
d__
?>
<!doctype html>
<html lang="<?= EBB\escAttr(dp__('winry', 'language code', 'en')) ?>" dir="<?= EBB\escAttr(dp__('winry', 'text direction', 'ltr')) ?>">
	<head>
		<meta charset="UTF-8">
		<title><?= EBB\escHTML(d__('winry', 'eBloodBank &rsaquo; Installation')) ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?= EBB\escURL(EBB\getThemeURL('/assets/components/bootstrap/css/bootstrap.min.css')) ?>" />
        <?php if ('rtl' === dp__('winry', 'text direction', 'ltr')) : ?>
        <link rel="stylesheet" href="<?= EBB\escURL(EBB\getThemeURL('/assets/components/bootstrap-rtl/css/bootstrap-rtl.min.css')) ?>" />
        <?php endif; ?>
		<link rel="stylesheet" href="<?= EBB\escURL(EBB\getThemeURL('/assets/css/style.css')) ?>" />
	</head>

	<body>

		<!-- Navigation -->
		<nav class="navbar" role="navigation">
			<div class="container">
				<div class="navbar-header">
                    <a class="navbar-brand" href="<?= EBB\escURL(EBB\getHomeURL()) ?>">
                        <?= EBB\escHTML(d__('winry', 'eBloodBank')) ?>
					</a>
				</div>
			</div>
		</nav>

		<!-- Page Content -->
		<div class="container">

            <?php if ($view->get('status') === 'installing') : ?>

                <?php if ($view->get('step') === 1) : ?>

                    <header>
                        <h1><?= EBB\escHTML(d__('winry', 'Configuration')) ?></h1>
                    </header>

                    <?php $view->displayView('notices') ?>

                    <p><?= EBB\escHTML(d__('winry', 'Welcome to eBloodBank, Before getting started, we need some information on the database. You will need to know the following items before proceeding.')) ?></p>

                    <ol>
                        <li><?= EBB\escHTML(d__('winry', 'Database name')) ?></li>
                        <li><?= EBB\escHTML(d__('winry', 'Database username')) ?></li>
                        <li><?= EBB\escHTML(d__('winry', 'Database password')) ?></li>
                        <li><?= EBB\escHTML(d__('winry', 'Database host')) ?></li>
                    </ol>

                    <p><?php printf(EBB\escHTML(d__('winry', 'We’re going to use this information to setup the database. You should open %1$s file in a text editor, fill in your information, and save it as %2$s.')), '<code>app/config-sample.php</code>', '<code>app/config.php</code>') ?></p>

                    <form id="form-install" class="form-horizontal" method="POST">

                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary"><?= EBB\escHTML(d__('winry', 'Run the install')) ?></button>
                            </div>
                        </div>

						<input type="hidden" name="action" value="install" />

                    </form>

                <?php elseif ($view->get('step') === 2) : ?>

                    <header>
                        <h1><?= EBB\escHTML(d__('winry', 'Installation')) ?></h1>
                    </header>

                    <?php $view->displayView('notices') ?>

                    <p><?= EBB\escHTML(d__('winry', 'Welcome to the installation process! Just fill in the information below and you’ll be on your way to using the eBloodBank premium system.')) ?></p>

                    <form id="form-install" class="form-horizontal" method="POST">

                        <fieldset>

                            <legend><?= EBB\escHTML(d__('winry', 'Site Information')) ?></legend>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="site_name"><?= EBB\escHTML(d__('winry', 'Site Name')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" name="site_name" id="site_name" class="form-control" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="site_email"><?= EBB\escHTML(d__('winry', 'Site E-mail')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="email" name="site_email" id="site_email" class="form-control" required />
                                </div>
                            </div>

                        </fieldset>

                        <fieldset>

                            <legend><?= EBB\escHTML(d__('winry', 'Administrator Account')) ?></legend>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="user_name"><?= EBB\escHTML(d__('winry', 'Name')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" name="user_name" id="user_name" class="form-control" value="" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="user_email"><?= EBB\escHTML(d__('winry', 'E-mail')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="email" name="user_email" id="user_email" class="form-control" value="" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="user_pass"><?= EBB\escHTML(d__('winry', 'Password')) ?> <span class="form-required">*</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="password" name="user_pass_1" id="user_pass_1" class="form-control" value="" placeholder="<?= EBB\escAttr(d__('winry', 'Type your password')) ?>" autocomplete="off" />
                                    &nbsp;
                                    <input type="password" name="user_pass_2" id="user_pass_2" class="form-control" value="" placeholder="<?= EBB\escAttr(d__('winry', 'Type your password again')) ?>" autocomplete="off" />
                                </div>
                            </div>

                        </fieldset>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary"><?= EBB\escHTML(d__('winry', 'Install eBloodBank')) ?></button>
                            </div>
                        </div>

						<input type="hidden" name="action" value="install" />

                    </form>

                <?php endif; ?>

            <?php elseif ($view->get('status') === 'installed') : ?>

                <header>
                    <h1><?= EBB\escHTML(d__('winry', 'Already Installed')) ?></h1>
                </header>

                <p><?= EBB\escHTML(d__('winry', 'You have already installed eBloodBank.')) ?></p>

            <?php endif; ?>

		</div>

        <script src="<?= EBB\escURL(EBB\getThemeURL('/assets/components/jquery/jquery.min.js')) ?>"></script>
		<script src="<?= EBB\escURL(EBB\getThemeURL('/assets/components/bootstrap/js/bootstrap.min.js')) ?>"></script>

	</body>

</html>
