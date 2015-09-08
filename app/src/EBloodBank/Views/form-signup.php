<?php
/**
 * Sign-up Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
?>

<?php View::display('notices') ?>

<form id="form-signup" class="form-horizontal" method="POST">

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

	<input type="hidden" name="action" value="signup" />

</form>
