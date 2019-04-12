<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Supervisors Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\SupervisorsTable|\Cake\ORM\Association\BelongsTo $Supervisors
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\Supervisor get($primaryKey, $options = [])
 * @method \App\Model\Entity\Supervisor newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Supervisor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Supervisor|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Supervisor saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Supervisor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Supervisor[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Supervisor findOrCreate($search, callable $callback = null, $options = [])
 */
class SupervisorsTable extends Table
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

        $this->setTable('supervisors');
        $this->setDisplayField('person_id');
        $this->setPrimaryKey(['person_id', 'supervisor_id', 'rol']);

        $this->belongsTo('Users', [
            'foreignKey' => 'person_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Supervisors', [
            'foreignKey' => 'supervisor_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id'
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
            ->scalar('rol')
            ->maxLength('rol', 3)
            ->allowEmptyString('rol', 'create');

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
        $rules->add($rules->existsIn(['person_id'], 'Users'));
        $rules->add($rules->existsIn(['supervisor_id'], 'Supervisors'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
