<?php
/**
 * View Districts Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;

$header = new View('header', array( 'title' => __('Districts') ));
$header();
?>

	<div class="btn-block">

        <?php if (isCurrentUserCan('manage_districts')) : ?>
		<a href="<?php echo getPageURL('manage-districts') ?>" class="btn btn-primary btn-manage"><?php _e('Manage') ?></a>
        <?php endif; ?>

        <?php if (isCurrentUserCan('add_district')) : ?>
		<a href="<?php echo getPageURL('new-district') ?>" class="btn btn-default btn-add-new"><?php _e('Add New') ?></a>
        <?php endif; ?>

	</div>

    <?php Notices::displayNotices() ?>

	<table id="table-distrs" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('City') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach (EntityManager::getDistrictRepository()->findAll() as $distr) : ?>

            <tr>
                <td><?php $distr->display('id') ?></td>
                <td><?php $distr->display('name') ?></td>
                <td>
                    <?php $distr->get('city')->display('name') ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

<?php
$footer = new View('footer');
$footer();
