Array Path
==========
Array Path is an easy and very convenient way for manipulating arrays, especially multidimensional.<br>
Forget about checking for existing indexes and/or getting an E_NOTICE.<br>

With ArrayPath you can easily Check, Add, Remove and Retrieve elements from any array

Our examples will be using a class alias which will be explained next

Using Class Alias
-----------------
The default class alias is `A` but you can also define your custom alias.

By using the default alias you get the benefits of the ide auto-completion


```php
<?php
// recommended
ArrayPath::registerClassAlias();

A::get($aData, 'a/b/c');

// or

ArrayPath::registerClassAlias('MyAlias');
MyAlias::get($aData, 'a/b/c');

```

A good place to register the class alias is in any bootstrap file like a Service Provider or initialisation script

Example 1 (Get)
---------
```php
<?php
$post = array(
	'user' => array(
	    'basicInformation' => array(
	        'name'    => 'Mathias',
	        'surname' => 'Grimm'
	    ),
	)
);

// normal php way
$sName    = isset($post['user']['basicInformation']['name'   ]) ? $post['user']['basicInformation']['name'   ] : null;
$sSurname = isset($post['user']['basicInformation']['surname']) ? $post['user']['basicInformation']['surname'] : null;

// default value
$sLocale = isset($post['user']['locale']) ? $post['user']['locale'] : 'Europe/Dublin';

// ===================================================================

// ArrayPath
$sName    = A::get($post, 'user/basicInformation/name');
$sSurname = A::get($post, 'user/basicInformation/surname');

// with default value
$sLocale  = A:get($post, 'user/locale', 'Europe/Dublin');

```

Example 2 (Set)
---------------
```php
<?php
// normal php way
$aUser = array();
$sName = $aUser['user']['basicInformation']['name'] = 'Mathias Grimm';
// ===================================================================

// ArrayPath 
$aUser = array();
$sName = A::set($aUser, 'user/basicInformation/name', 'Mathias');
```

Example 3 (Exists)
------------------
The exists checks for the existence of the index as in array_key_exists<br>
and returns true if the key exists regardless the value.<br>
An isset will return false in case of a null value.

```php
<?php
// normal php way
$bExists = false;
if (array_key_exists('user', (array) $aUser)) {
	if (array_key_exists('basicInformation', (array) $aUser['user'])) {
		if (array_key_exists('name', (array) $aUser['user']['basicInformation'])) {
			$bExists = true;
		}
	}
}

// ===================================================================

// ArrayPath 
$bExists = A::exists($aUser, 'user/basicInformation/name');
```

Example 4 (Get and Remove)
--------------------------
```php
<?php
// normal php way
if (isset($aUser['user']['basicInformation']['name'])) {
	$sName = $aUser['user']['basicInformation']['name'];
	unset($aUser['user']['basicInformation']['name']);
}


// ArrayPath
$sName = A::remove($aUser, 'user/basicInformation/name');
```

Example 5 (Using a custom separator) 
------------------------------------
```php
<?php
ArrayPath::setSeparator('.');
$sName = A::get($aUser, 'user.basicInformation.name');

ArrayPath::setSeparator('-');
$sName = A::get($aUser, 'user-basicInformation-name');

ArrayPath::setSeparator('->');
$sName = A::get($aUser, 'user->basicInformation->name');

ArrayPath::setSeparator('|');
$sName = A::get($aUser, 'user|basicInformation|name');
```

Composer/Packagist
=========
https://packagist.org/packages/mathiasgrimm/arraypath

<pre>
"require": {
    "mathiasgrimm/arraypath": "2.*"
}
</pre>
