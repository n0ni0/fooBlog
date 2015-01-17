<?php

namespace entities;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class Users
{
  protected $id;
  protected $username;
  protected $password;
  protected $roles;
  protected $name;
  protected $surnames;
  protected $mail;
  static private $userTable = 'users';

  public function __construct($app)
  {
     $this->db = $app['db']; 
  }

  public function setName($name)
  {
    $name = $this->name;
    return $this;
  }

  public function setSurnames($surnames)
  {
    $surnames = $this->surname;
    return $this;
  }

  public function setMail($mail)
  {
    $mail = $this->mail;
    return $this;
  }

  public function setId($id)
  {
    $id = $this->id;
    return $this;
  }

  public function setUserName($username)
  {
    $username = $this->username;
    return $this;
  }

  public function setPassword($password)
  {
    $password = $this->password;
    return $this;
  }

  public function setRole($roles)
  {
    $setRoles = $this->roles;
    return $this;
  }


  public function getName()
  {
    return $this->name;
  }

  public function getSurnames()
  {
    return $this->surnames;
  }

  public function getMail()
  {
    return $this->mail;
  }

  public function getID()
  {
    return $this->id;
  }

  public function getUserName()
  {
    return $this->userName;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getRoles()
  {
    return $this->roles;
  }



  public function addUser($form)
  {
    $table = self::$userTable;
    $numRows = $this->db->fetchAssoc("
      SELECT *
      FROM   $table
      WHERE  username = ? 
      OR     mail = ?",
       array($form['username'],
             $form['mail']));

    if($numRows)
    {
      throw new UsernameNotFoundException(sprintf('El usuario o el correo ya existe'));
    }
    $this->db->insert('users',$form);
  }


}

