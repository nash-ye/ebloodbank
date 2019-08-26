<?php
/**
 * Doctrine CLI configurations
 *
 * @package EBloodBank
 * @since   1.2
 */

// Load eBloodBank bootstrapper.
require_once dirname(__DIR__) . '/bootstrap.php';

$entityManager = $EBloodBank->getEntityManager();
return Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
