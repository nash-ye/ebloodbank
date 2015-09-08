<?php
/**
 * Template Lists Functions
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

/**
 * @return void
 * @since 1.0
 */
function getUsersDropdown(array $args)
{
    $output = '';
    $args = array_merge(array(
        'id'        => '',
        'name'      => '',
        'atts'      => array(),
        'users'     => 'all',
        'selected'  => array(),
        'show_none' => true,
    ), $args);

    if ('all' === $args['users']) {
        $em = main()->getEntityManager();
        $userRepository = $em->getRepository('Entities:User');
        $args['users'] = (array) $userRepository->findAll();
    }

	$args['atts'] = array_merge((array) $args['atts'], array(
		'name' => $args['name'],
		'id'   => $args['id'],
	) );

    $output .= sprintf('<select%s>', toAttributes($args['atts']));

    foreach ($args['users'] as $user) {

        $userID = (int) $user->get('id');

        $output .= sprintf(
                '<option%s>%s</option>',
                toAttributes(array(
                    'value' => $userID,
                    'selected' => in_array($userID, (array) $args['selected']),
                )),
                escHTML($user->get('name'))
            );

    }

    $output .= '</select>';

    return $output;
}

/**
 * @return void
 * @since 1.0
 */
function getCitiesDropdown(array $args)
{
    $output = '';
    $args = array_merge(array(
        'id'        => '',
        'name'      => '',
        'atts'      => array(),
        'cities'    => 'all',
        'selected'  => array(),
        'show_none' => true,
    ), $args);

    if ('all' === $args['cities']) {
        $em = main()->getEntityManager();
        $cityRepository = $em->getRepository('Entities:City');
        $args['cities'] = (array) $cityRepository->findAll();
    }

	$args['atts'] = array_merge((array) $args['atts'], array(
		'name' => $args['name'],
		'id'   => $args['id'],
	) );

    $output .= sprintf('<select%s>', toAttributes($args['atts']));

    foreach ($args['users'] as $city) {

        $cityID = (int) $city->get('id');

        $output .= sprintf(
                '<option%s>%s</option>',
                toAttributes(array(
                    'value' => $cityID,
                    'selected' => in_array($cityID, (array) $args['selected']),
                )),
                escHTML($city->get('name'))
            );

    }

    $output .= '</select>';

    return $output;
}
