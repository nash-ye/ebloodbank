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
use EBloodBank\Models\Donor;

$donorsCriteria = array_merge([
    'blood_group' => 'any',
    'district'    => -1,
    'city_id'     => -1,
], (array) $view->get('donorsCriteria') );

$em = main()->getEntityManager();
$cityRepository = $em->getRepository('Entities:City');
$districtRepository = $em->getRepository('Entities:District');

?>
<form class="form-inline" action="<?= EBB\escURL(EBB\getDonorsURL()) ?>" method="POST">

    <div class="form-group">
        <label>
            <?= EBB\escHTML(__('Name')) ?>
            <input type="text" name="name"  class="form-control" value="<?= EBB\escAttr('') ?>" />
        </label>
    </div>

    <div class="form-group">
        <label>
            <?= EBB\escHTML(__('Blood Group')) ?>
            <select name="blood_group"  class="form-control">
                <option value="all"><?= EBB\escHTML(__('All')) ?></option>
                <?php foreach (Donor::getBloodGroups() as $bloodGroup) : ?>
                <option<?= EBB\toAttributes(['selected' => ($bloodGroup === $donorsCriteria['blood_group'])]) ?>><?= EBB\escHTML($bloodGroup) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <label>
            <?= EBB\escHTML(__('City')) ?>
            <select name="city_id"  class="form-control">
                <?php foreach ($cityRepository->findAll() as $city) : ?>
                <option<?= EBB\toAttributes(['value' => $city->get('id'), 'selected' => ($city->get('id') == $donorsCriteria['city_id'])]) ?>><?php $city->display('name') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <label>
            <?= EBB\escHTML(__('District')) ?>
            <select name="district_id"  class="form-control">
                <option value="all"><?= EBB\escHTML(__('All')) ?></option>
                <?php foreach ($districtRepository->findAll() as $district) : ?>
                <option<?= EBB\toAttributes(['value' => $district->get('id'), 'selected' => ($district->get('id') == $donorsCriteria['district'])]) ?>><?php $district->display('name') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-default"><?= EBB\escHTML(__('Search')) ?></button>
    </div>

</form>
