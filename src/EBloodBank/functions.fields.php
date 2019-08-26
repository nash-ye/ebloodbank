<?php
/**
 * Template dropdowns helpers file
 *
 * @package EBloodBank
 * @since   1.0.1
 */
namespace EBloodBank;

use EBloodBank\Models\User;
use EBloodBank\Models\City;
use EBloodBank\Models\District;

/**
 * @return string
 * @since 1.0.1
 */
function getTokenField(array $args)
{
    $output = '';
    $args = array_merge([
        'id'    => '',
        'name'  => '',
    ], $args);

    $session = Main::getInstance()->getSession();
    $sessionToken = $session->getCsrfToken();
    $sessionTokenValue = $sessionToken->getValue();

    if (is_null($sessionTokenValue)) {
        return $output;
    }

    $atts = [
        'type'  => 'hidden',
        'id'    => $args['id'],
        'name'  => $args['name'],
        'value' => $sessionTokenValue,
    ];

    $output = sprintf('<input%s/>', toAttributes($atts));
    return $output;
}

/**
 * @return string
 * @since 1.0
 */
function getUsersDropdown(array $args)
{
    $output = '';
    $args = array_merge([
        'id'                => '',
        'name'              => '',
        'atts'              => [],
        'users'             => 'all',
        'selected'          => [],
        'show_placeholder'  => false,
        'placeholder_value' => '',
        'placeholder_text'  => '',
    ], $args);

    $args['selected'] = (array) $args['selected'];

    array_walk($args['selected'], function ($value) {
        if ($value instanceof User) {
            return $value->get('id');
        }
    });

    $args['atts'] = array_merge((array) $args['atts'], [
        'name' => $args['name'],
        'id'   => $args['id'],
    ]);

    if (is_string($args['users']) && 'all' === $args['users']) {
        $em = Main::getInstance()->getEntityManager();
        $userRepository = $em->getRepository('Entities:User');
        $args['users'] = (array) $userRepository->findAll();
    }

    $output .= sprintf('<select%s>', toAttributes($args['atts']));

    if ($args['show_placeholder']) {
        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes(['value' => $args['placeholder_value']]),
            escHTML($args['placeholder_text'])
        );
    }

    foreach ($args['users'] as $user) {
        $userID = (int) $user->get('id');

        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes([
                'value'    => $userID,
                'selected' => in_array($userID, $args['selected']),
            ]),
            escHTML($user->get('name'))
        );
    }

    $output .= '</select>';

    return $output;
}

/**
 * @return string
 * @since 1.0
 */
function getCitiesDropdown(array $args)
{
    $output = '';
    $args = array_merge([
        'id'                => '',
        'name'              => '',
        'atts'              => [],
        'cities'            => 'all',
        'selected'          => [],
        'show_placeholder'  => false,
        'placeholder_value' => '',
        'placeholder_text'  => '',
    ], $args);

    $args['selected'] = (array) $args['selected'];

    array_walk($args['selected'], function ($value) {
        if ($value instanceof City) {
            return $value->get('id');
        }
    });

    $args['atts'] = array_merge((array) $args['atts'], [
        'name' => $args['name'],
        'id'   => $args['id'],
    ]);

    if (is_string($args['cities']) && 'all' === $args['cities']) {
        $em = Main::getInstance()->getEntityManager();
        $cityRepository = $em->getRepository('Entities:City');
        $args['cities'] = (array) $cityRepository->findAll();
    }

    $output .= sprintf('<select%s>', toAttributes($args['atts']));

    if ($args['show_placeholder']) {
        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes(['value' => $args['placeholder_value']]),
            escHTML($args['placeholder_text'])
        );
    }

    foreach ($args['cities'] as $city) {
        $cityID = (int) $city->get('id');

        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes([
                'value'    => $cityID,
                'selected' => in_array($cityID, $args['selected']),
            ]),
            escHTML($city->get('name'))
        );
    }

    $output .= '</select>';

    return $output;
}

/**
 * @return void
 * @since 1.2
 */
function getDistrictsDropdown(array $args)
{
    $output = '';
    $args = array_merge([
        'id'                => '',
        'name'              => '',
        'atts'              => [],
        'selected'          => [],
        'districts'         => 'all',
        'group_by_cities'   => true,
        'show_placeholder'  => false,
        'placeholder_value' => '',
        'placeholder_text'  => '',
    ], $args);

    $args['selected'] = (array) $args['selected'];

    array_walk($args['selected'], function ($value) {
        if ($value instanceof District) {
            return $value->get('id');
        }
    });

    $args['atts'] = array_merge((array) $args['atts'], [
        'name' => $args['name'],
        'id'   => $args['id'],
    ]);

    if (is_string($args['districts']) && 'all' === $args['districts']) {
        $em = Main::getInstance()->getEntityManager();
        $districtRepository = $em->getRepository('Entities:District');
        $args['districts'] = (array) $districtRepository->findAll();
    }

    $output .= sprintf('<select%s>', toAttributes($args['atts']));

    if ($args['show_placeholder']) {
        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes(['value' => $args['placeholder_value']]),
            escHTML($args['placeholder_text'])
        );
    }

    if (! $args['group_by_cities']) {
        foreach ($args['districts'] as $district) {
            $districtID = (int) $district->get('id');
            $output .= sprintf(
                '<option%s>%s</option>',
                toAttributes([
                    'value'        => $districtID,
                    'data-city-id' => $district->get('city')->get('id'),
                    'selected'     => in_array($districtID, $args['selected']),
                ]),
                escHTML($district->get('name'))
            );
        }
    } else {
        $groups = [];
        foreach ($args['districts'] as $district) {
            $city = $district->get('city');
            $cityID = (int) $city->get('id');
            if (! isset($groups[$cityID])) {
                $groups[$cityID] = [
                    'atts'    => [
                        'label' => $city->get('name')
                    ],
                    'options' => []
                ];
            }
            $districtID = (int) $district->get('id');
            $groups[$cityID]['options'][$districtID] = [
                'atts'  => [
                    'value'        => $districtID,
                    'data-city-id' => $district->get('city')->get('id'),
                    'selected'     => in_array($districtID, $args['selected']),
                ],
                'text'  => $district->get('name'),
            ];
        }
        if (! empty($groups)) {
            foreach ($groups as $group) {
                $output .= sprintf('<optgroup%s>', toAttributes($group['atts']));
                if (! empty($group['options']) && is_array($group['options'])) {
                    foreach ($group['options'] as $option) {
                        $output .= sprintf(
                            '<option%s>%s</option>',
                            toAttributes($option['atts']),
                            escHTML($option['text'])
                        );
                    }
                }
                $output .= '</optgroup>';
            }
        }
    }

    $output .= '</select>';

    return $output;
}

/**
 * @return array
 * @since 1.2.2
 */
function getGenders()
{
    return [
        'male'   => __('Male'),
        'female' => __('Female'),
    ];
}

/**
 * @return array
 * @since 1.2.2
 */
function getVisibilities()
{
    return [
        'everyone' => __('Everyone'),
        'members'  => __('Members'),
        'staff'    => __('Staff'),
    ];
}

/**
 * @return void
 * @since 1.2.2
 */
function getVisibilitiesDropdown(array $args)
{
    $output = '';
    $args = array_merge([
        'id'                => '',
        'name'              => '',
        'atts'              => [],
        'selected'          => [],
        'show_placeholder'  => false,
        'placeholder_value' => '',
        'placeholder_text'  => '',
    ], $args);

    $visibilities = getVisibilities();

    $args['selected'] = (array) $args['selected'];

    $args['atts'] = array_merge((array) $args['atts'], [
        'name' => $args['name'],
        'id'   => $args['id'],
    ]);

    $output .= sprintf('<select%s>', toAttributes($args['atts']));

    if ($args['show_placeholder']) {
        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes(['value' => $args['placeholder_value']]),
            escHTML($args['placeholder_text'])
        );
    }

    foreach ($visibilities as $key => $label) {
        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes([
                'selected' => in_array($key, $args['selected']),
            ]),
            escHTML($label)
        );
    }

    $output .= '</select>';

    return $output;
}

/**
 * @return array
 * @since 1.2.2
 */
function getBloodGroups()
{
    return [
        'A+',
        'A-',
        'B+',
        'B-',
        'O+',
        'O-',
        'AB+',
        'AB-',
    ];
}

/**
 * @return void
 * @since 1.2
 */
function getBloodGroupsDropdown(array $args)
{
    $output = '';
    $args = array_merge([
        'id'                => '',
        'name'              => '',
        'atts'              => [],
        'selected'          => [],
        'show_placeholder'  => false,
        'placeholder_value' => '',
        'placeholder_text'  => '',
    ], $args);

    $bloodGroups = getBloodGroups();

    $args['selected'] = (array) $args['selected'];

    $args['atts'] = array_merge((array) $args['atts'], [
        'name' => $args['name'],
        'id'   => $args['id'],
    ]);

    $output .= sprintf('<select%s>', toAttributes($args['atts']));

    if ($args['show_placeholder']) {
        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes(['value' => $args['placeholder_value']]),
            escHTML($args['placeholder_text'])
        );
    }

    foreach ($bloodGroups as $bloodGroup) {
        $output .= sprintf(
            '<option%s>%s</option>',
            toAttributes([
                'selected' => in_array($bloodGroup, $args['selected']),
            ]),
            escHTML($bloodGroup)
        );
    }

    $output .= '</select>';

    return $output;
}
