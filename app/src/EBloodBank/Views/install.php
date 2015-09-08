<?php
/**
 * Install Page
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
		<title><?php __e('eBloodBank') ?></title>
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
                    <a class="navbar-brand" href="<?php echo EBB\escURL(EBB\getHomeURL()) ?>">
                        <?php __e('eBloodBank') ?>
					</a>
				</div>
			</div>
		</nav>

		<!-- Page Content -->
		<div class="container">

            <?php if (1 === $step) : ?>

                <header>
                    <h1><?php __e('Step 1') ?></h1>
                </header>

                <p><?php __e('Welcome to eBloodBank, Before getting started, we need some information on the database. You will need to know the following items before proceeding.') ?></p>

                <ol>
                    <li><?php __e('Database name') ?></li>
                    <li><?php __e('Database username') ?></li>
                    <li><?php __e('Database password') ?></li>
                    <li><?php __e('Database host') ?></li>
                </ol>

                <p><?php __e('We’re going to use this information to setup the database. You can simply open <code>app/config.php</code> in a text editor, fill in your information, and save it.') ?></p>

                <form id="form-install" class="form-horizontal" method="POST">

                    <div class="form-group">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary"><?php __e('Install') ?></button>
                        </div>
                    </div>

                </form>

            <?php elseif (2 === $step) : ?>

                <header>
                    <h1><?php __e('Step 2') ?></h1>
                </header>

                <form id="form-install" class="form-horizontal" method="POST">

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label for="user_name"><?php __e('Name') ?></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="user_name" id="user_name" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label for="user_email"><?php __e('E-mail') ?></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="email" name="user_email" id="user_email" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label for="user_pass"><?php __e('Password') ?></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="password" name="user_pass_1" id="user_pass_1" class="form-control" value="" placeholder="<?php echo EBB\escAttr(__('Type your password')) ?>" autocomplete="off" />
                            &nbsp;
                            <input type="password" name="user_pass_2" id="user_pass_2" class="form-control" value="" placeholder="<?php echo EBB\escAttr(__('Type your password again')) ?>" autocomplete="off" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary"><?php __e('Sign Up') ?></button>
                        </div>
                    </div>

                </form>

            <?php elseif (3 === $step) : ?>

                <header>
                    <h1><?php __e('Finish!') ?></h1>
                </header>

                <p><?php __e('eBloodBank has been installed successfully.') ?></p>

                <div class="btn-group btn-group-lg" role="group">
                    <a href="<?php echo EBB\escURL(EBB\getHomeURL()) ?>" class="btn btn-default"><?php __e('Home') ?></a>
                    <a href="<?php echo EBB\escURL(EBB\getLoginURL()) ?>" class="btn btn-primary"><?php __e('Log In') ?></a>
                </div>

            <?php endif; ?>

		</div>

        <script src="<?php echo EBB\escURL(EBB\getSiteURl('/public/jquery/jquery.min.js')) ?>"></script>
		<script src="<?php echo EBB\escURL(EBB\getSiteURl('/public/bootstrap/js/bootstrap.min.js')) ?>"></script>

	</body>

</html>
