<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Entity\User;

use App\Entity\Customer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity(
 *     fields={
 *          "username"
 *     },
 *     message="user.username.unique_entity",
 *     groups={"registration"}
 * )
 *
 * @UniqueEntity(
 *     fields={
 *          "email"
 *     },
 *     message="user.email.unique_entity",
 *     groups={"registration"}
 * )
 */
class User implements AdvancedUserInterface, \Serializable, UserProviderInterface
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * Contains the ID of the user
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the user"
     *     }
     * )
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Contains the firstname of the person
     *
     * @var string $firstname
     *
     * @ORM\Column(
     *     name="firstname",
     *     type="string",
     *     length=80,
     *     nullable=true,
     *     options={"comment"="Contains the firstname of the person"}
     * )
     *
     * @Assert\Length(
     *     max=80,
     *     maxMessage="user.firstname.max_length"
     * )
     */
    protected $firstname;

    /**
     * Contains the lastname of the person
     *
     * @var string $lastname
     *
     * @ORM\Column(
     *     name="lastname",
     *     type="string",
     *     length=80,
     *     nullable=true,
     *     options={"comment"="Contains the lastname of the person"}
     * )
     *
     * @Assert\Length(
     *     max=80,
     *     maxMessage="user.lastname.max_length"
     * )
     */
    protected $lastname;

    /**
     * Contains the username (alias) of the person
     *
     * @var string
     *
     * @ORM\Column(
     *     name="username",
     *     type="string",
     *     length=60,
     *     nullable=false,
     *     unique=true,
     *     options={"comment"="Contains the username (alias) of the person"}
     * )
     *
     * @Assert\NotBlank(
     *     message="user.username.not_blank",
     *     groups={"registration", "resettingRequest"}
     * )
     *
     * @Assert\Length(
     *     max=60,
     *     maxMessage="user.username.max_length",
     *     groups={"registration","login"}
     * )
     */
    protected $username;


    /**
     * Contains the email address of the person
     *
     * @var string
     *
     * @ORM\Column(
     *     name="email",
     *     type="string",
     *     length=60,
     *     nullable=false,
     *     unique=true,
     *     options={"comment":"Contains the email address of the person"}
     * )
     *
     * @Assert\NotBlank(
     *     message="user.email.not_blank",
     *     groups={"registration"}
     * )
     *
     * @Assert\Email(
     *     message="user.email.not_valid",
     *     groups={"registration"}
     * )
     *
     * @Assert\Length(
     *     max=60,
     *     maxMessage="user.email.max_length"
     * )
     */
    protected $email;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     *
     * @ORM\Column(
     *     name="password",
     *     type="string",
     *     length=64,
     *     nullable=true,
     *     options={"comment"="Encrypted password. Must be persisted."}
     * )
     *
     * @Assert\Length(
     *     max=64,
     *     maxMessage="user.password.max_length"
     * )
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     *
     * @ORM\Column(
     *     name="plain_password",
     *     type="string",
     *     length=4096,
     *     nullable=true,
     *     options={"comment"="Plain password. Used for model validation. Must not be persisted."}
     * )
     *
     * @Assert\NotBlank(
     *     groups={"registration", "resettingReset"}
     * )
     *
     * @Assert\Length(
     *     max=4096,
     *     maxMessage="user.plainPassword.max_length",
     *     groups={"registration", "resettingReset"}
     * )
     */
    protected $plainPassword;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var null|string
     *
     * @ORM\Column(
     *     name="confirmation_token",
     *     type="string",
     *     length=255,
     *     nullable=true,
     *     options={"comment"="Contains a random string sent to the user email address in order to verify it."}
     *     )
     */
    protected $confirmationToken;

    /**
     * Date of registration
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="registered_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Date of registration"}
     * )
     *
     * @Assert\DateTime(
     *     message="user.registeredAt.not_validate"
     * )
     */
    protected $registeredAt;

    /**
     * Last update data
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="update_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Date of registration"}
     * )
     *
     * @Assert\DateTime(
     *     message="user.updatedAt.not_validate"
     * )
     */
    protected $updateAt;

    /**
     * user is locked ?
     *
     * @var boolean
     *
     * @ORM\Column (
     *     name="locked",
     *     type="boolean",
     *     nullable=false,
     *     options={"comment"="user is locked",
     *              "default"="0"
     *              }
     *     )
     */
    protected $locked;

    /**
     * user is active ?
     * Depending on whether the user's registration
     * has been validated or not (in the case of a confirmation by email for example)
     *
     * @var boolean
     *
     * @ORM\Column (
     *     name="is_active",
     *     type="boolean",
     *     nullable=false,
     *     options={"comment"="user is active",
     *              "default"="0"
     *              }
     *     )
     */
    protected $isActive = true;

    /**
     * Role of the user
     *
     * @var array
     *
     * @ORM\Column(
     *      name="role",
     *      type="array",
     *      nullable=false,
     *      options={"comment"="Roles of the user"}
     * )
     *
     * @Assert\Type("array")
     */
    protected $roles = [];

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $apiKey;

    /**
     * @var Customer
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Customer")
     *
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     *
     * @Assert\Valid()
     */
    protected $customer;

    /** *******************************
     *  CONSTRUCT
     */

    public function __construct()
    {
        $dateAt = new \DateTime();
        $this
            ->setRegisteredAt($dateAt)
            ->setUpdateAt($dateAt)
            ->setLocked(false);
    }

    /** *******************************
     *  GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param null|string $firstname
     *
     * @return User
     */
    public function setFirstname(?string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param null|string $lastname
     *
     * @return User
     */
    public function setLastname(?string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     *
     * @return User
     */
    public function setUsername(?string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     *
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param null|string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param null|string $confirmationToken
     *
     * @return User
     */
    public function setConfirmationToken(?string $confirmationToken): User
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt(): \DateTime
    {
        return $this->registeredAt;
    }

    /**
     * @param \DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt(\DateTime $registeredAt): User
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt(): \DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     *
     * @return User
     */
    public function setUpdateAt(\DateTime $updateAt): User
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     *
     * @return User
     */
    public function setLocked(bool $locked): User
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive(bool $isActive): User
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array The user roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     *
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }


    /** *******************************
     *  BEHAVIOR METHOD
     */

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        // TODO STUB to implement
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * LockedException
     */
    public function isAccountNonLocked()
    {
        // TODO STUB to implement
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        // TODO STUB to implement
        return true;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     */
    public function loadUserByUsername($username)
    {
        // TODO: Implement loadUserByUsername() method.
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return void
     *
     */
    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class): bool
    {
        // TODO: Implement supportsClass() method.
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * DisabledException
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * String representation of object
     * \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
                $this->isActive
            ]
        );
    }

    /**
     * Constructs the object
     * \Serializable::unserialize()
     *
     * @param $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            ) = unserialize($serialized);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }


    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return User
     */
    public function setCustomer(Customer $customer): User
    {
        $this->customer = $customer;
        return $this;
    }
}
