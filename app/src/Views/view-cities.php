<?php
/**
 * View Cities Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Options;

$limit = Options::getOption('entities_per_page');
$pageNumber = max((int) $this->get('page'), 1);
$offset = ($pageNumber - 1) * $limit;

$cityRepository = EntityManager::getCityRepository();
$cities = $cityRepository->findBy(array(), array(), $limit, $offset);

View::display('header', array( 'title' => __('Cities') ));
?>

	<div class="btn-block">
        <?php echo getEditCitiesLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-cities' ))) ?>
        <?php echo getAddCityLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-city' ))) ?>
	</div>

    <?php View::display('notices') ?>

	<table id="table-cities" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($cities as $city) : ?>

            <tr>
                <td><?php $city->display('id') ?></td>
                <td><?php $city->display('name') ?></td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total' => (int) ceil($cityRepository->countAll() / $limit),
            'page_url' => addQueryArgs(getCitiesURL(), array( 'page' => '%#%' )),
            'base_url' => getCitiesURL(),
            'current' => $pageNumber,
        ))

    ?>

<?php
View::display('footer');
