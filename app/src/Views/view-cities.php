<?php
/**
 * Manage Cities
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Notices;

$header = new View('header', array( 'title' => __('Cities') ));
$header();
?>

	<div class="btn-block">

        <?php if (isCurrentUserCan('manage_cities')) : ?>
		<a href="<?php echo getPageURL('manage-cities') ?>" class="btn btn-primary btn-manage"><?php _e('Manage') ?></a>
        <?php endif; ?>

        <?php if (isCurrentUserCan('add_city')) : ?>
		<a href="<?php echo getPageURL('new-city') ?>" class="btn btn-default btn-add-new"><?php _e('Add New') ?></a>
        <?php endif; ?>

	</div>

    <?php Notices::displayNotices() ?>

	<table id="table-cities" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach (EntityManager::getCityRepository()->findAll() as $city) : ?>

            <tr>
                <td><?php $city->display('city_id') ?></td>
                <td><?php $city->display('city_name') ?></td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

<?php
$footer = new View('footer');
$footer();
