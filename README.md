# OdooConnectorBundle

A Symfony Client for Odoo using Ripcord RPC library (as used in Odoo Web API docs)


## Installation

* Download OdooConnectorBundle using [composer]()

```composer
composer require sofia-holding/odoo-connector-bundle
```
* Enable the Bundle
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
            new \Odoo\ConnectorBundle\OdooConnectorBundle(),
        // ...
    );
}
```

* Add the following configuration to your parameters.yml
```yml
# app/config/parameters.yml


    url_odoo:  'example.odoo.com'
    db_odoo: 'example-database'
    username_odoo: 'user@email.com'
    password_odoo: 'yourpassword'
```
* Import OdooConnectorBundle routing.

```yml
# app/config/routing.yml
odoo_connector:
    resource: "@OdooConnectorBundle/Resources/config/routes.yaml"
```

## Usage

```php
# Create an instance of the odoo service
$odooService = $this->get('odoo_service');

# Read records        
$option[0] = array('id', '=', $id);
$option[1] = array('name', '=', "Partner Name");
$odooService->search('res.partner', $option);

# Create records       
$partner = array('name'=>"New Partner");
$odooService->create('res.partner', $partner);

# update records        
$option[0] = array('name'=>"New  Partner Name");
$odooService->update('res.partner',$id=7,$option);

# delete records        
$odooService->delete('res.partner',$id=7);
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.


