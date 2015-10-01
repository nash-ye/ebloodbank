<?php
/**
 * New\Edit user form template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;
use EBloodBank\Roles;
use EBloodBank\Models\User;

if (! $view->isExists('user')) {
    $user = new User();
}
?>

<?php $view->displayView('notices') ?>

<form id="form-user" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_name"><?= EBB\escHTML(__('Name')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="user_name" id="user_name" class="form-control" value="<?php $user->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_email"><?= EBB\escHTML(__('E-mail')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="email" name="user_email" id="user_email" class="form-control" value="<?php $user->display('email', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
            <?php if ($user->isExists()) : ?>
            <label for="user_pass"><?= EBB\escHTML(__('New Password')) ?></label>
            <?php else : ?>
            <label for="user_pass"><?= EBB\escHTML(__('Password')) ?> <span class="form-required">*</span></label>
            <?php endif; ?>
		</div>
		<div class="col-sm-4">
			<input type="password" name="user_pass_1" id="user_pass_1" class="form-control" value="" placeholder="<?= EBB\escAttr(__('Type the password')) ?>" autocomplete="off" />
			&nbsp;
			<input type="password" name="user_pass_2" id="user_pass_2" class="form-control" value="" placeholder="<?= EBB\escAttr(__('Type the password again')) ?>" autocomplete="off" />
		</div>
	</div>

    <?php if ($user->get('id') != EBB\getCurrentUserID()) : ?>
	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_role"><?= EBB\escHTML(__('Role')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
            <select name="user_role" id="user_role" class="form-control" required>
				<?php foreach (Roles::getRoles() as $role) : ?>
                <option<?= EBB\toAttributes(['value' => $role->getSlug(), 'selected' => ($role->getSlug() === $user->get('role'))]) ?>><?= EBB\escHTML($role->getTitle()) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
    <?php endif; ?>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?= EBB\escHTML(__('Submit')) ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_user" />
    <?= EBB\getTokenField(['name' => 'token']) ?>

</form>
