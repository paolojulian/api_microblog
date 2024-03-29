<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PostsFixture
 */
class PostsFixture extends TestFixture
{
    public $import = ['table' => 'posts'];
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'title' => '20002 Post',
                'body' => 'Lorem Ipsum',
                'retweet_post_id' => null,
                'user_id' => 200002,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 2,
                'title' => '20002 Post',
                'body' => 'Lorem Ipsum',
                'retweet_post_id' => null,
                'user_id' => 200001,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 3,
                'title' => '20002 Post',
                'body' => 'Lorem Ipsum',
                'retweet_post_id' => null,
                'user_id' => 200012,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 4,
                'title' => '',
                'body' => 'This is a shared post',
                'retweet_post_id' => 1,
                'user_id' => 200001,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 5,
                'title' => 'Test',
                'body' => 'For Searching',
                'retweet_post_id' => null,
                'user_id' => 200001,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 6,
                'title' => 'Test',
                'body' => 'For Searching 2',
                'retweet_post_id' => null,
                'user_id' => 200001,
                'img_path' => null,
                'created' => '2019-11-23 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 7,
                'title' => 'Title Search',
                'body' => 'Haha!',
                'retweet_post_id' => null,
                'user_id' => 200001,
                'img_path' => null,
                'created' => '2019-11-24 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 8,
                'title' => '',
                'body' => 'wont show this post onsearch',
                'retweet_post_id' => 7,
                'user_id' => 200001,
                'img_path' => null,
                'created' => '2019-11-24 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 9,
                'title' => '',
                'body' => 'wont show this post onsearch',
                'retweet_post_id' => '',
                'user_id' => 200002,
                'img_path' => null,
                'created' => '2019-11-24 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 10,
                'title' => 'ForEditing',
                'body' => 'Lorem Ipsum',
                'retweet_post_id' => null,
                'user_id' => 200002,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 11,
                'title' => 'PostOfOtherUser',
                'body' => 'Lorem Ipsum',
                'retweet_post_id' => null,
                'user_id' => 900023,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 12,
                'title' => 'NotLiked',
                'body' => 'Lorem Ipsum',
                'retweet_post_id' => null,
                'user_id' => 200002,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
            [
                'id' => 13,
                'title' => 'Liked',
                'body' => 'Lorem Ipsum',
                'retweet_post_id' => null,
                'user_id' => 20002,
                'img_path' => null,
                'created' => '2019-11-22 09:35:10',
                'modified' => '2019-11-22 09:35:10',
                'deleted' => null
            ],
        ];
        parent::init();
    }
}
