# CakePHP-Auth-UserEntity

[![Latest Version](https://img.shields.io/github/release/loadsys/CakePHP-Auth-UserEntity.svg?style=flat-square)](https://github.com/loadsys/CakePHP-Auth-UserEntity/releases)
[![Build Status](https://img.shields.io/travis/loadsys/CakePHP-Auth-UserEntity/master.svg?style=flat-square)](https://travis-ci.org/loadsys/CakePHP-Auth-UserEntity)
[![Coverage Status](https://img.shields.io/coveralls/loadsys/CakePHP-Auth-UserEntity/master.svg?style=flat-square)](https://coveralls.io/r/loadsys/CakePHP-Auth-UserEntity)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/loadsys/cakephp-auth-userentity.svg?style=flat-square)](https://packagist.org/packages/loadsys/cakephp-auth-userentity)

A CakePHP 3 plugin that enhances Cake's stock AuthComponent to provide a userEntity() method.


## Requirements

* CakePHP 3.0.0+
* PHP 5.6+


## Installation

Pull the plugin into your project using composer:

```bash
$ composer require loadsys/cakephp-auth-userentity:~1.0
```

To use this plugin in your app, you must override the stock AuthComponent with the one from this plugin. This is typically done in `AppController::initialize()`:

```php
$this->loadComponent('Auth', [
	/**
	 * Name the plugin's Component as the class to use. This is **required**
	 * in order to use the plugin.
	 */
	'className' => 'AuthUserEntity.UserEntityAuth',

	/**
	 * Name the Entity class that will be used as the container for the
	 * array data in Auth->user(). Defaults to `\Cake\ORM\Entity`, which
	 * is safe for all apps, but will exclude any custom logic you may
	 * have defined in your app's "user" Entity class.
	 */
	'entityClass' => '\App\Model\Entity\User',

	/**
	 * Any options to pass to the new Entity when it is created. The defaults
	 * are:
	 *
	 *   [
	 *       'markClean' => true,    // Force the Entity to appear clean.
	 *       'source' => 'AuthUser', // The repository this record originated from.
	 *                               // We default to a fake name to make it clear
	 *                               // the Entity doesn't represent a "true" ORM
	 *                               // record.
	 *   ]
	 */
	'entityOptions' => [
		'associated' => ['Permissions', 'Groups'],
	],

	/**
	 * (The rest of your normal Auth configs follow here.)
	 */
	// ...
]);
```

## Usage

Once installed, you will be able to retrieve a User entity from your controllers like so:

```php
	// Get the whole entity:
	$user = $this->Auth->userEntity();

	// Or to get a specific property (which will engage any
	// _getProperty() methods you have defined in your Entity class):
	$userEmail = $this->Auth->userEntity('email');

	// Internally, the Entity::get() interface is used, so you can pass
	// nested keys (but remember that this data must be loaded into the
	// Auth session data!):
	$groupName = $this->Auth->userEntity('group.name');

	// Or to call a function from your entity:
	if (!$this->Auth->userEntity()->isAdmin()) {
		$this->Flash->error('Only admins can use this method.');
		return $this->redirect('/');
	}
```

Like the stock AuthComponent's `::user()` method, `null` will be returned whenever there is no authenticated User data available in the Session. It's also important to remember that **only** the data that is saved into the Auth session will be available in the Entity, but you can use [lazy loading in the Entity class](http://book.cakephp.org/3.0/en/orm/entities.html#lazy-loading-associations) to fetch additional properties as needed or [adjust the find query](http://book.cakephp.org/3.0/en/controllers/components/authentication.html#customizing-find-query) used to authenticate users so associated data is available in the Auth session.

Also keep in mind that currently **only** the top-level data is [marshaled](http://book.cakephp.org/3.0/en/orm/saving-data.html#converting-request-data-into-entities), so sub-properties will exist only as arrays and not Entities themselves.


## Contributing

### Reporting Issues

Please use [GitHub Isuses](https://github.com/loadsys/CakePHP-Auth-UserEntity/issues) for listing any known defects or issues.

### Development

When developing this plugin, please fork and issue a PR for any new development.

## License

[MIT](https://github.com/loadsys/CakePHP-Auth-UserEntity/blob/master/LICENSE.md)


## Copyright

[Loadsys Web Strategies](http://www.loadsys.com) 2015
