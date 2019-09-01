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
 * @var int
 * @since 1.2
 */
const DATABASE_INSTALLED = 0;

/**
 * @var int
 * @since 1.2
 */
const DATABASE_NOT_SELECTED = 2;

/**
 * @var int
 * @since 1.2
 */
const DATABASE_NOT_CONNECTED = 4;

/**
 * @var int
 * @since 1.2
 */
const DATABASE_TABLE_NOT_EXIST = 8;

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

/**
 * Whether any of application database tables is exist.
 *
 * @return bool
 * @since  1.6
 */
function isAnyTablesExists(DBAL\Connection $connection)
{
    try {
        $tablesNames = ['user', 'donor', 'city', 'district', 'variable'];
        $randTableName = $tablesNames[array_rand($tablesNames, 1)];

        $connection->executeQuery("SELECT 1 FROM $randTableName LIMIT 1");
    } catch (DBAL\DBALException $ex) {
        return false;
    }

    return true;
}

/**
 * @return int
 * @since 1.2
 */
function getInstallationStatus(DBAL\Connection $connection, $forceCheck = false)
{
    static $status;

    if (is_null($status) || $forceCheck) {
        if (! isDatabaseSelected($connection)) {
            $status = DATABASE_NOT_SELECTED;
        } elseif (! isDatabaseConnected($connection)) {
            $status = DATABASE_NOT_CONNECTED;
        } elseif (! isAnyTablesExists($connection)) {
            $status = DATABASE_TABLE_NOT_EXIST;
        } else {
            $status = DATABASE_INSTALLED;
        }
    }

    return $status;
}
