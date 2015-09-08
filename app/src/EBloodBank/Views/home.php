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

View::display('header', array( 'title' => __('Home') )); ?>

    <p><?php __e('Welcome to eBloodBank!') ?></p>

<?php
View::display('footer');
