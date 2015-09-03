<?php
/**
 * New\Edit User Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Models\User;
use EBloodBank\Roles;

if (! $this->isExists('user')) {
    $user = new User();
}
?>

<?php View::display('notices') ?>

<form id="form-user" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_name"><?php _e('Name') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="user_name" id="user_name" class="form-control" value="<?php $user->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_email"><?php _e('E-mail') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="email" name="user_email" id="user_email" class="form-control" value="<?php $user->display('email', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
            <label for="user_pass"><?php $user->isExists() ? _e('New Password') : _e('Password') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="password" name="user_pass_1" id="user_pass_1" class="form-control" value="" placeholder="<?php echo escAttr(__('Type the password')) ?>" autocomplete="off" />
			&nbsp;
			<input type="password" name="user_pass_2" id="user_pass_2" class="form-control" value="" placeholder="<?php echo escAttr(__('Type the password again')) ?>" autocomplete="off" />
		</div>
	</div>

    <?php if ($user->get('id') != getCurrentUserID()) : ?>
	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_role"><?php _e('Role') ?></label>
		</div>
		<div class="col-sm-4">
            <select name="user_role" id="user_role" class="form-control">
				<?php foreach (Roles::getRoles() as $role) : ?>
				<option<?php echo toAttributes(array( 'value' => $role->slug, 'selected' => ($role->slug === $user->get('role')) )) ?>><?php echo $role->title ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
    <?php endif; ?>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?php _e('Submit') ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_user" />

</form>
