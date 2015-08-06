<?php
namespace EBloodBank\Models;

use EBloodBank\Kernal\Model;
use EBloodBank\Kernal\Options;
use EBloodBank\Traits\EntityMeta;
use EBloodBank\Exceptions\InvaildProperty;

/**
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\DonorRepository")
 * @Table(name="donor")
 */
class Donor extends Model
{
    use EntityMeta;

    /**
     * @var int
     * @since 1.0
     *
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $donor_id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_name;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_gender;

    /**
     * @var int
     * @since 1.0
     *
     * @Column(type="integer")
     */
    protected $donor_weight = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_birthdate;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_blood_group;

    /**
     * @var int
     * @since 1.0
     *
     * @Column(type="integer")
     * @ManyToOne(targetEntity="District")
     */
    protected $donor_distr_id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_address;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_phone;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_email;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_rtime;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $donor_status = 'pending';

    /**
     * @var int
     * @since 1.0
     */
    public function calculateAge()
    {
        $current_date = new \DateTime(date('Y-m-d'));
        $birthdate = new \DateTime($this->get('donor_birthdate'));

        if ($birthdate > $current_date) {
            return 0;
        }

        return (int) $current_date->diff($birthdate)->format('%y');
    }

    /**
     * @var bool
     * @since 1.0
     */
    public function isPublished()
    {
        return 'published' === $this->get('donor_status');
    }

    /**
     * @var bool
     * @since 1.0
     */
    public function isPending()
    {
        return 'pending' === $this->get('donor_status');
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'donor_id':
            case 'donor_distr_id':
                $value = (int) $value;
                break;
            case 'donor_weight':
                $value = (float) $value;
                break;
            case 'donor_email':
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                break;
            case 'donor_name':
            case 'donor_rtime':
            case 'donor_phone':
            case 'donor_gender':
            case 'donor_status':
            case 'donor_address':
            case 'donor_birthdate':
            case 'donor_blood_group':
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;
        }
        return $value;
    }

    /**
     * @throws \EBloodBank\Exceptions\InvaildProperty
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'donor_id':
                if (! isVaildID($value)) {
                    throw new InvaildProperty(__('Invaild donor ID.'), 'invaild_donor_id');
                }
                break;
            case 'donor_name':
                if (empty($value) || ! is_string($value)) {
                    throw new InvaildProperty(__('Invaild donor name.'), 'invaild_donor_name');
                }
                break;
            case 'donor_gender':
                if (! array_key_exists($value, (array) Options::getOption('genders'))) {
                    throw new InvaildProperty(__('Invaild donor gender.'), 'invaild_donor_gender');
                }
                break;
            case 'donor_weight':
                if ($value < 0) {
                    throw new InvaildProperty(__('Invaild donor weight.'), 'invaild_donor_weight');
                }
                break;
            case 'donor_blood_group':
                if (! in_array($value, (array) Options::getOption('blood_groups'))) {
                    throw new InvaildProperty(__('Invaild donor blood group.'), 'invaild_donor_blood_group');
                }
                break;
        }
        return true;
    }
}
