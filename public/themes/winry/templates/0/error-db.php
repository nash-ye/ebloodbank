<?php
/**
 * Database error page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

?>
<!doctype html>
<html lang="<?= EBB\escAttr(dp__('winry', 'language code', 'en')) ?>" dir="<?= EBB\escAttr(dp__('winry', 'text direction', 'ltr')) ?>">
	<head>
		<meta charset="UTF-8">
		<title><?= EBB\escHTML(d__('winry', 'eBloodBank &rsaquo; Database Error')) ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
        <?php if ('rtl' === dp__('winry', 'text direction', 'ltr')) : ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-rtl@3.3.4/dist/css/bootstrap-rtl.min.css">
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

            <header class="page-header">
                <h1><?= EBB\escHTML(d__('winry', 'Error: Database Connection Failed')) ?></h1>
            </header>

            <div class="error-msg error-404-msg">
                <p><?= EBB\escHTML(d__('winry', 'An error occurred while establishing the database connection.')) ?></p>
            </div>

		</div>

        <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
	</body>
</html>
