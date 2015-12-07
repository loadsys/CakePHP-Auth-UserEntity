<?php
/**
 * Test the customized AuthComponent class.
 */
namespace AuthUserEntity\Test\TestCase\Controller\Component;

use AuthUserEntity\Controller\Component\UserEntityAuthComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\TestSuite\TestCase;

/**
 * \App\Test\TestCase\Controller\Component\UserEntityAuthComponentTest
 */
class UserEntityAuthComponentTest extends TestCase {
	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
	];

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$registry = new ComponentRegistry(new Controller());
		$this->Component = $this->getMock(
			'AuthUserEntity\Controller\Component\UserEntityAuthComponent',
			['user'],
			[$registry]
		);
	}

	/**
	 * Shortcut to mock the Component's internal ::user() method to return
	 * the provided value.
	 *
	 * @return void
	 */
	protected function mockUser($ary = []) {
		$this->Component->expects($this->any())
			->method('user')
			->willReturn($ary);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Component);
		parent::tearDown();
	}

	/**
	 * Test userEntity() with no user loaded.
	 *
	 * @return void
	 */
	public function testUserEntityEmpty() {
		$this->mockUser(null);

		$this->assertNull(
			$this->Component->userEntity(),
			'The returned value with no key provided should be null.'
		);
		$this->assertNull(
			$this->Component->userEntity('id'),
			'The returned value with a key provided should be null.'
		);
	}

	/**
	 * Test userEntity() with no key provided.
	 *
	 * @return void
	 */
	public function testUserEntityNoKey() {
		$user = [
			'id' => 4,
			'email' => 'foo@bar.com',
		];
		$this->mockUser($user);

		$result = $this->Component->userEntity();

		$this->assertInstanceOf(
			'\Cake\ORM\Entity',
			$result,
			'The returned object should be a User entity.'
		);
		foreach ($user as $property => $expected) {
			$this->assertEquals(
				$expected,
				$result->{$property},
				'The returned entity should contain the property values from the loaded User array.'
			);
		}
	}

	/**
	 * Test userEntity() with a key provided.
	 *
	 * @return void
	 */
	public function testUserEntityKeyed() {
		$user = [
			'id' => 4,
			'email' => 'foo@bar.com',
		];
		$this->mockUser($user);

		$key = 'email';

		$this->assertEquals(
			$user[$key],
			$this->Component->userEntity($key),
			'The returned value should match the value of the same name from the stored User array.'
		);
	}
}
