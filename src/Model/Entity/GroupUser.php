<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GroupUser Entity
 *
 * @property int $group_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $date_from
 * @property \Cake\I18n\FrozenTime|null $date_to
 * @property \Cake\I18n\FrozenTime $start_time
 * @property \Cake\I18n\FrozenTime|null $end_time
 * @property string $repetition
 * @property string|null $rep_days
 * @property int|null $group_type_id
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\GroupType $group_type
 */
class GroupUser extends Entity
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
        'date_to' => true,
        'start_time' => true,
        'end_time' => true,
        'repetition' => true,
        'rep_days' => true,
        'group_type_id' => true,
        'group' => true,
        'user' => true,
        'group_type' => true
    ];
}
