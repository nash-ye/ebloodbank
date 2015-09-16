<?php
namespace EBloodBank\Traits;

use EBloodBank as EBB;

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

        $em = main()->getEntityManager();
        $connection = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = $this->get($eIDFieldName);

        if (! EBB\isValidID($eID)) {
            return $metaValue;
        }

        if (! isset($this->meta[$metaKey])) {
            $rows = $connection->fetchAll("SELECT meta_id, meta_value FROM $eMetaTableName WHERE $eIDColumnName = ? AND meta_key = ?", array( $eID, $metaKey ));
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
    public function submitMeta($metaKey, $metaValue, $sanitize = false, $validate = true)
    {
        if (empty($metaValue)) {
            $this->deleteMeta($metaKey);
        } else {
            $currentMetaValue = $this->getMeta($metaKey);
            if (is_null($currentMetaValue)) {
                $this->addMeta($metaKey, $metaValue, $sanitize, $validate);
            } else {
                $this->updateMeta($metaKey, $metaValue, $currentMetaValue, $sanitize, $validate);
            }
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public function addMeta($metaKey, $metaValue, $sanitize = false, $validate = true)
    {
        $em = main()->getEntityManager();
        $connection = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = $this->get($eIDFieldName);

        if (! EBB\isValidID($eID)) {
            return false;
        }

        if ($sanitize) {
            $metaValue = static::sanitizeMeta($metaKey, $metaValue);
        }

        if ($validate && ! static::validateMeta($metaKey, $metaValue)) {
            return false;
        }

        $data = array(
            $eIDColumnName  => $eID,
            'meta_value'    => $metaValue,
            'meta_key'      => $metaKey,
        );

        $inserted = (bool) $connection->insert($eMetaTableName, $data);
        $metaID = ($inserted) ? (int) $connection->lastInsertId() : 0;

        return $metaID;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function updateMeta($metaKey, $metaValue, $currentMetaValue = null, $sanitize = false, $validate = true)
    {
        $em = main()->getEntityManager();
        $connection = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = $this->get($eIDFieldName);

        if (! EBB\isValidID($eID)) {
            return false;
        }

        if ($sanitize) {
            $metaValue = static::sanitizeMeta($metaKey, $metaValue);
        }

        if ($validate && ! static::validateMeta($metaKey, $metaValue)) {
            return false;
        }

        $data = array(
            'meta_value' => $metaValue,
        );

        $criteria = array(
            $eIDColumnName => $eID,
            'meta_key'     => $metaKey,
        );

        if (! is_null($currentMetaValue)) {
            $criteria['meta_value'] = $currentMetaValue;
        }

        $updated = (bool) $connection->update($eMetaTableName, $data, $criteria);

        return $updated;
    }

    /**
     * @return int
     * @since 1.0
     */
    public function deleteMeta($metaKey, $metaValue = null)
    {
        $em = main()->getEntityManager();
        $connection = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = $this->get($eIDFieldName);

        if (! EBB\isValidID($eID)) {
            return false;
        }

        $criteria = array(
            $eIDColumnName => $eID,
            'meta_key'     => $metaKey,
        );

        if (! is_null($metaValue)) {
            $criteria['meta_value'] = $metaValue;
        }

        $deleted = (bool) $connection->delete($eMetaTableName, $criteria);

        return $deleted;
    }

    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    static public function sanitizeMeta($metaKey, $metaValue)
    {
        return $metaValue;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    static public function validateMeta($metaKey, $metaValue)
    {
        return true;
    }
}
