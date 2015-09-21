<?php
/**
 * Database Errors Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
use EBloodBank as EBB;
?>
<!doctype html>
<html lang="<?= EBB\escAttr(p__('language code', 'en')) ?>" dir="<?= EBB\escAttr(p__('text direction', 'ltr')) ?>">
	<head>
		<meta charset="UTF-8">
		<title><?= EBB\escHTML(__('eBloodBank &rsaquo; Database Error')) ?></title>
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

            <header class="page-header">
                <h1><?= EBB\escHTML(__('Error: Database Connection Failed')) ?></h1>
            </header>

            <div class="error-msg error-404-msg">
                <p><?= EBB\escHTML(__('An error occurred while establishing the database connection.')) ?></p>
            </div>

		</div>

        <script src="<?= EBB\escURL(EBB\getSiteURl('/public/jquery/jquery.min.js')) ?>"></script>
		<script src="<?= EBB\escURL(EBB\getSiteURl('/public/bootstrap/js/bootstrap.min.js')) ?>"></script>

	</body>

</html>
