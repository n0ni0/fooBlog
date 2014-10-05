<?php 
class Post {
	protected $author = "";
	protected $createDate = "";
	protected $title = "";

	public function __construct() {}

	public function setAuthor($authorName) { 
		$this->author = $authorName;
		return $this; 
	}

	public function setTitle($postTitle) { $this->title = $postTitle; return $this; }

	public function setCreateDate($createDate) { $this->createDate = $createDate; return $this; }

}