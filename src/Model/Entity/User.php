<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property int $mobile
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property \Cake\I18n\FrozenTime $birthdate
 * @property string|null $lot
 * @property string|null $block
 * @property string|null $street
 * @property string|null $subdivision
 * @property string|null $city
 * @property string|null $province
 * @property string $country
 * @property string $zipcode
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $is_activated
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\Follower[] $followers
 * @property \App\Model\Entity\Like[] $likes
 * @property \App\Model\Entity\Notification[] $notifications
 * @property \App\Model\Entity\Post[] $posts
 */
class User extends Entity
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
        'username' => true,
        'email' => true,
        'mobile' => true,
        'password' => true,
        'first_name' => true,
        'last_name' => true,
        'birthdate' => true,
        'created' => true,
        'modified' => true,
        'is_activated' => true,
        'deleted' => true,
        'comments' => true,
        'followers' => true,
        'likes' => true,
        'notifications' => true,
        'posts' => true,
        'address' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];

    protected function _getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
