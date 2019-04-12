<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GroupsTypes Model
 *
 * @method \App\Model\Entity\GroupsType get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupsType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GroupsType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupsType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupsType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupsType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupsType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupsType findOrCreate($search, callable $callback = null, $options = [])
 */
class GroupsTypesTable extends Table
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

        $this->setTable('groups_types');
        $this->setDisplayField('group_type_id');
        $this->setPrimaryKey('group_type_id');
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
            ->integer('group_type_id')
            ->allowEmptyString('group_type_id', 'create');

        $validator
            ->scalar('description')
            ->maxLength('description', 145)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

        return $validator;
    }
}
