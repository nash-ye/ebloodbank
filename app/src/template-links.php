<?php
/**
 * Template Links Functions
 *
 * @package EBloodBank
 * @since 1.0
 */

use EBloodBank\Options;

/*** Login\Logout\Signup Template Tags ****************************************/

/**
 * @return string
 * @since 1.0
 */
function getLoginURL()
{
    $url = getSiteURL('/login');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getLoginLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Log Out'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (isUserLoggedIn()) {
        return $link;
    }

    $args['atts']['href'] = getLoginURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'login-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getLogoutURL()
{
    $url = getLoginURL();
    $url = addQueryArgs($url, array(
        'action' => 'logout',
    ));
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getLogoutLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Log Out'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isUserLoggedIn()) {
        return $link;
    }

    $args['atts']['href'] = getLogoutURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'logout-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getSignupURL()
{
    $url = getSiteURL('/signup');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getSignupLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Sign Up'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! Options::getOption('self_registration')) {
        return $link;
    }

    $args['atts']['href'] = getSignupURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'signup-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** Users Template Tags ******************************************************/

/**
 * @return string
 * @since 1.0
 */
function getUsersURL()
{
    $url = getSiteURL('/users');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getAddUserURL()
{
    $url = getSiteURL('/add/user');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditUsersURL()
{
    $url = getSiteURL('/edit/users');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditUserURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = getSiteURL("/edit/user/{$id}");
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteUserURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = addQueryArgs(getEditUsersURL(), array(
        'action' => 'delete',
        'id' => $id,
    ));

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getActivateUserURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = addQueryArgs(getEditUsersURL(), array(
        'action' => 'activate',
        'id' => $id,
    ));

    return $url;
}


/**
 * @return string
 * @since 1.0
 */
function getUsersLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Users'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('view_users')) {
        return $link;
    }

    $args['atts']['href'] = getUsersURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'view-link view-users-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getAddUserLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('add_user')) {
        return $link;
    }

    $args['atts']['href'] = getAddUserURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'add-link add-user-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditUsersLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('edit_users')) {
        return $link;
    }

    $args['atts']['href'] = getEditUsersURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-users-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditUserLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('edit_user')) {
        return $link;
    }

    $args['atts']['href'] = getEditUserURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-user-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteUserLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Delete'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('delete_user')) {
        return $link;
    }

    if ($args['id'] === getCurrentUserID()) {
        return $link;
    }

    $args['atts']['href'] = getDeleteUserURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'delete-link delete-user-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getActivateUserLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Activate'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('activate_user')) {
        return $link;
    }

    $em = main()->getEntityManager();
    $user = $em->find('Entities:User', $args['id']);

    if (! $user->isPending()) {
        return $link;
    }

    $args['atts']['href'] = getActivateUserURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'activate-link activate-user-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** Donors Template Tags *****************************************************/

/**
 * @return string
 * @since 1.0
 */
function getDonorsURL()
{
    $url = getSiteURL('/donors');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getAddDonorURL()
{
    $url = getSiteURL('/add/donor');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditDonorsURL()
{
    $url = getSiteURL('/edit/donors');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditDonorURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = getSiteURL("/edit/donor/{$id}");
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteDonorURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = addQueryArgs(getEditDonorsURL(), array(
        'action' => 'delete_donor',
        'id' => $id,
    ));

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getApproveDonorURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = addQueryArgs(getEditDonorsURL(), array(
        'action' => 'approve',
        'id' => $id,
    ));

    return $url;
}


/**
 * @return string
 * @since 1.0
 */
function getDonorsLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Donors'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('view_donors')) {
        return $link;
    }

    $args['atts']['href'] = getDonorsURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'view-link view-donors-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getAddDonorLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('add_donor')) {
        return $link;
    }

    $args['atts']['href'] = getAddDonorURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'add-link add-donor-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditDonorsLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('edit_donors')) {
        return $link;
    }

    $args['atts']['href'] = getEditDonorsURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-donors-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditDonorLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('edit_donor')) {
        return $link;
    }

    $args['atts']['href'] = getEditDonorURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-donor-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteDonorLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Delete'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('delete_donor')) {
        return $link;
    }

    $args['atts']['href'] = getDeleteDonorURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'delete-link delete-donor-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getApproveDonorLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Approve'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('approve_donor')) {
        return $link;
    }

    $em = main()->getEntityManager();
    $donor = $em->find('Entities:Donor', $args['id']);

    if (! $donor->isPending()) {
        return $link;
    }

    $args['atts']['href'] = getApproveDonorURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'approve-link approve-donor-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** Cities Template Tags *****************************************************/

/**
 * @return string
 * @since 1.0
 */
function getCitiesURL()
{
    $url = getSiteURL('/cities');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getAddCityURL()
{
    $url = getSiteURL('/add/city');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditCitiesURL()
{
    $url = getSiteURL('/edit/cities');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditCityURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = getSiteURL("/edit/city/{$id}");
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteCityURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = addQueryArgs(getEditCitiesURL(), array(
        'action' => 'delete',
        'id' => $id,
    ));

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getCitiesLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Cities'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('view_cities')) {
        return $link;
    }

    $args['atts']['href'] = getCitiesURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'view-link view-cities-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getAddCityLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('add_city')) {
        return $link;
    }

    $args['atts']['href'] = getAddCityURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'add-link add-city-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditCitiesLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('edit_cities')) {
        return $link;
    }

    $args['atts']['href'] = getEditCitiesURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-cities-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditCityLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('edit_city')) {
        return $link;
    }

    $args['atts']['href'] = getEditCityURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-city-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteCityLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Delete'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('delete_city')) {
        return $link;
    }

    $args['atts']['href'] = getDeleteCityURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'delete-link delete-city-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** Districts Template Tags ***************************************************/

/**
 * @return string
 * @since 1.0
 */
function getDistrictsURL()
{
    $url = getSiteURL('/districts');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getAddDistrictURL()
{
    $url = getSiteURL('/add/district');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditDistrictsURL()
{
    $url = getSiteURL('/edit/districts');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getEditDistrictURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = getSiteURL("/edit/district/{$id}");
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteDistrictURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = addQueryArgs(getEditDistrictsURL(), array(
        'action' => 'delete',
        'id' => $id,
    ));

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getDistrictsLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Districts'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('view_districts')) {
        return $link;
    }

    $args['atts']['href'] = getDistrictsURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'view-link view-districts-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getAddDistrictLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('add_district')) {
        return $link;
    }

    $args['atts']['href'] = getAddDistrictURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'add-link add-district-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditDistrictsLink(array $args = array())
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
    ), $args);

    if (! isCurrentUserCan('edit_districts')) {
        return $link;
    }

    $args['atts']['href'] = getEditDistrictsURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-districts-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getEditDistrictLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('edit_district')) {
        return $link;
    }

    $args['atts']['href'] = getEditDistrictURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'edit-link edit-district-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getDeleteDistrictLink(array $args)
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Delete'),
        'atts' => array(),
        'before' => '',
        'after' => '',
        'id' => 0,
    ), $args);

    if (! isValidID($args['id'])) {
        return $link;
    }

    if (! isCurrentUserCan('delete_district')) {
        return $link;
    }

    $args['atts']['href'] = getDeleteDistrictURL($args['id']);

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'delete-link delete-district-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** General Template Tags ****************************************************/

/**
 * @return array
 * @since 1.0
 */
function getPagination(array $args)
{
    $output = '';

    $args = array_merge(array(
        'total' => 1,
        'current' => 1,
        'base_url' => '',
        'page_url' => '',
        'before' => '<nav>',
        'after' => '</nav>',
    ), $args);

	$args['total'] = (int) $args['total'];
    $args['total'] = max($args['total'], 1);

	if ($args['total'] > 1) {

        $output .= '<ul class="pagination">';

        $args['current'] = (int) $args['current'];
        $args['current'] = max($args['current'], 1);

        for ($n = 1; $n <= $args['total']; $n++) {
            if ($n === $args['current']) {
                $output .= '<li class="active"><span>' . number_format($n) . '</span></li>';
            } else {
                if (1 === $n) {
                    $url = $args['base_url'];
                } else {
                    $url = str_replace('%#%', $n, urldecode($args['page_url']));
                }
                $output .= '<li><a href="' . escURL($url) . '">' . number_format($n) . '</a></li>';
            }
        }

        $output .= '</ul>';

	}

    if (! empty($output)) {
        $output = $args['before'] . $output . $args['after'];
    }

    return $output;
}
