<?php 

namespace entities;

class Task
{
	protected $name;
	protected $mail;
	protected $title;
	protected $content;

	public function getName()
	{
		return $this->name;
	}

	public function getMail()
	{
		return $this->mail;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getContent()
	{
		return $this->content;
	}



	public function setName()
	{
		$this->name = $name;
		return $this;
	}

	public function setMail()
	{
		$this->mail = $mail;
		return $this;
	}

	public function setTitle()
	{
		$this->title = $title;
		return $this;
	}

	public function setContent()
	{
		$this->content = $contens;
		return $this;
	}

}
?>