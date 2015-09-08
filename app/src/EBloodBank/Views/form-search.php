<?php
/**
 * Search Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Models\Donor;

$donorsCriteria = array_merge( array(
    'blood_group' => 'any',
    'district'    => -1,
    'city_id'     => -1,
), (array) $this->get('donorsCriteria') );

$em = main()->getEntityManager();
$cityRepository = $em->getRepository('Entities:City');
$districtRepository = $em->getRepository('Entities:District');

?>
<form class="form-inline" action="<?php echo EBB\escURL(EBB\getDonorsURL()) ?>" method="POST">

    <div class="form-group">
        <label>
            <?php __e('Name') ?>
            <input type="text" name="name"  class="form-control" value="<?php echo EBB\escAttr('') ?>" />
        </label>
    </div>

    <div class="form-group">
        <label>
            <?php __e('Blood Group') ?>
            <select name="blood_group"  class="form-control">
                <option value="all"><?php __e('All') ?></option>
                <?php foreach (Donor::getBloodGroups() as $bloodGroup) : ?>
                <option<?php echo EBB\toAttributes(array( 'selected' => ($bloodGroup === $donorsCriteria['blood_group']) )) ?>><?php echo EBB\escHTML($bloodGroup) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <label>
            <?php __e('City') ?>
            <select name="city_id"  class="form-control">
                <?php foreach ($cityRepository->findAll() as $city) : ?>
                <option<?php echo EBB\toAttributes(array( 'value' => $city->get('id'), 'selected' => ($city->get('id') == $donorsCriteria['city_id']) )) ?>><?php $city->display('name') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <label>
            <?php __e('District') ?>
            <select name="district_id"  class="form-control">
                <option value="all"><?php __e('All') ?></option>
                <?php foreach ($districtRepository->findAll() as $district) : ?>
                <option<?php echo EBB\toAttributes(array( 'value' => $district->get('id'), 'selected' => ($district->get('id') == $donorsCriteria['district']) )) ?>><?php $district->display('name') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-default"><?php __e('Search') ?></button>
    </div>

</form>
