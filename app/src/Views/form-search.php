<?php
/**
 * Search Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Options;

$donorsCriteria = array_merge( array(
    'blood_group' => 'any',
    'district'    => -1,
    'city_id'     => -1,
), (array) $this->get('donorsCriteria') );

$em = main()->getEntityManager();
$cityRepository = $em->getRepository('Entities:City');
$districtRepository = $em->getRepository('Entities:District');

?>
<form class="form-inline" action="<?php echo escURL(getDonorsURL()) ?>" method="POST">

    <div class="form-group">
        <label>
            <?php _e('Name') ?>
            <input type="text" name="name"  class="form-control" value="<?php echo escAttr('') ?>" />
        </label>
    </div>

    <div class="form-group">
        <label>
            <?php _e('Blood Group') ?>
            <select name="blood_group"  class="form-control">
                <option value="all"><?php _e('All') ?></option>
                <?php foreach (Options::getOption('blood_groups') as $blood_group) : ?>
                <option<?php echo toAttributes(array( 'selected' => ($blood_group === $donorsCriteria['blood_group']) )) ?>><?php echo escHTML($blood_group) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <label>
            <?php _e('City') ?>
            <select name="city_id"  class="form-control">
                <?php foreach ($cityRepository->findAll() as $city) : ?>
                <option<?php echo toAttributes(array( 'value' => $city->get('id'), 'selected' => ($city->get('id') == $donorsCriteria['city_id']) )) ?>><?php $city->display('name') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <label>
            <?php _e('District') ?>
            <select name="district_id"  class="form-control">
                <option value="all"><?php _e('All') ?></option>
                <?php foreach ($districtRepository->findAll() as $district) : ?>
                <option<?php echo toAttributes(array( 'value' => $district->get('id'), 'selected' => ($district->get('id') == $donorsCriteria['district']) )) ?>><?php $district->display('name') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-default"><?php _e('Search') ?></button>
    </div>

</form>
