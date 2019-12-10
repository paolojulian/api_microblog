<?php
namespace App\Model\Table;

use App\Exception\UserNotFoundException;
use App\Exception\ValidationErrorsException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $Comments
 * @property \App\Model\Table\FollowersTable&\Cake\ORM\Association\HasMany $Followers
 * @property \App\Model\Table\LikesTable&\Cake\ORM\Association\HasMany $Likes
 * @property \App\Model\Table\NotificationsTable&\Cake\ORM\Association\HasMany $Notifications
 * @property \App\Model\Table\PostsTable&\Cake\ORM\Association\HasMany $Posts
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Trimmer');

        $this->hasMany('Comments', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Followers', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Likes', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Notifications', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Posts', [
            'foreignKey' => 'user_id',
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
            ->requirePresence('username', 'create', __('Username is required'))
            ->notEmptyString('username', __('Username is required'))
            ->scalar('username', __('Username should be scalar'))
            ->alphaNumeric('username', __('Alphanumeric characters only'))
            ->lengthBetween('username', [6, 20], __('6 to 20 characters only'))
            ->add('username', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Username already exists'
                ]
            ]);

        $validator
            ->requirePresence('email', 'create', __('Email is required'))
            ->notEmptyString('email', __('Email is required'))
            ->email('email', false, __('Invalid email'))
            ->maxLength('email', 255, __('Up to 255 characters only'))
            ->add('email', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Email already exists'
                ]
            ]);

        $validator
            ->requirePresence('mobile', 'create', __('Mobile is required'))
            ->notEmptyString('mobile', __('Mobile is required'))
            ->integer('mobile', __('Mobile should only contain number'))
            ->maxLength('mobile', 50, __('Up to 50 characters only'));

        $validator
            ->requirePresence('first_name', true, __('First name is required'))
            ->notEmptyString('first_name', __('First name is required'))
            ->scalar('first_name', __('First name should be scalar'))
            ->maxLength('first_name', 70, __('Up to 70 characters only'))
            ->add('first_name', [
                'lettersOnly' => [
                    'rule' => ['custom', '/^[^%#\/*@!0-9]+$/'],
                    'message' => 'Letters only'
                ]
            ]);

        $validator
            ->requirePresence('last_name', true, __('Last name is required'))
            ->notEmptyString('last_name', __('Last name is required'))
            ->scalar('last_name', __('Last name should be scalar'))
            ->maxLength('last_name', 35, __('Up to 35 characters only'))
            ->add('last_name', [
                'lettersOnly' => [
                    'rule' => ['custom', '/^[^%#\/*@!0-9]+$/'],
                    'message' => 'Letters only'
                ]
            ]);

        $validator
            ->requirePresence('birthdate', true, __('Birthdate is required'))
            ->notEmptyDateTime('birthdate', __('Birthdate is required'))
            ->date('birthdate');

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
            ->requirePresence('country', 'create', __('Country is required'))
            ->notEmptyString('country', __('Country is required'))
            ->scalar('country')
            ->maxLength('country', 90, __('Up to 90 characters only'));

        $validator
            ->requirePresence('zipcode', 'create', __('Zipcode is required'))
            ->notEmptyString('zipcode', __('Zipcode is required'))
            ->scalar('zipcode')
            ->maxLength('zipcode', 18, __('Up to 18 characters only'));

        $validator
            ->scalar('activation_key')
            ->maxLength('activation_key', 255)
            ->allowEmptyString('activation_key');

        $validator
            ->scalar('is_activated')
            ->maxLength('is_activated', 255)
            ->allowEmptyString('is_activated');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create', __('Password is required'))
            ->notEmptyString('password', __('Password is required'));

        $validator
            ->requirePresence('confirm_password', 'create', __('Confirm password is required'))
            ->notEmptyString('confirm_password', __('Confirm password is required'))
            ->add('confirm_password', 'no-misspelling', [
                'rule' => ['compareWith', 'password'],
                'message' => 'Password confirmation does not match password'
            ]);

        return $validator;
    }

    /**
     * Adds a new user
     * defaults
     *  is_activated as false
     *  avatar_url as null
     * 
     * @param $data - User data object
     * @return bool
     */
    public function addUser(array $data)
    {
        $user = $this->newEntity($data);
        $errors = $user->getErrors();
        if ($errors) {
            throw new ValidationErrorsException($user);
        }
        if ( ! $this->save($user)) {
            throw new InternalErrorException();
        }

        return $user;
    }

    /**
     * Activates the user
     * Updates the is_activated as true account column on Users table
     * 
     * @param string $key - The activation key to match the user
     * 
     * @return bool
     * 
     * @throws UserNotFoundException - If user with given activation key is not found
     * @throws InternalErrorException - Db errors
     */
    public function activateAccount(string $key)
    {
        $user = $this
            ->find()
            ->where(['activation_key' => $key])
            ->first();

        if ( ! $user) {
            throw new UserNotFoundException(0);
        }
        $user->is_activated = b'1';
        if ( ! $this->save($user)) {
            throw new InternalErrorException();
        }
        return true;
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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
