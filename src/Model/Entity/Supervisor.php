<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Supervisor Entity
 *
 * @property int $person_id
 * @property int $supervisor_id
 * @property string $rol
 * @property int|null $company_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Supervisor $supervisor
 * @property \App\Model\Entity\Company $company
 */
class Supervisor extends Entity
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
        'company_id' => true,
        'user' => true,
        'supervisor' => true,
        'company' => true
    ];
}
