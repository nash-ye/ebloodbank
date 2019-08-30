<?php
/**
 * Template badges helpers file
 *
 * @package EBloodBank
 * @since   1.1
 */
namespace EBloodBank;

use EBloodBank\Views\View;

/*** Users Template Tags ******************************************************/

/**
 * @return string
 * @since 1.1
 */
function getPendingUsersCountBadge(array $args = [], View $context = null)
{
    $badge = '';

    $args = array_merge([
        'atts' => [],
        'before' => ' ',
        'after' => '',
    ], $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $context->getAcl()->isUserAllowed($currentUser, 'User', 'activate')) {
        return $badge;
    }

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'badge pending-users-badge';
    }

    $usersRepository = Main::getInstance()->getEntityManager()->getRepository('Entities:User');
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
function getPendingDonorsCountBadge(array $args = [], View $context = null)
{
    $badge = '';

    $args = array_merge([
        'atts'   => [],
        'before' => ' ',
        'after'  => '',
    ], $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $context->getAcl()->isUserAllowed($currentUser, 'Donor', 'approve')) {
        return $badge;
    }

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'badge pending-donors-badge';
    }

    $donorsRepository = Main::getInstance()->getEntityManager()->getRepository('Entities:Donor');
    $donorsCount = $donorsRepository->countBy(['status' => 'pending']);

    if ($donorsCount <= 0) {
        return $badge;
    }

    $badge = '<span' . toAttributes($args['atts']) . '>' . escHTML(number_format($donorsCount)) . '</span>';
    return $args['before'] . $badge . $args['after'];
}
