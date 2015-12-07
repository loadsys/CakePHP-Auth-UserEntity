<?php
/**
 * Sub-classes AuthComponent to provide an alterative to ::user() that
 * returns an entity instead of an array.
 */
namespace AuthUserEntity\Controller\Component;

use App\Model\Entity\User;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component\AuthComponent;

/**
 * Provides a convenience ::userEntity() method.
 */
class UserEntityAuthComponent extends AuthComponent {

	/**
	 * Default config.
	 *
	 * This array **supplements** Cake's default AuthComponent configs.
	 *
	 * - `entityClass` - The fully qualified namespace name of the Entity
	 *   class to use. Default is Cake\ORM\Entity.
	 *
	 *   ```
	 *   $this->Auth->config('entityClass', '\App\Model\Entity\User');
	 *   ```
	 *
	 * - `entityOptions` - An array of options to pass to the Entity
	 *   constructor. Default is to pass
	 *   [markClean => true, source => 'AuthUser']. This second argument
	 *   helps prevent the entity obtained from the ::authEntity() method
	 *   from getting saved back into the ORM. (The entity should be
	 *   treated as if it was read-only.)
	 *
	 *   ```
	 *   $this->Auth->config('entityOptions', [
	 *	   'source' => 'Users' // Alias of the Table this Entity originates from.
	 *   ]);
	 *   ```
	 *
	 *
	 * @var array
	 */
	protected $_defaultAdditionalConfig = [
		'entityClass' => '\Cake\ORM\Entity',
		'entityOptions' => [
			'markClean' => true,
			'source' => 'AuthUser',
		],
	];

	/**
	 * Constructor
	 *
	 * @param ComponentRegistry $registry A ComponentRegistry this component can use to lazy load its components
	 * @param array $config Array of configuration settings.
	 */
	public function __construct(ComponentRegistry $registry, array $config = []) {
		$this->_defaultConfig = $this->_defaultConfig + $this->_defaultAdditionalConfig;
		parent::__construct($registry, $config);
	}

	/**
	 * Get the current user from storage and return it as an Entity.
	 *
	 * Preserves the same interface as Auth->user(), so if a key is passed,
	 * only that value will be returned from the User record. The only way
	 * to get the full Entity is to call this method with zeron arguments.
	 *
	 * @param string $key Field to retrieve. Leave null to get a User Entity object.
	 * @return \Cake\ORM\Entity|mixed|null Either User Entity or null if no user is logged in.
	 */
	public function userEntity($key = null) {
		$user = $this->user();
		if (!$user) {
			return null;
		}

		$entityClass = $this->config('entityClass');
		$user = new $entityClass($user, $this->config('entityOptions'));

		if (!is_null($key)) {
			return $user->get($key);
		}

		return $user;
	}
}
