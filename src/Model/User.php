<?php
namespace Agere\User\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\Mail\Address\AddressInterface;
/**
 * Users
 */
//class Users implements AddressInterface {
class User {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	private $departmentId;

	/**
	 * @var integer
	 */
	private $supplierId;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $firstName;

	/**
	 * @var string
	 */
	private $lastName;

	/**
	 * @var string
	 */
	private $patronymic;

	/**
	 * @var string
	 */
	private $phone;

	/**
	 * @var string
	 */
	private $phoneWork;

	/**
	 * @var string
	 */
	private $phoneInternal;

	/**
	 * @var string
	 */
	private $post;

	/**
	 * @var \DateTime
	 */
	private $dateBirth;

	/**
	 * @var \DateTime
	 */
	private $dateEmployment;

	/**
	 * @var string
	 */
	private $photo;

	/**
	 * @var string
	 */
	private $notation;

	/**
	 * @var string
	 */
	private $showIndex;

	/**
	 * @var integer
	 */
	private $sendEmails;

	/**
	 * @var integer
	 */
	private $remove;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	//private $usersCity;
	private $cities;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	//private $usersRoles;
	private $roles;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $orderSale;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $orderSalePaid;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $callCenter;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $cart;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $files;

	/**
	 * Constructor
	 */
	public function __construct() {
		//$this->usersCity = new \Doctrine\Common\Collections\ArrayCollection();
		$this->usersRoles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->orderSale = new \Doctrine\Common\Collections\ArrayCollection();
		$this->orderSalePaid = new \Doctrine\Common\Collections\ArrayCollection();
		$this->callCenter = new \Doctrine\Common\Collections\ArrayCollection();
		$this->cart = new \Doctrine\Common\Collections\ArrayCollection();
		$this->files = new \Doctrine\Common\Collections\ArrayCollection();
		$this->cities = new \Doctrine\Common\Collections\ArrayCollection();
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set departmentId

	 *
*@param integer $departmentId
	 * @return User
	 */
	public function setDepartmentId($departmentId) {
		$this->departmentId = $departmentId;

		return $this;
	}

	/**
	 * Get departmentId
	 *
	 * @return integer
	 */
	public function getDepartmentId() {
		return $this->departmentId;
	}

	/**
	 * Set supplierId

	 *
*@param integer $supplierId
	 * @return User
	 */
	public function setSupplierId($supplierId) {
		$this->supplierId = $supplierId;

		return $this;
	}

	/**
	 * Get supplierId
	 *
	 * @return integer
	 */
	public function getSupplierId() {
		return $this->supplierId;
	}

	/**
	 * Set email

	 *
*@param string $email
	 * @return User
	 */
	public function setEmail($email) {
		$this->email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set password

	 *
*@param string $password
	 * @return User
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Set firstName

	 *
*@param string $firstName
	 * @return User
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * Get firstName
	 *
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * Set lastName

	 *
*@param string $lastName
	 * @return User
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * Get lastName
	 *
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * Set patronymic

	 *
*@param string $patronymic
	 * @return User
	 */
	public function setPatronymic($patronymic) {
		$this->patronymic = $patronymic;

		return $this;
	}

	public function getName() {
		return $this->getFirstName() . ' ' . $this->getPatronymic() . ' ' . $this->getLastName();
	}

	/**
	 * Get patronymic
	 *
	 * @return string
	 */
	public function getPatronymic() {
		return $this->patronymic;
	}

	/**
	 * Set phone

	 *
*@param string $phone
	 * @return User
	 */
	public function setPhone($phone) {
		$this->phone = $phone;

		return $this;
	}

	/**
	 * Get phone
	 *
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * Set phoneWork

	 *
*@param string $phoneWork
	 * @return User
	 */
	public function setPhoneWork($phoneWork) {
		$this->phoneWork = $phoneWork;

		return $this;
	}

	/**
	 * Get phoneWork
	 *
	 * @return string
	 */
	public function getPhoneWork() {
		return $this->phoneWork;
	}

	/**
	 * Set phoneInternal

	 *
*@param string $phoneInternal
	 * @return User
	 */
	public function setPhoneInternal($phoneInternal) {
		$this->phoneInternal = $phoneInternal;

		return $this;
	}

	/**
	 * Get phoneInternal
	 *
	 * @return string
	 */
	public function getPhoneInternal() {
		return $this->phoneInternal;
	}

	/**
	 * Set post

	 *
*@param string $post
     * @return User
	 */
	public function setPost($post) {
		$this->post = $post;

		return $this;
	}

	/**
	 * Get post
	 *
	 * @return string
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * Set dateBirth

	 *
*@param \DateTime $dateBirth
     * @return User
	 */
	public function setDateBirth($dateBirth) {
		$this->dateBirth = $dateBirth;

		return $this;
	}

	/**
	 * Get dateBirth
	 *
	 * @return \DateTime
	 */
	public function getDateBirth() {
		return $this->dateBirth;
	}

	/**
	 * Set dateEmployment

	 *
*@param \DateTime $dateEmployment
     * @return User
	 */
	public function setDateEmployment($dateEmployment) {
		$this->dateEmployment = $dateEmployment;

		return $this;
	}

	/**
	 * Get dateEmployment
	 *
	 * @return \DateTime
	 */
	public function getDateEmployment() {
		return $this->dateEmployment;
	}

	/**
	 * Set photo

	 *
*@param string $photo
     * @return User
	 */
	public function setPhoto($photo) {
		$this->photo = $photo;

		return $this;
	}

	/**
	 * Get photo
	 *
	 * @return string
	 */
	public function getPhoto() {
		return $this->photo;
	}

	/**
	 * Set notation

	 *
*@param string $notation
     * @return User
	 */
	public function setNotation($notation) {
		$this->notation = $notation;

		return $this;
	}

	/**
	 * Get notation
	 *
	 * @return string
	 */
	public function getNotation() {
		return $this->notation;
	}

	/**
	 * Set showIndex

	 *
*@param string $showIndex
     * @return User
	 */
	public function setShowIndex($showIndex) {
		$this->showIndex = $showIndex;

		return $this;
	}

	/**
	 * Get showIndex
	 *
	 * @return string
	 */
	public function getShowIndex() {
		return $this->showIndex;
	}

	/**
	 * Set sendEmails

	 *
*@param integer $sendEmails
     * @return User
	 */
	public function setSendEmails($sendEmails) {
		$this->sendEmails = $sendEmails;

		return $this;
	}

	/**
	 * Get sendEmails
	 *
	 * @return integer
	 */
	public function getSendEmails() {
		return $this->sendEmails;
	}

	/**
	 * Set remove

	 *
*@param integer $remove
	 * @return User
	 */
	public function setRemove($remove) {
		$this->remove = $remove;

		return $this;
	}

	/**
	 * Get remove
	 *
	 * @return integer
	 */
	public function getRemove() {
		return $this->remove;
	}


	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getCities() {
		return $this->cities;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $cities
	 * @return User
	 */
	public function setCities($cities) {
		$this->cities = $cities;

		return $this;
	}

	/**
	 * Add usersCity


*
*@param \Agere\User\Model\UsersCity $usersCity
	 * @return User
	 */
	/*public function addUsersCity(\Agere\Users\Model\UsersCity $usersCity)
	{
		trigger_error('Please use setCities()', E_USER_DEPRECATED);
		$this->usersCity[] = $usersCity;

		return $this;
	}*/
	/**
	 * Remove usersCity

	 *
*@param \Agere\User\Model\UsersCity $usersCity
	 */
	/*public function removeUsersCity(\Agere\Users\Model\UsersCity $usersCity)
	{
		trigger_error('Please use setCities()', E_USER_DEPRECATED);
		$this->usersCity->removeElement($usersCity);
	}*/
	/**
	 * Get usersCity
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	/*public function getUsersCity()
	{
		trigger_error('Please use setCities()', E_USER_DEPRECATED);
		return $this->usersCity;
	}*/
	/**
	 * Add usersRoles


*
*@param \Agere\User\Model\UsersRoles $usersRoles
	 * @return User
	 */
	/*public function addUsersRole(\Agere\Users\Model\UsersRoles $usersRoles)
	{
		$this->usersRoles[] = $usersRoles;

		return $this;
	}*/
	/**
	 * Remove usersRoles

	 *
*@param \Agere\User\Model\UsersRoles $usersRoles
	 */
	/*public function removeUsersRole(\Agere\Users\Model\UsersRoles $usersRoles)
	{
		$this->usersRoles->removeElement($usersRoles);
	}*/
	/**
	 * Get usersRoles
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	/*public function getUsersRoles()
	{
		return $this->usersRoles;
	}*/
	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $roles
	 * @return User
	 */
	public function setRoles($roles) {
		$this->roles = $roles;

		return $this;
	}


	/**
	 * Add orderSale

	 *
*@param \Agere\OrderSale\Model\OrderSale $orderSale
	 * @return User
	 */
	public function addOrderSale(\Agere\OrderSale\Model\OrderSale $orderSale) {
		$this->orderSale[] = $orderSale;

		return $this;
	}

	/**
	 * Remove orderSale
	 *
	 * @param \Agere\OrderSale\Model\OrderSale $orderSale
	 */
	public function removeOrderSale(\Agere\OrderSale\Model\OrderSale $orderSale) {
		$this->orderSale->removeElement($orderSale);
	}

	/**
	 * Get orderSale
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getOrderSale() {
		return $this->orderSale;
	}

	/**
	 * Add orderSalePaid

	 *
*@param \Agere\OrderSale\Model\OrderSalePaid $orderSalePaid
	 * @return User
	 */
	public function addOrderSalePaid(\Agere\OrderSale\Model\OrderSalePaid $orderSalePaid) {
		$this->orderSalePaid[] = $orderSalePaid;

		return $this;
	}

	/**
	 * Remove orderSalePaid
	 *
	 * @param \Agere\OrderSale\Model\OrderSalePaid $orderSalePaid
	 */
	public function removeOrderSalePaid(\Agere\OrderSale\Model\OrderSalePaid $orderSalePaid) {
		$this->orderSalePaid->removeElement($orderSalePaid);
	}

	/**
	 * Get orderSalePaid
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getOrderSalePaid() {
		return $this->orderSalePaid;
	}

	/**
	 * Add callCenter

	 *
*@param \Agere\CallCenter\Model\CallCenter $callCenter
	 * @return User
	 */
	public function addCallCenter(\Agere\CallCenter\Model\CallCenter $callCenter) {
		$this->callCenter[] = $callCenter;

		return $this;
	}

	/**
	 * Remove callCenter
	 *
	 * @param \Agere\CallCenter\Model\CallCenter $callCenter
	 */
	public function removeCallCenter(\Agere\CallCenter\Model\CallCenter $callCenter) {
		$this->callCenter->removeElement($callCenter);
	}

	/**
	 * Get callCenter
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getCallCenter() {
		return $this->callCenter;
	}

	/**
	 * Add cart

	 *
*@param \Agere\Cart\Model\Cart $cart
	 * @return User
	 */
	public function addCart(\Agere\Cart\Model\Cart $cart) {
		$this->cart[] = $cart;

		return $this;
	}

	/**
	 * Remove cart
	 *
	 * @param \Agere\Cart\Model\Cart $cart
	 */
	public function removeCart(\Agere\Cart\Model\Cart $cart) {
		$this->cart->removeElement($cart);
	}

	/**
	 * Get cart
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getCart() {
		return $this->cart;
	}

	/**
	 * Add files

	 *
*@param \Agere\Files\Model\Files $files
	 * @return User
	 */
	public function addFile(\Agere\Files\Model\Files $files) {
		$this->files[] = $files;

		return $this;
	}

	/**
	 * Remove files
	 *
	 * @param \Agere\Files\Model\Files $files
	 */
	public function removeFile(\Agere\Files\Model\Files $files) {
		$this->files->removeElement($files);
	}

	/**
	 * Get files
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getFiles() {
		return $this->files;
	}

	/*public function toString() {
		$string = '<' . $this->getEmail() . '>';
		$name   = $this->getName();
		if (null === $name) {
			return $string;
		}

		return $name . ' ' . $string;
	}*/

}
