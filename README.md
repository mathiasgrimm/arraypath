Array Path
==========
Array Path is an easy and very convenient way for manipulating arrays, especially multidimensional.<br>
Forget about checking for existing indexes and/or getting an E_NOTICE.<br>

With ArrayPath you can easily Check, Add, Remove and Retrieve elements from any array

Example 1 and Most Useful use (Get)
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
$sName    = ArrayPath::get('user/basicInformation/name'    , $post);
$sSurname = ArrayPath::get('user/basicInformation/surname' , $post);

// with default value
$sLocale  = ArrayPath::get('user/locale', $post, 'Europe/Dublin');

```

Example 2 (Set)
---------
```php
<?php
// normal php way
$aUser = array();
$sName = $aUser['user']['basicInformation']['name'] = 'Mathias Grimm';
// ===================================================================

// ArrayPath 
$aUser = array();
$sName = ArrayPath::set('Mathias', 'user/basicInformation/name', $aUser);
```

Example 3 (Exists)
---------
The exists checks for the existence of the index as in array_key_exists<br>
and returns true if the key exists regardless the value.<br>
An isset will return false in case of a null value.

```php
<?php
// normal php way
$bExists = false;
if (array_key_exists('user', (array)$aUser)) {
	if (array_key_exists('basicInformation', (array)$aUser['user'])) {
		if (array_key_exists('name', (array)$aUser['user']['basicInformation'])) {
			$bExists = true;
		}
	}
}

// ===================================================================

// ArrayPath 
$bExists = ArrayPath::exists('user/basicInformation/name', $aUser);
```

Example 4 (Remove)
==================
```php
<?php
// normal php way
if (isset($aUser['user']['basicInformation']['name'])) {
	$sName = $aUser['user']['basicInformation']['name'];
	unset($aUser['user']['basicInformation']['name']);
}


// ArrayPath
$sName = ArrayPath::remove('user/basicInformation/name', $aUser);
```

Example 5 (Using a custom separator)
==================
```php
<?php
ArrayPath::setSeparator('.');
$sName = ArrayPath::get('user.basicInformation.name', $aUser);

ArrayPath::setSeparator('-');
$sName = ArrayPath::get('user-basicInformation-name', $aUser);

ArrayPath::setSeparator('->');
$sName = ArrayPath::get('user->basicInformation->name', $aUser);

ArrayPath::setSeparator('|');
$sName = ArrayPath::get('user|basicInformation|name', $aUser);
```


