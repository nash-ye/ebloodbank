<?php
/**
 * Add district page controller class file
 *
 * @package    EBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Notices;
use EBloodBank\Models\District;
use EBloodBank\Views\View;
use Aura\Di\ContainerInterface;

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
    public function __construct(ContainerInterface $container)
    {
        $this->district = new District();
        parent::__construct($container);
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();
        if ($currentUser && $currentUser->canAddDistrict()) {
            $this->doActions();
            $this->addNotices();
            $district = $this->getQueriedDistrict();
            $view = View::forge('add-district', [
                'district' => $district,
            ]);
        } else {
            $view = View::forge('error-403');
        }
        $view();
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
            $currentUser = EBB\getCurrentUser();

            if (! $currentUser || ! $currentUser->canAddDistrict()) {
                return;
            }

            $session = $this->getContainer()->get('session');
            $sessionToken = $session->getCsrfToken();
            $actionToken = filter_input(INPUT_POST, 'token');

            if (! $actionToken || ! $sessionToken->isValid($actionToken)) {
                return;
            }

            $district = $this->getQueriedDistrict();
            $em = $this->getContainer()->get('entity_manager');
            $cityRepository = $em->getRepository('Entities:City');

            // Set the district name.
            $district->set('name', filter_input(INPUT_POST, 'district_name'), true);

            // Set the district city ID.
            $district->set('city', $cityRepository->find(filter_input(INPUT_POST, 'district_city_id')));

            // Set the creation date.
            $district->set('created_at', new DateTime('now', new DateTimeZone('UTC')));

            // Set the originator user.
            $district->set('created_by', EBB\getCurrentUser());

            $em->persist($district);
            $em->flush();

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

    /**
     * @return \EBloodBank\Models\District
     * @since 1.0
     */
    protected function getQueriedDistrict()
    {
        return $this->district;
    }
}
