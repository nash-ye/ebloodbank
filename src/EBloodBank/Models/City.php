<?php
/**
 * City entity class file
 *
 * @package    EBloodBank
 * @subpackage Models
 * @since      1.0
 */
namespace EBloodBank\Models;

use DateTime;
use DateTimeZone;
use EBloodBank as EBB;
use InvalidArgumentException;

/**
 * City entity class
 *
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\CityRepository")
 * @Table(name="city")
 * @HasLifecycleCallbacks
 */
class City extends Entity
{
    /**
     * City ID
     * 
     * @var   int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="city_id")
     */
    protected $id = 0;

    /**
     * City name
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="city_name", unique=true)
     */
    protected $name;

    /**
     * City creation datetime
     * 
     * @var   \DateTime
     * @since 1.0
     *
     * @Column(type="datetime", name="city_created_at")
     */
    protected $created_at;

    /**
     * City created by
     * 
     * @var   User
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\User")
     * @JoinColumn(name="city_created_by", referencedColumnName="user_id")
     */
    protected $created_by;

    /**
     * City districts
     * 
     * @var   District[]
     * @since 1.0
     *
     * @OneToMany(targetEntity="EBloodBank\Models\District", mappedBy="city")
     */
    protected $districts = [];

    /**
     * @return bool
     * @since  1.0
     */
    public function isExists()
    {
        $id = (int) $this->get('id');
        return ! empty($id);
    }

    /**
     * @return void
     * @since  1.6
     * 
     * @PrePersist
     */
    public function doActionOnPrePersist()
    {
        $this->set('created_at', new DateTime('now', new DateTimeZone('UTC')));
    }

    /**
     * @return mixed
     * @since  1.0
     * @static
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'id':
                $value = EBB\sanitizeInteger($value);
                break;
            case 'name':
                $value = EBB\sanitizeTitle($value);
                break;
        }

        return $value;
    }

    /**
     * @throws \InvalidArgumentException
     * @return bool
     * @since  1.0
     * @static
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
            case 'created_by':
                if (! $value instanceof User || ! $value->isExists()) {
                    throw new InvalidArgumentException(__('Invalid city originator.'));
                }
                break;
        }

        return true;
    }
}
