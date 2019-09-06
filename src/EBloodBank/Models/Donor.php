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
 * @HasLifecycleCallbacks
 */
class Donor extends Entity
{
    use EntityMeta;

    /**
     * Donor ID
     * 
     * @var   int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="donor_id")
     */
    protected $id = 0;

    /**
     * Donor name
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="donor_name")
     */
    protected $name;

    /**
     * Donor gender
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="donor_gender")
     */
    protected $gender;

    /**
     * Donor birthdate
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="donor_birthdate")
     */
    protected $birthdate;

    /**
     * Donor blood group
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="donor_blood_group")
     */
    protected $blood_group;

    /**
     * Donor district
     * 
     * @var   District
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\District", inversedBy="donors")
     * @JoinColumn(name="donor_district_id", referencedColumnName="district_id")
     */
    protected $district;

    /**
     * Donor creation datetime
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="datetime", name="donor_created_at")
     */
    protected $created_at;

    /**
     * Donor created by
     * 
     * @var   User
     * @since 1.0
     *
     * @ManyToOne(targetEntity="EBloodBank\Models\User")
     * @JoinColumn(name="donor_created_by", referencedColumnName="user_id")
     */
    protected $created_by;

    /**
     * Donor status
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="donor_status")
     */
    protected $status;

    /**
     * Donor meta
     * 
     * @var   array
     * @since 1.0
     *
     * @Column(type="json", name="donor_meta")
     */
    protected $meta = [];

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
        $genders = EBB\getGenders();
        $gender = $this->get('gender');
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
                if (! array_key_exists($value, EBB\getGenders())) {
                    throw new InvalidArgumentException(__('Invalid donor gender.'));
                }
                break;
            case 'blood_group':
                if (! in_array($value, EBB\getBloodGroups(), true)) {
                    throw new InvalidArgumentException(__('Invalid donor blood group.'));
                }
                break;
            case 'district':
                if (! $value instanceof District || ! $value->isExists()) {
                    throw new InvalidArgumentException(__('Invalid donor district.'));
                }
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
    public static function sanitizeMeta(string $key, $value)
    {
        switch ($key) {
            case 'weight':
                $value = EBB\sanitizeFloat($value);
                break;
            case 'email':
                $value = EBB\sanitizeEmail($value);
                break;
            case 'phone':
                $value = EBB\sanitizeInteger($value);
                break;
            case 'address':
                $value = EBB\sanitizeTitle($value);
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
    public static function validateMeta(string $key, $value)
    {
        switch ($key) {
            case 'weight':
                if (! empty($value) && ! EBB\isValidFloat($value)) {
                    // TODO: Check Min and Max weight.
                    throw new InvalidArgumentException(__('Invalid donor weight.'));
                }
                break;
            case 'email':
                if (! empty($value) && ! EBB\isValidEmail($value)) {
                    throw new InvalidArgumentException(__('Invalid donor e-mail address.'));
                }
                break;
            case 'email_visibility':
                if (! empty($value) && ! array_key_exists($value, EBB\getVisibilities())) {
                    throw new InvalidArgumentException(__('Invalid donor e-mail address visibility.'));
                }
                break;
            case 'phone':
                if (! empty($value) && ! ctype_digit($value)) {
                    throw new InvalidArgumentException(__('Invalid donor phone number.'));
                }
                break;
            case 'phone_visibility':
                if (! empty($value) && ! array_key_exists($value, EBB\getVisibilities())) {
                    throw new InvalidArgumentException(__('Invalid donor phone number visibility.'));
                }
                break;
        }

        return true;
    }
}
