<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StepsOptions Model
 *
 * @property \App\Model\Table\StepsTable|\Cake\ORM\Association\BelongsTo $Steps
 * @property \App\Model\Table\NextStepsTable|\Cake\ORM\Association\BelongsTo $NextSteps
 *
 * @method \App\Model\Entity\StepsOption get($primaryKey, $options = [])
 * @method \App\Model\Entity\StepsOption newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StepsOption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StepsOption|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StepsOption saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StepsOption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StepsOption[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StepsOption findOrCreate($search, callable $callback = null, $options = [])
 */
class StepsOptionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('steps_options');
        $this->setDisplayField('step_id');
        $this->setPrimaryKey(['step_id', 'option_order']);

        $this->belongsTo('Steps', [
            'foreignKey' => 'step_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('NextSteps', [
            'foreignKey' => 'next_step_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('option_order')
            ->allowEmptyString('option_order', 'create');

        $validator
            ->scalar('option_description')
            ->maxLength('option_description', 20)
            ->requirePresence('option_description', 'create')
            ->allowEmptyString('option_description', false);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['step_id'], 'Steps'));
        $rules->add($rules->existsIn(['next_step_id'], 'NextSteps'));

        return $rules;
    }
}
