<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $task_id
 * @property int $group_id
 * @property string|null $description_1
 * @property string|null $description_2
 * @property bool $required
 * @property int $priority
 * @property int $task_order
 *
 * @property \App\Model\Entity\Group $group
 */
class Task extends Entity
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
        'group_id' => true,
        'description_1' => true,
        'description_2' => true,
        'required' => true,
        'priority' => true,
        'task_order' => true,
        'group' => true
    ];
}
