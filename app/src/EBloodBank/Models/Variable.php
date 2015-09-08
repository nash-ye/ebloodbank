<?php
/**
 * Variable Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank as EBB;
use EBloodBank\Exceptions\InvalidArgument;

/**
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\VariableRepository")
 * @Table(name="variable")
 */
class Variable extends Entity
{
    /**
     * @var string
     * @since 1.0
     *
     * @Id
     * @Column(type="string", name="variable_name")
     */
    protected $name;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="variable_value")
     */
    protected $value;

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'name':
                $value = trim($value);
                break;
            case 'value':
                $value = (string) $value;
                break;
        }
        return $value;
    }

    /**
     * @throws \EBloodBank\Exceptions\InvalidArgument
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid variable name.'), 'Invalid_variable_name');
                }
                break;
            case 'value':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid variable value.'), 'Invalid_variable_value');
                }
                break;
        }
        return true;
    }
}
