<?php
namespace EBloodBank\Traits;

use EBloodBank\EntityManager;

/**
 * @since 1.0
 */
trait EntityMeta
{
    /**
     * @var array
     * @since 1.0
     */
    protected $meta = array();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getMeta($metaKey, $single = true)
    {
        if ($single) {
            $metaValue = null;
        } else {
            $metaValue = array();
        }

        $em = EntityManager::getInstance();
        $db = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = parent::get($eIDFieldName);

        if (! isVaildID($eID)) {
            return $metaValue;
        }

        if (! isset($this->meta[$metaKey])) {
            $rows = $db->fetchAll("SELECT meta_id, meta_value FROM $eMetaTableName WHERE $eIDColumnName = ? AND meta_key = ?", array( $eID, $metaKey ));
            if (! empty($rows)) {
                foreach ($rows as $row) {
                    $this->meta[$metaKey][$row['meta_id']] = $row['meta_value'];
                }
            }
        }

        if (isset($this->meta[$metaKey])) {
            if ($single) {
                $metaValue = reset($this->meta[$metaKey]);
            } else {
                $metaValue = (array) $this->meta[$metaKey];
            }
        }

        return $metaValue;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function submitMeta($metaKey, $metaValue)
    {
        if (empty($metaValue)) {
            $this->deleteMeta($metaKey);
        } else {
            $prevMetaValue = $this->getMeta($metaKey);
            if (is_null($prevMetaValue)) {
                $this->addMeta($metaKey, $metaValue);
            } else {
                $this->updateMeta($metaKey, $metaValue);
            }
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public function addMeta($metaKey, $metaValue)
    {
        $em = EntityManager::getInstance();
        $db = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = parent::get($eIDFieldName);

        if (! isVaildID($eID)) {
            return false;
        }

        $data = array(
            $eIDColumnName  => $eID,
            'meta_value'    => $metaValue,
            'meta_key'      => $metaKey,
        );

        $inserted = (bool) $db->insert($eMetaTableName, $data);
        $metaID = ($inserted) ? (int) $db->lastInsertId() : 0;

        return $metaID;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function updateMeta($metaKey, $metaValue, $prevMetaValue = null)
    {
        $em = EntityManager::getInstance();
        $db = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = parent::get($eIDFieldName);

        if (! isVaildID($eID)) {
            return false;
        }

        $data = array(
            'meta_value' => $metaValue,
        );

        $criteria = array(
            $eIDColumnName => $eID,
            'meta_key'     => $metaKey,
        );

        if (! is_null($prevMetaValue)) {
            $criteria['meta_value'] = $prevMetaValue;
        }

        $updated = (bool) $db->update($eMetaTableName, $data, $criteria);

        return $updated;
    }

    /**
     * @return int
     * @since 1.0
     */
    public function deleteMeta($metaKey, $metaValue = null)
    {
        $em = EntityManager::getInstance();
        $db = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = parent::get($eIDFieldName);

        if (! isVaildID($eID)) {
            return false;
        }

        $criteria = array(
            $eIDColumnName => $eID,
            'meta_key'     => $metaKey,
        );

        if (! is_null($metaValue)) {
            $criteria['meta_value'] = $metaValue;
        }

        $deleted = (bool) $db->delete($eMetaTableName, $criteria);

        return $deleted;
    }
}
