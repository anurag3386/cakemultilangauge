<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BirthDetail Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $country_id
 * @property int $city_id
 * @property \Cake\I18n\Time $date
 * @property string $day
 * @property string $time
 * @property int $sun_sign_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Country $country
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\SunSign $sun_sign
 */
class BirthDetail extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
