<?php 

namespace entities;

class Post {
	protected $id;
	protected $author = "";
	protected $createDate = "";
	protected $title = "";
	protected $content;
	protected $db = null;

	public function __construct($app, $id = null) {
		$this->db = $app['db'];
			
		if (!is_null($id)) {
			
			$postData = $this->db->fetchAll("SELECT * FROM entrada WHERE id = ?", array($id));

			$this->title = $postData[0]['titulo'];
			$this->content = $postData[0]['contenido'];
			$this->createDate = $postData[0]['creado'];
			$this->id = $postData[0]['id'];
		}
	}

	public function setAuthor($authorName) { 
		$this->author = $authorName;
		return $this; 
	}

	// --  setters -------------------
	public function setTitle($postTitle) { $this->title = $postTitle; return $this; }

	public function setCreateDate($createDate) { $this->createDate = $createDate; return $this; }

	public function setContent($content) { $this->content = $content; return $this; }

	public function setId($id) { $this->id = $id; return $this; }

	// -- getters ------------------------------

	public function getAuthor() { return $this->author; }

	public function getTitle() { return $this->title; }

	public function getCreateDate() { return $this->createDate; }

	public function getContent() { return $this->content; }

	public function getId() { return $this->id; }


	public function save() {
		$postData['titulo'] = $this->title;
		$postData['contenido'] = $this->content;

		if (!is_null($this->getId()) ) {
			$postData['id'] = $this->getId();
			$this->db->update('entrada', "id = ?", $postData);
		} else {
			$postData['creado'] = date("Y-m-d H:i:s");
			$this->db->insert('entrada', $postData);
		}
	}


	public function delete() {
		$this->db->delete('entrada', array('id' => $this->getId()));
	}

}