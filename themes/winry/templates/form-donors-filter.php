<?php
/**
 * Filter donors form template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$criteria = array_merge([
    'city'                     => -1,
    'district'                 => -1,
    'blood_group'              => 'any',
    'blood_group_alternatives' => false,
], (array) $view->get('criteria'));

$cityRepository = $view->get('cityRepository');
$districtRepository = $view->get('districtRepository');

?>
<script>
    jQuery(document).ready(function($) {

        var filterForm = $('#form-donors-filter');
        var citySelect = $('select[name="city_id"]', filterForm);
        var districtSelect = $('select[name="district_id"]', filterForm);
        var districtOptions = $('option', districtSelect).toArray();

        populateDistrictSelect(districtSelect, districtOptions, citySelect.val(), districtSelect.val());

        citySelect.change(function() {
            populateDistrictSelect(districtSelect, districtOptions, citySelect.val(), "-1");
        });

    });
    function populateDistrictSelect(districtSelect, districtOptions, selectedCity, selectedDistrict) {
        if (selectedCity !== null) {
            // Remove any existing district options.
            districtSelect.empty();
            if (selectedCity !== "-1") {
                var isSelectedDistrictAdded = false;
                // Loop through the district options array.
                $.each(districtOptions, function() {
                    var districtOption = $(this);
                    var districtOptionValue = districtOption.val();
                    if (districtOptionValue === "-1") {
                        districtSelect.prepend(districtOption);
                    } else if (districtOption.attr('data-city-id') === selectedCity) {
                        districtSelect.append(districtOption);
                    }
                    if (! isSelectedDistrictAdded && selectedDistrict === districtOptionValue) {
                        isSelectedDistrictAdded = true;
                    }
                });
                if (isSelectedDistrictAdded || selectedDistrict === null) {
                    districtSelect.val(selectedDistrict);
                }
            }
        }
    }
</script>
<form id="form-donors-filter" class="form-inline" method="POST">

    <div class="form-group">
        <label>
            <?= EBB\escHTML(__('Blood Group')) ?>
            <?=
                EBB\getBloodGroupsDropdown([
                    'name'              => 'blood_group',
                    'selected'          => $criteria['blood_group'],
                    'atts'              => ['class' => 'form-control'],
                    'show_placeholder'  => true,
                    'placeholder_value' => 'any',
                    'placeholder_text'  => __('All'),
                ])
            ?>
        </label>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="blood_group_alternatives" id="blood_group_alternatives" <?= EBB\toAttributes(['checked' => $criteria['blood_group_alternatives']]) ?> />
                <?= EBB\escHTML(__('Include alternatives?')) ?>
            </label>
        </div>
    </div>

    <div class="form-group">
        <label>
            <?= EBB\escHTML(__('City')) ?>
            <?=
                EBB\getCitiesDropdown([
                    'name'              => 'city_id',
                    'selected'          => $criteria['city'],
                    'atts'              => ['class' => 'form-control'],
                    'show_placeholder'  => true,
                    'placeholder_value' => '-1',
                    'placeholder_text'  => __('All'),
                ])
            ?>
        </label>
    </div>

    <div class="form-group">
        <label>
            <?= EBB\escHTML(__('District')) ?>
            <select name="district_id"  class="form-control">
                <option value="-1"><?= EBB\escHTML(__('All')) ?></option>
                <?php foreach ($districtRepository->findAll() as $district) : ?>
                <option<?= EBB\toAttributes(['value' => $district->get('id'), 'data-city-id' => $district->get('city')->get('id'), 'selected' => ($district->get('id') == $criteria['district'])]) ?>><?php $district->display('name') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-default"><?= EBB\escHTML(__('Search')) ?></button>
    </div>

</form>
