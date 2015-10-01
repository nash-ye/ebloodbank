<?php
/**
 * Donor entity class file
 *
 * @package    EBloodBank
 * @subpackage Models
 * @since      1.0
 */
namespace EBloodBank\Models;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Traits\EntityMeta;

/**
 * Donor entity class
 *
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\DonorRepository")
 * @Table(name="donor")
 */
class Donor extends Entity
{
    use EntityMeta;

    /**
     * @var int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="donor_id")
     */
    protected $id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_name")
     */
    protected $name;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_gender")
     */
    protected $gender;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_birthdate")
     */
    protected $birthdate;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_blood_group")
     */
    protected $blood_group;

    /**
     * @var District
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\District")
     * @JoinColumn(name="donor_district_id", referencedColumnName="district_id")
     */
    protected $district;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="datetime", name="donor_created_at")
     */
    protected $created_at;

    /**
     * @var User
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\User")
     * @JoinColumn(name="donor_created_by", referencedColumnName="user_id")
     */
    protected $created_by;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_status")
     */
    protected $status;

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
     * @return bool
     * @since 1.0
     */
    public function isPending()
    {
        return 'pending' === $this->get('status');
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function isApproved()
    {
        return 'approved' === $this->get('status');
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getGenderTitle()
    {
        $gender = $this->get('gender');
        $genders = self::getGenderTitles();
        if (isset($genders[$gender])) {
            $gender = $genders[$gender];
        }
        return $gender;
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getAge($format = '%y')
    {
        $currentDate = new DateTime(date('Y-m-d'));
        $birthdate = new DateTime($this->get('birthdate'));

        if ($birthdate > $currentDate) {
            return 0;
        }

        return $currentDate->diff($birthdate)->format($format);
    }

    /**
     * @return array
     * @since 1.0
     * @static
     */
    public static function getBloodGroups()
    {
        return array(
            'A+',
            'A-',
            'B+',
            'B+',
            'O+',
            'O-',
            'AB+',
            'AB-',
        );
    }

    /**
     * @return array
     * @since 1.0
     * @static
     */
    public static function getGenderTitles()
    {
        return array(
            'male'   => __('Male'),
            'female' => __('Female'),
        );
    }

    /**
     * @return mixed
     * @since 1.0
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
            case 'gender':
            case 'status':
            case 'birthdate':
            case 'blood_group':
                $value = EBB\sanitizeSlug($value);
                break;
            case 'district':
                if (EBB\isValidID($value)) {
                    $em = main()->getEntityManager();
                    $value = $em->find('Entities:District', $value);
                }
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
     * @static
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'id':
                if (! EBB\isValidID($value)) {
                    throw new InvalidArgumentException(__('Invalid donor ID.'));
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgumentException(__('Invalid donor name.'));
                }
                break;
            case 'gender':
                if (! array_key_exists($value, self::getGenderTitles())) {
                    throw new InvalidArgumentException(__('Invalid donor gender.'));
                }
                break;
            case 'birthdate':
                break;
            case 'blood_group':
                if (! in_array($value, self::getBloodGroups(), true)) {
                    throw new InvalidArgumentException(__('Invalid donor blood group.'));
                }
                break;
            case 'district':
                if (! $value instanceof District || ! $value->isExists()) {
                    throw new InvalidArgumentException(__('Invalid donor district.'));
                }
                break;
            case 'created_at':
                break;
            case 'created_by':
                if (! $value instanceof User || ! $value->isExists()) {
                    throw new InvalidArgumentException(__('Invalid donor originator.'));
                }
                break;
            case 'status':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgumentException(__('Invalid donor status.'));
                }
                break;
        }
        return true;
    }

    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    public static function sanitizeMeta($metaKey, $metaValue)
    {
        switch ($metaKey) {
            case 'weight':
                $metaValue = EBB\sanitizeFloat($metaValue);
                break;
            case 'email':
                $metaValue = EBB\sanitizeEmail($metaValue);
                break;
            case 'phone':
                $metaValue = EBB\sanitizeInteger($metaValue);
                break;
            case 'address':
                $metaValue = EBB\sanitizeTitle($metaValue);
                break;
        }
        return $metaValue;
    }

    /**
     * @throws \InvalidArgumentException
     * @return bool
     * @since 1.0
     * @static
     */
    public static function validateMeta($metaKey, $metaValue)
    {
        switch ($metaKey) {
            case 'weight':
                if (! empty($metaValue) && ! EBB\isValidFloat($metaValue)) {
                    // TODO: Check Min and Max weight.
                    throw new InvalidArgumentException(__('Invalid donor weight.'));
                }
                break;
            case 'email':
                if (! empty($metaValue) && ! EBB\isValidEmail($metaValue)) {
                    throw new InvalidArgumentException(__('Invalid donor e-mail.'));
                }
                break;
            case 'phone':
                if (! empty($metaValue) && ! EBB\isValidInteger($metaValue)) {
                    throw new InvalidArgumentException(__('Invalid donor phone number.'));
                }
                break;
        }
        return true;
    }
}
