<?php
/**
 * View users page controller class file
 *
 * @package    eBloodBank
 * @subpackage Controllers
 * @since      1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Views\View;

/**
 * View users page controller class
 *
 * @since 1.0
 */
class ViewUsers extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        $currentUser = EBB\getCurrentUser();
        if (! $currentUser || ! $currentUser->canViewDonors()) {
            View::display('error-403');
        } else {
            View::display('view-users', [
                'users' => $this->getQueriedUsers(),
                'pagination.total' => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
            ]);
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public function getPagesTotal()
    {
        $total = 1;
        $limit = (int) Options::getOption('entities_per_page');
        if ($limit >= 1) {
            $total = (int) ceil($this->countAllUsers() / $limit);
        }
        return $total;
    }

    /**
     * @return int
     * @since 1.0
     */
    public function getCurrentPage()
    {
        return max((int) filter_input(INPUT_GET, 'page'), 1);
    }

    /**
     * @return \EBloodBank\Models\User[]
     * @since 1.0
     */
    public function getAllUsers()
    {
        $entityManager = $this->getContainer()->get('entity_manager');
        $userRepository = $entityManager->getRepository('Entities:User');

        return $userRepository->findAll([], ['created_at' => 'DESC']);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countAllUsers()
    {
        $entityManager = $this->getContainer()->get('entity_manager');
        $userRepository = $entityManager->getRepository('Entities:User');

        return $userRepository->countAll();
    }

    /**
     * @return \EBloodBank\Models\User[]
     * @since 1.0
     */
    public function getQueriedUsers()
    {
        $entityManager = $this->getContainer()->get('entity_manager');
        $userRepository = $entityManager->getRepository('Entities:User');

        $limit = (int) Options::getOption('entities_per_page');
        $offset = ($this->getCurrentPage() - 1) * $limit;

        return $userRepository->findBy([], ['created_at' => 'DESC'], $limit, $offset);
    }

    /**
     * @return int
     * @since 1.0
     */
    public function countQueriedUsers()
    {
        return count($this->getQueriedUsers());
    }
}
