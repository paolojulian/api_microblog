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
            'foreignKey' => 'following_id',
            'className' => 'Followers'
        ]);
        $this->hasMany('Following', [
            'foreignKey' => 'user_id',
            'className' => 'Followers'
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
        $this->hasOne('Addresses')
            ->setProperty('address');
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
     * 
     * @return \App\Model\Entity\User
     * 
     * @throws ValidationErrorsException - When there are invalid parameters
     * @throws InternalErrorException - Db failed to save
     */
    public function addUser(array $data)
    {
        $user = $this->newEntity($data, [
            'fields' => [
                'username',
                'email',
                'mobile',
                'first_name',
                'last_name',
                'birthdate',
                'password',
                'address',
            ],
            'associated' => ['Addresses' => ['validate' => true]]
        ]);
        if ($user->hasErrors()) {
            throw new ValidationErrorsException($user);
        }
        if ( ! $this->save($user, ['associated' => ['Addresses']])) {
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
            throw new UserNotFoundException();
        }
        $user->is_activated = b'1';
        if ( ! $this->save($user)) {
            throw new InternalErrorException();
        }
        return true;
    }

    /**
     * Fetches activated users by the given username
     * 
     * @param string $username - Username of the user
     * 
     * @return \Cake\ORM\Query
     * 
     * @throws \App\Exception\UserNotFoundException
     */
    public function fetchByUsername(string $username)
    {
        $query = $this->find()
            ->where([
                'username' => $username,
                'is_activated' => true
            ]);

        if ($query->isEmpty()) {
            throw new UserNotFoundException();
        }

        return $query;
    }

    /**
     * The user containing
     * follower count
     * following count
     * and basic user info
     * 
     * @param string $username - Username of the user
     * 
     * @return \App\Model\Entity\User
     */
    public function fetchUserProfile(string $username)
    {
        $user = $this->fetchByUsername($username);
        $user = $this->commonFields($user);

        return $user->first();
    }

    /**
     * Toggles the follow a user
     * 
     * @param int $userId - users.id - The user to follow
     * @param int $followingId - users.id - The user to be followed
     * 
     * @return \App\Model\Entity\Follower|null - Null if unfollowed else Entity
     * 
     * @throws \App\Exception\UserNotFoundException - If the following user is not found
     * @throws \Cake\Http\Exception\InternalErrorException - DB Error
     */
    public function toggleFollowUser(int $userId, int $followingId)
    {
        if ( ! $this->exists(['id' => $followingId])) {
            throw new UserNotFoundException();
        }

        $followEntity = $this->Following->fetchFollowing($userId, $followingId)->first();

        if ($followEntity) {
            // Delete if already followed
            if ( ! $this->delete($followEntity)) {
                throw new InternalErrorException();
            }
            return;
        }

        // Add new entity if not yet followed
        $followEntity = $this->newEntity();
        $followEntity->user_id = $userId;
        $followEntity->following_id = $followingId;
        if ( ! $this->save($followEntity)) {
            throw new InternalErrorException();
        }

        return $followEntity;
    }

    /**
     * Fetches all posts that will be displayed in the landing page
     * 
     * TODO take unique posts and check for followed users who took
     * 
     * @param int $userId
     * @param int $page
     * @param int $perPage
     * 
     * @return array of \App\Model\Entity\Post
     */
    public function fetchPosts(int $userId, int $page = 1, int $perPage = 5)
    {
        return $this->find()
            ->select(['id', 'username', 'first_name', 'last_name', 'avatar_url'])
            ->where(['id' => $userId])
            ->contain([
                'Posts' => function ($q) use ($page, $perPage) {
                    return $q->contain([
                        'RetweetPosts' => function ($q) {
                            return $q->contain(['Users' => function ($q) {
                                return $q->select(['id', 'username', 'first_name', 'last_name', 'avatar_url']);
                            }]);
                        }
                    ])
                    ->order(['Posts__created' => 'desc'])
                    ->limit($perPage)
                    ->page($page);
                }
            ]);
    }

    /**
     * Gets the common fields used
     * id,
     * username,
     * avatar_url,
     * first_name,
     * last_name
     * 
     * @param \Cake\ORM\Query
     * 
     * @return \Cake\ORM\Query
     */
    public function commonFields(Query $query)
    {
        return $query->select([
                'Users.id',
                'Users.username',
                'Users.first_name',
                'Users.last_name',
                'Users.avatar_url'
            ]);

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
