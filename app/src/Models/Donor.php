<?php
/**
 * Donor Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank\Options;
use EBloodBank\Traits\EntityMeta;
use EBloodBank\Exceptions\InvalidArgument;

/**
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
     * @Column(type="string", name="donor_created_at")
     */
    protected $created_at;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="integer", name="donor_created_by")
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
     * @var bool
     * @since 1.0
     */
    public function isPending()
    {
        return 'pending' === $this->get('status');
    }

    /**
     * @var bool
     * @since 1.0
     */
    public function isApproved()
    {
        return 'approved' === $this->get('status');
    }

    /**
     * @var int
     * @since 1.0
     */
    public function calculateAge()
    {
        $currentDate = new \DateTime(date('Y-m-d'));
        $birthdate = new \DateTime($this->get('birthdate'));

        if ($birthdate > $currentDate) {
            return 0;
        }

        return (int) $currentDate->diff($birthdate)->format('%y');
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'id':
            case 'created_by':
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'weight': // Meta value.
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
                break;
            case 'email': // Meta value.
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                break;
            case 'name':
            case 'phone': // Meta value.
            case 'gender':
            case 'status':
            case 'address': // Meta value.
            case 'birthdate':
            case 'blood_group':
            case 'created_at':
                $value = trim($value);
                break;
            case 'district':
                if (isValidID($value)) {
                    $em = main()->getEntityManager();
                    $value = $em->find('Entities:District', $value);
                }
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
            case 'id':
                if (! isValidID($value)) {
                    throw new InvalidArgument(__('Invalid donor ID.'), 'Invalid_donor_id');
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid donor name.'), 'Invalid_donor_name');
                }
                break;
            case 'gender':
                if (! array_key_exists($value, (array) Options::getOption('genders'))) {
                    throw new InvalidArgument(__('Invalid donor gender.'), 'Invalid_donor_gender');
                }
                break;
            case 'birthdate':
                // TODO: Checks the validity of DATETIME.
                break;
            case 'blood_group':
                if (! in_array($value, (array) Options::getOption('blood_groups'))) {
                    throw new InvalidArgument(__('Invalid donor blood group.'), 'Invalid_donor_blood_group');
                }
                break;
            case 'district':
                if (! $value instanceof District || ! $value->isExists()) {
                    throw new InvalidArgument(__('Invalid donor district.'), 'Invalid_donor_district');
                }
                break;
            case 'created_at':
                // TODO: Checks the validity of DATETIME.
                break;
            case 'created_by':
                if (! isValidID($value)) {
                    throw new InvalidArgument(__('Invalid donor user.'), 'Invalid_donor_user');
                }
                break;

            /* Donor Meta */

            case 'email':
                if (! isValidEmail($value)) {
                    throw new InvalidArgument(__('Invalid donor email.'), 'Invalid_donor_email');
                }
                break;
            case 'weight':
                if (! isValidFloat($value)) {
                    // TODO: Check Min and Max weight.
                    throw new InvalidArgument(__('Invalid donor weight.'), 'Invalid_donor_weight');
                }
                break;
        }
        return true;
    }
}
