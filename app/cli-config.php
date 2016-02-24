<?php
/**
 * eBloodBank CLI configurations
 *
 * @package eBloodBank
 * @since   1.2
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Load eBloodBank bootstrapper.
require_once 'bootstrap.php';

$entityManager = $EBloodBank->getEntityManager();
return ConsoleRunner::createHelperSet($entityManager);
