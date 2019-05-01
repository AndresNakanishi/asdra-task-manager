<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GroupUsers Model
 *
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GroupTypesTable|\Cake\ORM\Association\BelongsTo $GroupTypes
 *
 * @method \App\Model\Entity\GroupUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GroupUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupUser findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupUsersTable extends Table
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

        $this->setTable('group_users');
        $this->setDisplayField('group_id');
        $this->setPrimaryKey(['group_id', 'user_id', 'date_from']);

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->dateTime('date_from')
            ->allowEmptyDateTime('date_from', 'create');

        $validator
            ->dateTime('date_to')
            ->allowEmptyDateTime('date_to');

        $validator
            ->time('start_time')
            ->requirePresence('start_time', 'create')
            ->allowEmptyTime('start_time', false);

        $validator
            ->time('end_time')
            ->allowEmptyTime('end_time');

        $validator
            ->scalar('repetition')
            ->maxLength('repetition', 3)
            ->requirePresence('repetition', 'create')
            ->allowEmptyString('repetition', false);

        $validator
            ->scalar('rep_days')
            ->maxLength('rep_days', 45)
            ->allowEmptyString('rep_days');

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
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
