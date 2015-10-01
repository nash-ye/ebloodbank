<?php
/**
 * Diagnosing functions file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

use Doctrine\DBAL;

/**
 * Try to establish a connection with the database.
 *
 * @return bool
 * @since 1.0
 */
function tryDatabaseConnection(DBAL\Connection $connection)
{
    try {
        return $connection->connect();
    } catch (DBAL\DBALException $ex) {
        return false;
    }
}

/**
 * Whether an actual connection to the database is established.
 *
 * @return bool
 * @since 1.0
 */
function isDatabaseConnected(DBAL\Connection $connection)
{
    return $connection->isConnected();
}

/**
 * Whether a connection had specified which database to use.
 *
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
 * Whether all the application database tables are exist.
 *
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
