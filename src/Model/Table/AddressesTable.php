<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Addresses Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Address get($primaryKey, $options = [])
 * @method \App\Model\Entity\Address newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Address[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Address|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Address[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Address findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AddressesTable extends Table
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

        $this->setTable('addresses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Trimmer');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('lot')
            ->maxLength('lot', 10, __('Up to 10 characters only'))
            ->allowEmptyString('lot');

        $validator
            ->scalar('block')
            ->maxLength('block', 10, __('Up to 10 characters only'))
            ->allowEmptyString('block');

        $validator
            ->scalar('street')
            ->maxLength('street', 100, __('Up to 100 characters only'))
            ->allowEmptyString('street');

        $validator
            ->scalar('subdivision')
            ->maxLength('subdivision', 100, __('Up to 100 characters only'))
            ->allowEmptyString('subdivision');

        $validator
            ->scalar('city')
            ->maxLength('city', 190, __('Up to 190 characters only'))
            ->allowEmptyString('city');

        $validator
            ->scalar('province')
            ->maxLength('province', 255, __('Up to 190 characters only'))
            ->allowEmptyString('province');

        $validator
            ->requirePresence('country', true, __('Country is required'))
            ->notEmptyString('country', __('Country is required'))
            ->scalar('country')
            ->maxLength('country', 90, __('Up to 90 characters only'));

        $validator
            ->requirePresence('zipcode', true, __('Zipcode is required'))
            ->notEmptyString('zipcode', __('Zipcode is required'))
            ->scalar('zipcode')
            ->maxLength('zipcode', 18, __('Up to 18 characters only'));

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
