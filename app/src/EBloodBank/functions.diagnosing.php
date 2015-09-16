<?php
/**
 * Diagnosing Functions
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

use Doctrine\DBAL;

/**
 * @return bool
 * @since 1.0
 */
function isDatabaseConnected(DBAL\Connection $connection)
{
    try {

        $connection->connect();
        return $connection->isConnected();

    } catch (DBAL\DBALException $ex) {
        return false;
    }
}

/**
 * @return bool
 * @since 1.0
 */
function isDatabaseSelected(DBAL\Connection $connection)
{
    try {

        $dbName = $connection->getDatabase();
        return $dbName ? true : false;

    } catch (DBAL\DBALException $ex) {
        return false;
    }
}

/**
 * @return bool
 * @since 1.0
 */
function isAllTablesExists(DBAL\Connection $connection)
{
    try {

        $tablesNames = ['user', 'donor', 'city', 'district', 'variable'];

        foreach ($tablesNames as $tableName) {
            $connection->executeQuery("SELECT 1 FROM $tableName LIMIT 1");
        }

    } catch (DBAL\DBALException $ex) {
        return false;
    }

    return true;
}
