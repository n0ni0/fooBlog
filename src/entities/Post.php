<?php 

namespace entities;

class Post {
	protected $author = "";
	protected $createDate = "";
	protected $title = "";
	protected $content;

	public function __construct() {}

	public function setAuthor($authorName) { 
		$this->author = $authorName;
		return $this; 
	}

	public function setTitle($postTitle) { $this->title = $postTitle; return $this; }

	public function setCreateDate($createDate) { $this->createDate = $createDate; return $this; }

	public function setContent($content) { $this->content = $content; return $this; }

	public function getAuthor() { return $this->author; }

	public function getTitle() { return $this->title; }

	public function getCreateDate() { return $this->createDate; }

	public function getContent() { return $this->content; }

}