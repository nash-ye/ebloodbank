<?php
/**
 * Entity meta trait file
 *
 * @package    EBloodBank
 * @subpackage Traits
 * @since      1.0
 */
namespace EBloodBank\Traits;

/**
 * Entity meta trait
 *
 * @since 1.0
 */
trait EntityMeta
{
    /**
     * Entity meta
     *
     * @var   array
     * @since 1.0
     */
    protected $meta = [];

    /**
     * Get entity meta value
     *
     * @return mixed
     * @since  1.4
     */
    public function getMeta(string $key, $fallback = '')
    {
        $value = $fallback;

        if (! is_array($this->meta)) {
            return $value;
        }

        if (! isset($this->meta[$key])) {
            return $value;
        }

        $value = $this->meta[$key];

        return $value;
    }

    /**
     * Set entity meta value
     *
     * @return void
     * @since  1.4
     */
    public function setMeta(string $key, $value, $sanitize = false, $validate = true)
    {
        if (is_null($this->meta)) {
            $this->meta = [];
        }

        if ($sanitize) {
            $value = static::sanitizeMeta($key, $value);
        }

        if (! $validate || static::validateMeta($key, $value)) {
            $this->meta[$key] = $value;
        }
    }

    /**
     * Delete meta value
     *
     * @return void
     * @since  1.0
     */
    public function deleteMeta(string $key)
    {
        unset($this->meta[$key]);
    }

    /**
     * Sanitize meta value
     *
     * @return mixed
     * @since  1.0
     * @static
     */
    public static function sanitizeMeta(string $key, $value)
    {
        return $value;
    }

    /**
     * Validate meta value
     *
     * @return bool
     * @since  1.0
     * @static
     */
    public static function validateMeta(string $key, $value)
    {
        return true;
    }
}
