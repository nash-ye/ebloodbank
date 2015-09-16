<?php
/**
 * City Model
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
 * @Entity(repositoryClass="EBloodBank\Models\CityRepository")
 * @Table(name="city")
 */
class City extends Entity
{
    /**
     * @var int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="city_id")
     */
    protected $id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="city_name", unique=true)
     */
    protected $name;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="datetime", name="city_created_at")
     */
    protected $created_at;

    /**
     * @var User
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\User")
     * @JoinColumn(name="city_created_by", referencedColumnName="user_id")
     */
    protected $created_by;

    /**
     * @var District[]
     * @since 1.0
     *
     * @OneToMany(targetEntity="EBloodBank\Models\District", mappedBy="city")
     */
    protected $districts = array();

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
            case 'id':
                $value = EBB\sanitizeInteger($value);
                break;
            case 'name':
                $value = trim($value);
                break;
            case 'created_at':
                break;
            case 'created_by':
                if (EBB\isValidID($value)) {
                    $em = main()->getEntityManager();
                    $value = $em->find('Entities:User', $value);
                }
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
            case 'id':
                if (! EBB\isValidID($value)) {
                    throw new InvalidArgumentException(__('Invalid city ID.'));
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgumentException(__('Invalid city name.'));
                }
                break;
            case 'created_at':
                break;
            case 'created_by':
                if (! $value instanceof User || ! $value->isExists()) {
                    throw new InvalidArgumentException(__('Invalid city originator.'));
                }
                break;
        }
        return true;
    }
}
