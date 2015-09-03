<?php
/**
 * Install Page
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
		<title>eBloodBank</title>
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
                    <a class="navbar-brand" href="<?php echo escURL(getHomeURL()) ?>">
                        <?php _e('eBloodBank') ?>
					</a>
				</div>
			</div>
		</nav>

		<!-- Page Content -->
		<div class="container">

            <?php if (1 === $step) : ?>

                <header>
                    <h1><?php _e('Install > Step 1') ?></h1>
                </header>

                <p><?php _e('Welcome to eBloodBank, Before getting started, we need some information on the database. You will need to know the following items before proceeding.') ?></p>

                <ol>
                    <li><?php _e('Database name') ?></li>
                    <li><?php _e('Database username') ?></li>
                    <li><?php _e('Database password') ?></li>
                    <li><?php _e('Database host') ?></li>
                </ol>

                <p><?php _e('We’re going to use this information to setup the database. You can simply open <code>app/config.php</code> in a text editor, fill in your information, and save it.') ?></p>

                <form id="form-install" class="form-horizontal" method="POST">

                    <div class="form-group">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary"><?php _e('Install') ?></button>
                        </div>
                    </div>

                </form>

            <?php elseif (2 === $step) : ?>

                <header>
                    <h1><?php _e('Install > Step 2') ?></h1>
                </header>

                <form id="form-install" class="form-horizontal" method="POST">

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label for="user_name"><?php _e('Name') ?></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="user_name" id="user_name" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label for="user_email"><?php _e('E-mail') ?></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="email" name="user_email" id="user_email" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label for="user_pass"><?php _e('Password') ?></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="password" name="user_pass_1" id="user_pass_1" class="form-control" value="" placeholder="<?php echo escAttr(__('Type your password')) ?>" autocomplete="off" />
                            &nbsp;
                            <input type="password" name="user_pass_2" id="user_pass_2" class="form-control" value="" placeholder="<?php echo escAttr(__('Type your password again')) ?>" autocomplete="off" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary"><?php _e('Sign Up') ?></button>
                        </div>
                    </div>

                </form>

            <?php elseif (3 === $step) : ?>

                <header>
                    <h1><?php _e('Success!') ?></h1>
                </header>

                <p><?php _e('eBloodBank has been installed.') ?></p>

                <div class="btn-group btn-group-lg" role="group">
                    <a href="<?php echo escURL(getHomeURL()) ?>" class="btn btn-default"><?php _e('Home') ?></a>
                    <a href="<?php echo escURL(getLoginURL()) ?>" class="btn btn-primary"><?php _e('Log In') ?></a>
                </div>

            <?php endif; ?>

		</div>

        <script src="<?php echo escURL(getSiteURl('/public/jquery/jquery.min.js')) ?>"></script>
		<script src="<?php echo escURL(getSiteURl('/public/bootstrap/js/bootstrap.min.js')) ?>"></script>

	</body>

</html>
