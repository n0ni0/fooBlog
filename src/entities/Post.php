<?php 

namespace entities;

class Post {
  protected $id;
  protected $author = "";
  protected $createDate = "";
  protected $title = "";
  protected $content;
  protected $db = null;
  static private $postTable = 'articles';


  public function __construct($app, $id = null) {
    $this->db = $app['db'];
    $table = self::$postTable;

    if (!is_null($id)) {

    $postData = $this->db->fetchAll("SELECT * FROM $table WHERE id = ?", array($id));

    $this->title = $postData[0]['title'];
    $this->content = $postData[0]['content'];
    $this->createDate = $postData[0]['created'];
    $this->id = $postData[0]['id'];
    }
  }

  public function setAuthor($authorName) { 
    $this->author = $authorName;
    return $this; 
  }


  public function setTitle($postTitle) { $this->title = $postTitle; return $this; }

  public function setCreateDate($createDate) { $this->createDate = $createDate; return $this; }

  public function setContent($content) { $this->content = $content; return $this; }

  public function setId($id) { $this->id = $id; return $this; }


  public function getAuthor() { return $this->author; }

  public function getTitle() { return $this->title; }

  public function getCreateDate() { return $this->createDate; }

  public function getContent() { return $this->content; }

  public function getId() { return $this->id; }


  public function save() {
    $postData['title'] = $this->title;
    $postData['content'] = $this->content;

    if (!is_null($this->getId()) ) 
    {
      $postData['id'] = $this->getId();
      $this->db->update('articles', "id = ?", $postData);
    } else {
    $postData['created'] = date("Y-m-d H:i:s");
    $this->db->insert('articles', $postData);
    }
  }


  public function delete() {
    $this->db->delete('articles', array('id' => $this->getId()));
  }


  public function editPostByID($id)
  {
   $table = self::$postTable;
   $post = $this->db->fetchAssoc("SELECT * FROM $table WHERE id = ?", array($id));
   return $post;
  }

  public function redirectToBackend()
  {
    new RedirectResponse($app['url_generator']->generate('backend'));
  }

}
