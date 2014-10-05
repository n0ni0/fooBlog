<?php
	class PostRepository {

		static public function getPostById($postId) {
			$post = new Post();
			$post->getPostById($postId);

			return $post;
		}

		static public function getAllPosts($app, $date = null) {
			$posts = Array();

			$allPostsArray = $app['db']->fetchAll("SELECT * FROM entrada");

			foreach ($allPostsArray as $onePostArray) {
				$onePost = new Post();
				$onePost->setTitle($onePostArray['titile'])
						->setCreateDate($onePostArray['createDate'])
						->setAuthor($onePostArray['author']);

				array_push($posts, $onePost);

			}

			return $posts;
		}
	}