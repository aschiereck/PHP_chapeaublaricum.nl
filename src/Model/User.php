<?php

namespace App\Model;

use App\Util\Encryption\IPasswordEncryption;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="email_uix", columns={"email"})}))
 * @ORM\HasLifecycleCallbacks
 */
class User extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $roleId;


    /**
     * This field is only filled then there is a new password set
     * @var string
     */
    private $unHashedPassword = null;

    /**
     * @return array
     */
    protected function __validate()
    {
        $invalidFields = array();

        $nameValidation = new ValidatorChain();
        $nameValidation
            ->attach(new NotEmpty());

        $emailValidation = new ValidatorChain();
        $emailValidation
            ->attach(new NotEmpty())
            ->attach(new EmailAddress());

        $passWordValidation = new ValidatorChain();
        $passWordValidation
            ->attach(new NotEmpty());

        $newPassWordValidation = new ValidatorChain();
        $newPassWordValidation
            ->attach(new NotEmpty())
            ->attach(new StringLength(array('min' => 6)));

        $roleValidation = new ValidatorChain();
        $roleValidation
            ->attach(new NotEmpty());

        if(!$nameValidation->isValid($this->name))
        {
            $invalidFields['name'] = $nameValidation->getMessages();
        }
        if(!$emailValidation->isValid($this->email))
        {
            $invalidFields['email'] = $emailValidation->getMessages();
        }
        if(!$roleValidation->isValid($this->roleId))
        {
            $invalidFields['role'] = $roleValidation->getMessages();
        }
        if(!$passWordValidation->isValid($this->password))
        {
            $invalidFields['password'] = $passWordValidation->getMessages();
        }
        //Do password validation for new users, or when unHashedPassword is set
        if((!$this->id || !is_null($this->unHashedPassword)) && !$newPassWordValidation->isValid($this->unHashedPassword))
        {
            $invalidFields['password'] = $newPassWordValidation->getMessages();
        }

        return $invalidFields;
    }


    #region getters / setters

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param IPasswordEncryption $encryption
     * @param string $password
     * @return User
     */
    public function setPassword(IPasswordEncryption $encryption, $password)
    {
        $this->unHashedPassword = $password;
        $this->password = $encryption->encrypt($password);
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }
}
