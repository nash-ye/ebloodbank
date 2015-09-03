<?php
/**
 * Log-in Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;
?>

<?php View::display('notices') ?>

<form id="form-login" class="form-horizontal" action="<?php echo escURL(getLoginURL()) ?>" method="POST">

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
			<input type="password" name="user_pass" id="user_pass" class="form-control" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?php _e('Log In') ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="login" />

</form>