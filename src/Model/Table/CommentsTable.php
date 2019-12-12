<?php
namespace App\Model\Table;

use App\Exception\PostNotFoundException;
use App\Exception\ValidationErrorsException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Comments Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PostsTable&\Cake\ORM\Association\BelongsTo $Posts
 *
 * @method \App\Model\Entity\Comment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Comment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Comment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Comment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Comment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Comment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Comment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CommentsTable extends Table
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

        $this->setTable('comments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Trimmer');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Posts', [
            'foreignKey' => 'post_id',
            'joinType' => 'INNER',
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
            ->scalar('body')
            ->maxLength('body', 140, __('Up to 140 characters only'))
            ->requirePresence('body', true, __('Body is required'))
            ->notEmptyString('body', __('Body is required'));

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        return $validator;
    }

    /**
     * Fetches comments of the given post
     * 
     * @param int $postId - posts.id
     * @param int $page - page
     * @param int $perPage
     * 
     * @return \Cake\ORM\Query
     */
    public function fetchByPost(int $postId)
    {
        return $this->find()
            ->select(['Comments.id', 'Comments.body', 'Comments.created'])
            ->contain(['Users' => function ($q) {
                return $q->select(['id', 'first_name', 'last_name', 'username', 'avatar_url']);
            }])
            ->where(['post_id' => $postId])
            ->order(['Comments.created' => 'DESC']);
    }

    /**
     * Adds a comment to a post
     * 
     * @param int $postId - The post to be inserted with a comment
     * @param array $data - Data to be inserted
     * 
     * @return App\Model\Entity\Comment
     * 
     * @throws \App\Exception\PostNotFoundException
     * @throws \App\Exception\ValidationErrorsException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function addComment(int $postId, array $data)
    {
        if ( ! $this->Posts->exists(['id' => $postId])) {
            throw new PostNotFoundException($postId);
        }

        $comment = $this->newEntity($data);
        $comment->post_id = $postId;

        if ($comment->hasErrors()) {
            throw new ValidationErrorsException($comment);
        }

        if ( ! $this->save($comment)) {
            throw new InternalErrorException();
        }

        return $comment;
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
        $rules->add($rules->existsIn(['post_id'], 'Posts'));

        return $rules;
    }
}
