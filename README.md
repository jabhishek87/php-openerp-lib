# openerplib

openerplib is a library for PHP that allows operations with xml-rpc [OpenERP] (http://www.openerp.com/) comfortably.

Inspired from Openerp Client Liprary python

## Requeriments

Dependency (requirement)

* xmlrpc.inc >= 1.174 http://phpxmlrpc.sourceforge.net/ (incluida)

## Installation

It requires no special installation. Copy the / openerplib where being want to use and import to your php scri

## Configuration

Two forms of use.

### Configuring file / openerplib / openerplib.inc.php

```php
<?php
	define('_OPENERPLIB_BD_', '');
	define('_OPENERPLIB_UID_', 0);
	define('_OPENERPLIB_PASSWD_', '');
	define('_OPENERPLIB_URL_', 'http://openerp/xmlrpc');
?>
```

### On-live setting.

```php
<?php
	$config = array(
		'bd'        => 'mybdname',
		'uid'       => 1212,
		'passwd'    => 'foo',
		'url'       => 'http://openerp/xmlrpc',
	);
	
	$open = new OpenERP($config);
?>
```

## Usage

### Creating the factory object OpenERP

```php
<?php
	$open = new OpenERP();	// read config => openerlib.inc.php
?>
```

### Reading objects by object id.

```php
<?php
	// Read res.partner object with id 1 (only reads the 'id' property)
	$p = $open->res_partner->get(1);
	print $p->id;

	//  Read res.partner object with id 1 and some of its properties
	$p = $open->res_partner('name', 'active')->get(1);
	print $p->id;
	print $p->name . " ". $p->active;
	
	$p = $open->res_partner(array('name', 'active'))->get(1);
	print $p->id;
	print $p->name . " ". $p->active;
	
	// Read res.partner object with id 1, all properties
	$p = $open->res_partner('__ALL')->get(1);
	print $p->id;
	print $p->name . " ". $p->ref . " " . $p->vat;
?>
```
    
### Navigating many2one objects with OpenERP
	
```php
<?php
	$p = $open->res_partner('country')->get(1);
	print $p->id;
	print $p->country->id;	// many2one => res.country
	print $p->country('name')->name;
?>
```
	
### Navigating one2many objects with OpenERP

```php
<?php
	$p = $open->res_partner('departament_ids')->get(1);
	print "Departaments of " . $p->id; 
	foreach($p->departament_ids('name', 'address_id') as $d)	// res.partner.departament
		print $d->name . " " . $d->address_id->id;
?>
```
	
### searches

```php
<?php
	$fields = array('street', 'email');
	$results = $open->res_partner_address($fields)->search('email', '=', 'foo@bar.com');
	foreach ($results as $id => $address) {
		print "<h1>" . $id . "</h1>";
		print "<pre>" . $address->info() . "</pre>";
		print "<hr>";
	}
?>
```

### Navigation , editing and saving (Update record)

```php
<?php
	$p = $open->res_partner('name', 'active')->get(1);
	$p->name = 'FOO';
	$p->save();
?>
```

### Creating new record

```php
<?php
	$crm = $open->crm_case;
	$crm->name = 'TEST';
	$crm->section_id = 10;
	$crm->email_from = 'foo@bar.com';
	$id = $crm->save();
	print $id ? "<h1>OK: ".$id."</h1>" : "<h1>ERROR</h1><pre>". $crm->getError() . "</pre>";
?>
```


### run Methods

```php
<?php
	$crm = $open->crm_case->get(39806);
	$r = $crm->workflow('case_open');
	print "<pre>". print_r($r) . "</pre>";
?>
```

## Contact