<?php
/**
 * Add district page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Models\District;

/**
 * Add district page controller class
 *
 * @since 1.0
 */
class AddDistrict extends Controller
{
    /**
     * @var \EBloodBank\Models\District
     * @since 1.0
     */
    protected $district;

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'add')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->district = new District();

        $this->doActions();
        $this->addNotices();
        $this->viewFactory->displayView(
            'add-district',
            [
                'district' => $this->district,
            ]
        );
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_POST, 'action')) {
            case 'submit_district':
                $this->doSubmitAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-added')) {
            Notices::addNotice('added', __('District added.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        try {
            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'add')) {
                return;
            }

            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $district = $this->district;

            // Set the district name.
            $district->set('name', filter_input(INPUT_POST, 'district_name'), true);

            // Set the district city ID.
            $district->set('city', $this->getCityRepository()->find(filter_input(INPUT_POST, 'district_city_id')));

            // Set the originator user.
            $district->set('created_by', $this->getAuthenticatedUser());

            $this->getEntityManager()->persist($district);
            $this->getEntityManager()->flush();

            $added = $district->isExists();

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getAddDistrictURL(),
                    ['flag-added' => $added]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_district_argument', $ex->getMessage());
        }
    }
}
