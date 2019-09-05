<?php
/**
 * Edit district page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use Psr\Container\ContainerInterface;

/**
 * Edit district page controller class
 *
 * @since 1.0
 */
class EditDistrict extends Controller
{
    /**
     * @var   int
     * @since 1.6
     */
    protected $districtId = 0;

    /**
     * @var   \EBloodBank\Models\District|null
     * @since 1.0
     */
    protected $district;

    /**
     * @since 1.0
     */
    public function __construct(ContainerInterface $container, $districtId)
    {
        parent::__construct($container);
        if (EBB\isValidID($districtId)) {
            $this->districtId = (int) $districtId;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->isUserAllowed($this->getAuthenticatedUser(), 'District', 'edit')) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        if ($this->districtId) {
            $this->district = $this->getDistrictRepository()->find($this->districtId);
        }

        if (! $this->district) {
            $this->viewFactory->displayView('error-404');
            return;
        }

        $district = $this->district;

        if (! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $district)) {
            $this->viewFactory->displayView('error-403');
            return;
        }

        $this->doActions();
        $this->addNotices();
        $this->viewFactory->displayView(
            'edit-district',
            [
                'district' => $district,
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
        if (filter_has_var(INPUT_GET, 'flag-edited')) {
            Notices::addNotice('edited', __('District edited.'), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doSubmitAction()
    {
        try {
            $sessionToken = $this->getSession()->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $district = $this->district;

            if (! $this->hasAuthenticatedUser() || ! $this->getAcl()->canEditEntity($this->getAuthenticatedUser(), $district)) {
                return;
            }

            // Set the district name.
            $district->set('name', filter_input(INPUT_POST, 'district_name'), true);

            // Set the district city ID.
            $district->set('city', $this->getCityRepository()->find(filter_input(INPUT_POST, 'district_city_id')));

            $this->getEntityManager()->flush($district);

            EBB\redirect(
                EBB\addQueryArgs(
                    EBB\getEditDistrictURL($district->get('id')),
                    ['flag-edited' => true]
                )
            );
        } catch (InvalidArgumentException $ex) {
            Notices::addNotice('invalid_district_argument', $ex->getMessage());
        }
    }
}
