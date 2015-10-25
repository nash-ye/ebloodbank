<?php
/**
 * Template badges helpers file
 *
 * @package EBloodBank
 * @since   1.1
 */
namespace EBloodBank;

/*** Users Template Tags ******************************************************/

/**
 * @return string
 * @since 1.1
 */
function getPendingUsersCountBadge(array $args = [])
{
    $badge = '';

    $args = array_merge(array(
        'atts' => [],
        'before' => ' ',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('activate_user')) {
        return $badge;
    }

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'badge pending-users-badge';
    }

    $usersRepository = main()->getEntityManager()->getRepository('Entities:User');
    $usersCount = $usersRepository->countBy(['status' => 'pending']);

    if ($usersCount <= 0) {
        return $badge;
    }

    $badge = '<span' . toAttributes($args['atts']) . '>' . escHTML(number_format($usersCount)) . '</span>';
    return $args['before'] . $badge . $args['after'];
}

/*** Donors Template Tags *****************************************************/

/**
 * @return string
 * @since 1.1
 */
function getPendingDonorsCountBadge(array $args = [])
{
    $badge = '';

    $args = array_merge(array(
        'atts' => [],
        'before' => ' ',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('approve_donor')) {
        return $badge;
    }

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'badge pending-donors-badge';
    }

    $donorsRepository = main()->getEntityManager()->getRepository('Entities:Donor');
    $donorsCount = $donorsRepository->countBy(['status' => 'pending']);

    if ($donorsCount <= 0) {
        return $badge;
    }

    $badge = '<span' . toAttributes($args['atts']) . '>' . escHTML(number_format($donorsCount)) . '</span>';
    return $args['before'] . $badge . $args['after'];
}
