<?php

namespace repositories;

use entities\Post;

class PostRepository
{
  static private $postTable = 'articles';

  static public function getAllPosts($app, $date = null)
  {
    $posts = Array();
    $table = self::$postTable;

    $allPostsArray = $app['db']->fetchAll("SELECT * FROM $table order by created desc" );

    foreach ($allPostsArray as $onePostArray)
    {
      $onePost = new Post($app);
      $onePost->setTitle($onePostArray['title'])
              ->setCreateDate($onePostArray['created'])
              ->setAuthor("Antonio Jiménez")
              ->setContent($onePostArray['content'])
              ->setId($onePostArray['id']);
      array_push($posts, $onePost);
    }
  return $posts;
  }
}
