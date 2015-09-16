<?php
/**
 * Variable Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use InvalidArgumentException;
use EBloodBank as EBB;

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
     * @Column(type="string", name="variable_value", nullable=true)
     */
    protected $value;

    /**
     * @return bool
     * @since 1.0
     */
    public function isExists()
    {
        $id = (int) $this->get('id');
        return ! empty($id);
    }

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
                break;
        }
        return $value;
    }

    /**
     * @throws \InvalidArgumentException
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'name':
                if (empty($value)) {
                    throw new InvalidArgumentException(__('Invalid variable name.'));
                }
                break;
            case 'value':
                break;
        }
        return true;
    }
}
