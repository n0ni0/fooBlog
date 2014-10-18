<?php

	namespace repositories;

	use entities\Post;

	class PostRepository {

		static public function getPostById($postId) {
			$post = new Post();
			$post->getPostById($postId);

			return $post;
		}

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

			$allPostsArray = $app['db']->fetchAll("SELECT * FROM entrada");

			foreach ($allPostsArray as $onePostArray) {
				$onePost = new Post();
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

		// ---------------------------------------------------------------------------------------
		static public function deletePostById($postId, $app)
		{
			$post = $app['db']->delete('entrada', array('id' => $postId));

			return $post;
		}

		// ---------------------------------------------------------------------------------------
		static public function editPostByID($postId, $app)
		{
			$post = $app['db']->fetchAssoc('SELECT * FROM entrada WHERE id = ?', array($postId));

			return $post;
		}
}