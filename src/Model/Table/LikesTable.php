<?php
namespace App\Model\Table;

use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Likes Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PostsTable&\Cake\ORM\Association\BelongsTo $Posts
 *
 * @method \App\Model\Entity\Like get($primaryKey, $options = [])
 * @method \App\Model\Entity\Like newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Like[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Like|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Like saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Like patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Like[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Like findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LikesTable extends Table
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

        $this->setTable('likes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
        $rules->add($rules->existsIn(['post_id'], 'Posts'));

        return $rules;
    }

    /**
     * Fetch the liked post
     * 
     * @param int $userId - users.id - user that liked the post
     * @param int $postId - posts.id - post that was liked
     * 
     * @return \Cake\ORM\Query
     */
    public function fetchLiked(int $userId, int $postId)
    {
        return $this->find()
            ->where([
                'post_id' => $postId,
                'user_id' => $userId
            ]);
    }

    /**
     * Toggles like of a post
     * 
     * @param int $userId - users.id - user that liked/unliked the post
     * @param int $postId - posts.id - post that was liked/unliked
     * 
     * @return \App\Model\Entity\Like|null - Null if unliked the post else liked
     * 
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function toggleLike(int $userId, int $postId)
    {
        $like = $this->fetchLiked($userId, $postId)->first();

        if ($like) {
            if ( ! $this->delete($like)) {
                throw new InternalErrorException();
            }
            return;
        }

        $like = $this->newEntity();
        $like->user_id = $userId;
        $like->post_id = $postId;
        if ( ! $this->save($like)) {
            throw new InternalErrorException();
        }
        return $like;
    }
}
