<?php
/**
 * Log-in form template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

?>

<?php $view->displayView('notices') ?>

<form id="form-login" class="form-horizontal" action="<?= EBB\escURL(EBB\getLoginURL()) ?>" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_email"><?= EBB\escHTML(d__('winry', 'E-mail')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="email" name="user_email" id="user_email" class="form-control" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_pass"><?= EBB\escHTML(d__('winry', 'Password')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="password" name="user_pass" id="user_pass" class="form-control" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?= EBB\escHTML(d__('winry', 'Log In')) ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="login" />

</form>
