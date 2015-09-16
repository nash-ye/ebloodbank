<?php
/**
 * Home Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

View::display('header', ['title' => __('Home')]); ?>

    <p><?= EBB\escHTML(__('Welcome to eBloodBank!')) ?></p>

<?php
View::display('footer');
