<?php
namespace App\Model\Table;

use App\Exception\PostNotFoundException;
use App\Exception\ValidationErrorsException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Posts Model
 *
 * @property \App\Model\Table\RetweetPostsTable&\Cake\ORM\Association\BelongsTo $RetweetPosts
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $Comments
 * @property \App\Model\Table\LikesTable&\Cake\ORM\Association\HasMany $Likes
 * @property \App\Model\Table\NotificationsTable&\Cake\ORM\Association\HasMany $Notifications
 *
 * @method \App\Model\Entity\Post get($primaryKey, $options = [])
 * @method \App\Model\Entity\Post newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Post[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Post[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PostsTable extends Table
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

        $this->setTable('posts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Trimmer');

        $this->belongsTo('RetweetPosts', [
            'foreignKey' => 'retweet_post_id',
            'className' => 'Posts',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'post_id',
        ]);
        $this->hasMany('Likes', [
            'foreignKey' => 'post_id',
        ]);
        $this->hasMany('Notifications', [
            'foreignKey' => 'post_id',
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
            ->scalar('title', __('Title should be scalar'))
            ->maxLength('title', 30, __('Up to 30 characters only'))
            ->allowEmptyString('title');

        $validator
            ->scalar('body', __('Body should be scalar'))
            ->maxLength('body', 140, __('Up to 140 characters only'))
            ->requirePresence('body', true, __('Body is required'))
            ->notEmptyString('body', __('Body is required'));

        $validator
            ->scalar('img_path')
            ->maxLength('img_path', 255)
            ->allowEmptyString('img_path');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        return $validator;
    }

    /**
     * Adds a post entity to the database
     * 
     * @param array $data - requestData
     * 
     * @return \App\Model\Entity\Post
     * 
     * @throws \App\Exception\ValidationErrorsException
     * @throws \Cake\Http\Exception\InternalErrorException - Did not save to db
     */
    public function addPost(array $data)
    {
        $post = $this->newEntity($data, [
            'fields' => ['title', 'body', 'img_path', 'user_id']
        ]);
        if ($post->hasErrors()) {
            throw new ValidationErrorsException($post);
        }
        if ( ! $this->save($post)) {
            throw new InternalErrorException();
        }
        return $post;
    }

    /**
     * Updates a post
     * 
     * @param integer $postId - posts.id - Post to be updated
     * @param array $data - Post Entity
     * 
     * @return array - status and Post Enitity
     * 
     * @throws \App\Exception\PostNotFoundException
     * @throws \App\Exception\ValidationErrorsException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function updatePost(int $postId, array $data)
    {
        $post = $this->get($postId);
        if ( ! $post) {
            throw new PostNotFoundException($postId);
        }

        $this->patchEntity($post, $data, [
            'fields' => ['title', 'body', 'img_path', 'user_id']
        ]);
        
        if ($post->hasErrors()) {
            throw new ValidationErrorsException($post);
        }

        if ( ! $this->save($post)) {
            throw new InternalErrorException();
        }

        return $post;
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
        $rules->add($rules->existsIn(['retweet_post_id'], 'RetweetPosts'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
