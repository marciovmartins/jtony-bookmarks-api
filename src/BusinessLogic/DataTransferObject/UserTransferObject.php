<?php

namespace BusinessLogic\DataTransferObject;

use Silex\Application;

use Symfony\Component\Validator\Constraints as Assert;

class UserTransferObject extends BaseTransferObject {

	protected $id;
	protected $name;
	protected $email;
	protected $nick;
	protected $password;

	public function __construct(Application $app) {
		parent::__construct($app);

		//VALIDATION CONSTRAINTS
		$emailValidation = array(new Assert\NotBlank(), new Assert\Email());
		$passwordValidation = array(new Assert\NotBlank(), new Assert\Length(array('min' => 4)));

		$constraintAuthenticate = new Assert\Collection(array(
			'email' => $emailValidation,
			'password' => $passwordValidation,
		));

		$constraintCreate =  new Assert\Collection(array(
			'name' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 10))),
			'nick' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
			'email' => $emailValidation,
			'password' => $passwordValidation
		));


		$this->validateActions['authenticate'] = array(
													'fields' => array('email', 'password'),
													'constraint' => $constraintAuthenticate
												);

		$this->validateActions['create'] = array(
													'fields' => array('name', 'email', 'nick', 'password'),
													'constraint' => $constraintCreate
												);
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getNick() {
		return $this->nick;
	}

	public function setNick($nick) {
		$this->nick = $nick;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function toArray() {
		return array(
				'id'=>$this->id,
				'name'=>$this->name,
				'email'=>$this->email,
				'nick'=>$this->nick
			);
	}

}
