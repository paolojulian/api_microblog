<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Address Entity
 *
 * @property int $id
 * @property string|null $lot
 * @property string|null $block
 * @property string|null $street
 * @property string|null $subdivision
 * @property string|null $city
 * @property string|null $province
 * @property string $country
 * @property string $zipcode
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\User $user
 */
class Address extends Entity
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
        'lot' => true,
        'block' => true,
        'street' => true,
        'subdivision' => true,
        'city' => true,
        'province' => true,
        'country' => true,
        'zipcode' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'user' => true,
    ];
}
