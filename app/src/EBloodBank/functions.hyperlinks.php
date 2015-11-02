<?php
/**
 * Template hyperlinks helpers file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

use EBloodBank\Models\User;
use EBloodBank\Models\Donor;
use EBloodBank\Models\City;
use EBloodBank\Models\District;

/*** Home Template Tags *******************************************************/

/**
 * @return string
 * @since 1.0
 */
function getHomeURL($format = 'absolute')
{
    $url = '';

    if (empty($format)) {
        $format = 'absolute';
    }

    switch (strtolower($format)) {

        case 'absolute':
            if (defined('EBB_URL')) {
                $url = EBB_URL;
            } else {
                $url = Options::getOption('site_url');
            }
            if (empty($url)) {
                $url = guessHomeURL(); // A fallback URl.
            }
            break;

        case 'relative':
            $url = (string) parse_url(getHomeURL(), PHP_URL_PATH);
            break;

    }

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getSiteURL($path = '', $format = 'absolute')
{
    $url = getHomeURL($format);

    if (! empty($path)) {
        $url = trimTrailingSlash($url) . '/' . ltrim($path, '/');
    }

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getHomeLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Home'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $args['atts']['href'] = getHomeURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'home-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** Installer Template Tags **************************************************/

/**
 * @return string
 * @since 1.0
 */
function getInstallerURL()
{
    $url = getSiteURL('/install.php');
    return $url;
}

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
function getLoginLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Log In'),
        'atts' => [],
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
function getLogoutLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Log Out'),
        'atts' => [],
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
function getSignupLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Sign Up'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    if ('on' !== Options::getOption('self_registration')) {
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

    $url = getSiteURL("/delete/user/{$id}");
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

    $url = getSiteURL("/activate/user/{$id}");
    return $url;
}


/**
 * @return string
 * @since 1.0
 */
function getUsersLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Users'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canViewUsers()) {
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
function getAddUserLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canAddUser()) {
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
function getEditUsersLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditUsers()) {
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
        'fallbackContent' => false,
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
        'user' => null,
    ), $args);

    if (! $args['user'] instanceof User) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditUser($args['user'])) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $args['atts']['href'] = getEditUserURL($args['user']->get('id'));

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
        'atts' => [],
        'before' => '',
        'after' => '',
        'user' => null,
    ), $args);

    if (! $args['user'] instanceof User) {
        return $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canDeleteUser($args['user'])) {
        return $link;
    }

    $args['atts']['href'] = getDeleteUserURL($args['user']->get('id'));

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
        'atts' => [],
        'before' => '',
        'after' => '',
        'user' => null,
    ), $args);

    if (! $args['user'] instanceof User || ! $args['user']->isPending()) {
        return $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canActivateUser($args['user'])) {
        return $link;
    }

    $args['atts']['href'] = getActivateUserURL($args['user']->get('id'));

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
 * @since 1.1
 */
function getDonorURL($id)
{
    $url = '';
    $id = (int) $id;

    if (! isValidID($id)) {
        return $url;
    }

    $url = getSiteURL("/donor/{$id}");
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

    $url = getSiteURL("/delete/donor/{$id}");
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

    $url = getSiteURL("/approve/donor/{$id}");
    return $url;
}


/**
 * @return string
 * @since 1.0
 */
function getDonorsLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Donors'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if ('on' !== Options::getOption('site_publication') && (! $currentUser || ! $currentUser->canViewDonors())) {
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
function getDonorLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => '',
        'atts' => [],
        'before' => '',
        'after' => '',
        'donor' => null,
    ), $args);

    if (! $args['donor'] instanceof Donor) {
        return $link;
    }

    $currentUser = getCurrentUser();

    if ('on' !== Options::getOption('site_publication') && (! $currentUser || ! $currentUser->canViewDonor($args['donor']))) {
        return $link;
    }

    if (empty($args['content'])) {
        $args['content'] = escHTML($args['donor']->get('name'));
    }

    $args['atts']['href'] = getDonorURL($args['donor']->get('id'));

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'view-link view-donor-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/**
 * @return string
 * @since 1.0
 */
function getAddDonorLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canAddDonor()) {
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
function getEditDonorsLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditDonors()) {
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
        'fallbackContent' => false,
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
        'donor' => null,
    ), $args);

    if (! $args['donor'] instanceof Donor) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditDonor($args['donor'])) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $args['atts']['href'] = getEditDonorURL($args['donor']->get('id'));

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
        'atts' => [],
        'before' => '',
        'after' => '',
        'donor' => null,
    ), $args);

    if (! $args['donor'] instanceof Donor) {
        return $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canDeleteDonor($args['donor'])) {
        return $link;
    }

    $args['atts']['href'] = getDeleteDonorURL($args['donor']->get('id'));

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
        'atts' => [],
        'before' => '',
        'after' => '',
        'donor' => null,
    ), $args);

    if (! $args['donor'] instanceof Donor || ! $args['donor']->isPending()) {
        return $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canApproveDonor($args['donor'])) {
        return $link;
    }

    $args['atts']['href'] = getApproveDonorURL($args['donor']->get('id'));

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

    $url = getSiteURL("/delete/city/{$id}");
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getCitiesLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Cities'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if ('on' !== Options::getOption('site_publication') && (! $currentUser || ! $currentUser->canViewCities())) {
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
function getAddCityLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canAddCity()) {
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
function getEditCitiesLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditCities()) {
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
        'fallbackContent' => false,
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
        'city' => null,
    ), $args);

    if (! $args['city'] instanceof City) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditCity($args['city'])) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $args['atts']['href'] = getEditCityURL($args['city']->get('id'));

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
        'atts' => [],
        'before' => '',
        'after' => '',
        'city' => null,
    ), $args);

    if (! $args['city'] instanceof City) {
        return $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canDeleteCity($args['city'])) {
        return $link;
    }

    $args['atts']['href'] = getDeleteCityURL($args['city']->get('id'));

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

    $url = getSiteURL("/delete/district/{$id}");
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getDistrictsLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Districts'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if ('on' !== Options::getOption('site_publication') && (! $currentUser || ! $currentUser->canViewDistricts())) {
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
function getAddDistrictLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Add'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canAddDistrict()) {
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
function getEditDistrictsLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditDistricts()) {
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
        'fallbackContent' => false,
        'content' => __('Edit'),
        'atts' => [],
        'before' => '',
        'after' => '',
        'district' => null,
    ), $args);

    if (! $args['district'] instanceof District) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditDistrict($args['district'])) {
        return $args['fallbackContent'] ? $args['content'] : $link;
    }

    $args['atts']['href'] = getEditDistrictURL($args['district']->get('id'));

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
        'atts' => [],
        'before' => '',
        'after' => '',
        'district' => null,
    ), $args);

    if (! $args['district'] instanceof District) {
        return $link;
    }

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canDeleteDistrict($args['district'])) {
        return $link;
    }

    $args['atts']['href'] = getDeleteDistrictURL($args['district']->get('id'));

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'delete-link delete-district-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** Settings Template Tags ***************************************************/

/**
 * @return string
 * @since 1.0
 */
function getSettingsURL()
{
    $url = getSiteURL('/settings');
    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getSettingsLink(array $args = [])
{
    $link = '';

    $args = array_merge(array(
        'content' => __('Settings'),
        'atts' => [],
        'before' => '',
        'after' => '',
    ), $args);

    $currentUser = getCurrentUser();

    if (! $currentUser || ! $currentUser->canEditSettings()) {
        return $link;
    }

    $args['atts']['href'] = getSettingsURL();

    if (! isset($args['atts']['class'])) {
        $args['atts']['class'] = 'settings-link';
    }

    $link = '<a' . toAttributes($args['atts']) . '>' . $args['content'] . '</a>';
    return $args['before'] . $link . $args['after'];
}

/*** General Template Tags ****************************************************/

/**
 * @return array
 * @since 1.0
 */
function getPaginationURLs(array $args)
{
    $urls = array();

    $args = array_merge(array(
        'total'    => 1,
        'base_url' => '',
        'page_url' => '',
    ), $args);

    $args['total'] = (int) $args['total'];

    if ($args['total'] <= 1) {
        return $urls;
    }

    $args['page_url'] = urldecode($args['page_url']);

    for ($n = 1; $n <= $args['total']; $n++) {
        if (1 === $n) {
            $urls[$n] = $args['base_url'];
        } else {
            $urls[$n] = str_replace('%#%', $n, $args['page_url']);
        }
    }

    return $urls;
}
