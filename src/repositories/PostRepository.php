<?php

	namespace repositories;

	use entities\Post;

	class PostRepository {

		static private $postTable = 'entrada';

		/**
		* Get all stored posts
		* 
		* $author: 	Antonio Jiménez
		* $params:  $app: app type
		*			$date: DateTime
		*
		* $return: 	$posts: Array[Posts]
		**/
		static public function getAllPosts($app, $date = null) {
			$posts = Array();
      		$table = self::$postTable;

			$allPostsArray = $app['db']->fetchAll("SELECT * FROM '$table' order by creado asc" );

			foreach ($allPostsArray as $onePostArray) {
				$onePost = new Post($app);
				$onePost->setTitle($onePostArray['titulo'])
						->setCreateDate($onePostArray['creado'])
						->setAuthor("Antonio Jiménez")
						->setContent($onePostArray['contenido'])
						->setId($onePostArray['id']);
				// Inserta uno o más elementos al final de un array    $array[] = $var;
				array_push($posts, $onePost);

			}

			return $posts;
		}

}
