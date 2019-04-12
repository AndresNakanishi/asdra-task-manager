<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StepsOption Entity
 *
 * @property int $step_id
 * @property int $option_order
 * @property string $option_description
 * @property int|null $next_step_id
 *
 * @property \App\Model\Entity\Step $step
 * @property \App\Model\Entity\NextStep $next_step
 */
class StepsOption extends Entity
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
        'option_description' => true,
        'next_step_id' => true,
        'step' => true,
        'next_step' => true
    ];
}
