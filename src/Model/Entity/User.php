<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;

/**
 * User Entity
 *
 * @property string $name
 * @property int $user_id
 * @property string|null $photo
 * @property string|null $user
 * @property string|null $password
 * @property string $user_type
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $locale_id
 * @property string|null $hash
 * @property string|null $token
 * @property string|null $address
 *
 * @property \App\Model\Entity\Locale $locale
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
        'name' => true,
        'photo' => true,
        'user' => true,
        'password' => true,
        'user_type' => true,
        'phone' => true,
        'email' => true,
        'locale_id' => true,
        'hash' => true,
        'token' => true,
        'address' => true,
        'locale' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token'
    ];

    protected function _setPassword($value)
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();
            return $hasher->hash($value);
        }
    }
    
    /**
     * Obtiene la instancia de un usuario a partir de su id.
     *
     * @param int $id ID del usuario
     *
     * @return App\Model\Entity\User Intancia del usuario con los datos del profile.
     */
    public static function get_user(int $id)
    {
        return TableRegistry::get('users')
            ->find('all')
            ->where([
                'users.user_id' => $id
            ])
            ->first();
    }
}
