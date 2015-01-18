<?php

namespace users;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\DBAL\Connection;

class userProvider implements UserProviderInterface
{
  private $conn;
  static private $userTable = "users";

  public function __construct(Connection $conn)
  {
    $this->conn = $conn;
  }

  public function loadUserByUsername($username)
  {
    $table = self::$userTable;
    $stmt = $this->conn->executequery("SELECT * FROM $table WHERE username = ?", array(strtolower($username)));

    if(!$user = $stmt->fetch())
    {
      throw new UsernameNotFoundException(sprintf('El usuario "%s" no existe', $username));
    }
    return new User($user['username'], $user['password'], explode(',', $user['roles']), true, true, true, true);
  }

  public function refreshUser(UserInterface $user)
  {
    if(!$user instanceof User)
    {
    throw new UnsupportedUserException(sprintf('La instancia de "%s no está soportada.', get_class($user)));
    }
    return $this->loadUserByUsername($user->getUsername());
  }

  public function supportsClass($class)
  {
    return $class === 'Symfony\Component\Security\Core\User\User';
  }

}

