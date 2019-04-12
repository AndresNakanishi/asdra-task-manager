<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Step Entity
 *
 * @property int $task_id
 * @property int $step_id
 * @property string $title
 * @property string|null $sub_title
 * @property string|null $photo
 * @property int $step_order
 * @property bool|null $required
 *
 * @property \App\Model\Entity\Task $task
 */
class Step extends Entity
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
        'task_id' => true,
        'title' => true,
        'sub_title' => true,
        'photo' => true,
        'step_order' => true,
        'required' => true,
        'task' => true
    ];
}
