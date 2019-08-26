<?php
/**
 * Entity meta trait file
 *
 * @package    eBloodBank
 * @subpackage Models
 * @since      1.0
 */
namespace EBloodBank\Traits;

use EBloodBank as EBB;

/**
 * Entity meta trait
 *
 * @since 1.0
 */
trait EntityMeta
{
    /**
     * @var array
     * @since 1.0
     */
    protected $meta = [];

    /**
     * @return mixed
     * @since 1.0
     */
    public function getMeta($metaKey, $single = true)
    {
        if ($single) {
            $metaValue = null;
        } else {
            $metaValue = [];
        }

        $em = EBB\Main::getInstance()->getEntityManager();
        $db = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = $this->get($eIDFieldName);

        if (! EBB\isValidID($eID)) {
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
        $em = EBB\Main::getInstance()->getEntityManager();
        $db = $em->getConnection();

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

        $data = [
            $eIDColumnName  => $eID,
            'meta_value'    => $metaValue,
            'meta_key'      => $metaKey,
        ];

        $inserted = (bool) $db->insert($eMetaTableName, $data);
        $metaID = ($inserted) ? (int) $db->lastInsertId() : 0;

        return $metaID;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function updateMeta($metaKey, $metaValue, $currentMetaValue = null, $sanitize = false, $validate = true)
    {
        $em = EBB\Main::getInstance()->getEntityManager();
        $db = $em->getConnection();

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

        $data = [
            'meta_value' => $metaValue,
        ];

        $criteria = [
            $eIDColumnName => $eID,
            'meta_key'     => $metaKey,
        ];

        if (! is_null($currentMetaValue)) {
            $criteria['meta_value'] = $currentMetaValue;
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
        $em = EBB\Main::getInstance()->getEntityManager();
        $db = $em->getConnection();

        $eMetaData = $em->getClassMetadata(get_class());
        $eMetaTableName = $eMetaData->getTableName() . '_meta';
        $eIDFieldName = $eMetaData->getSingleIdentifierFieldName();
        $eIDColumnName = $eMetaData->getSingleIdentifierColumnName();

        $eID = $this->get($eIDFieldName);

        if (! EBB\isValidID($eID)) {
            return false;
        }

        $criteria = [
            $eIDColumnName => $eID,
            'meta_key'     => $metaKey,
        ];

        if (! is_null($metaValue)) {
            $criteria['meta_value'] = $metaValue;
        }

        $deleted = (bool) $db->delete($eMetaTableName, $criteria);

        return $deleted;
    }

    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    public static function sanitizeMeta($metaKey, $metaValue)
    {
        return $metaValue;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function validateMeta($metaKey, $metaValue)
    {
        return true;
    }
}
