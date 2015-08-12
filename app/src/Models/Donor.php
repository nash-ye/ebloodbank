<?php
/**
 * Donor Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Options;
use EBloodBank\Traits\EntityMeta;
use EBloodBank\Exceptions\InvaildArgument;

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
     * @var float
     * @since 1.0
     *
     * @Column(type="integer", name="donor_weight")
     */
    protected $weight = 0;

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
     * @JoinColumn(name="donor_distr_id", referencedColumnName="distr_id")
     */
    protected $district;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_address")
     */
    protected $address;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_phone")
     */
    protected $phone;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_email")
     */
    protected $email;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_rtime")
     */
    protected $rtime;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="donor_status")
     */
    protected $status = 'pending';

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
    public function isPublished()
    {
        return 'published' === $this->get('status');
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
                $value = (int) $value;
                break;
            case 'weight':
                $value = (float) $value;
                break;
            case 'email':
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                break;
            case 'name':
            case 'rtime':
            case 'phone':
            case 'gender':
            case 'status':
            case 'address':
            case 'birthdate':
            case 'blood_group':
                $value = trim(filter_var($value, FILTER_SANITIZE_STRING));
                break;
            case 'district':
                if (is_numeric($value)) {
                    $value = EntityManager::getDistrictRepository()->find($value);
                }
                break;
        }
        return $value;
    }

    /**
     * @throws \EBloodBank\Exceptions\InvaildArgument
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'id':
                if (! isVaildID($value)) {
                    throw new InvaildArgument(__('Invaild donor ID.'), 'invaild_donor_id');
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvaildArgument(__('Invaild donor name.'), 'invaild_donor_name');
                }
                break;
            case 'gender':
                if (! array_key_exists($value, (array) Options::getOption('genders'))) {
                    throw new InvaildArgument(__('Invaild donor gender.'), 'invaild_donor_gender');
                }
                break;
            case 'weight':
                if (! is_float($value)) {
                    // TODO: Check Min and Max weight.
                    throw new InvaildArgument(__('Invaild donor weight.'), 'invaild_donor_weight');
                }
                break;
            case 'blood_group':
                if (! in_array($value, (array) Options::getOption('blood_groups'))) {
                    throw new InvaildArgument(__('Invaild donor blood group.'), 'invaild_donor_blood_group');
                }
                break;
            case 'district':
                if (! $value instanceof District || ! isVaildID($value->get('id'))) {
                    throw new InvaildArgument(__('Invaild donor district object.'), 'invaild_donor_district');
                }
                break;
        }
        return true;
    }
}
