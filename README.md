# vk api #

[![Latest Stable Version](https://poser.pugx.org/mrsuh/vk-api/v/stable)](https://packagist.org/packages/mrsuh/vk-api)
[![Total Downloads](https://poser.pugx.org/mrsuh/vk-api/downloads)](https://packagist.org/packages/mrsuh/vk-api)
[![License](https://poser.pugx.org/mrsuh/vk-api/license)](https://packagist.org/packages/mrsuh/vk-api)

Symfony API Bundle for social network vk.com

## Installation ##

First you must create your application at vk.com's [applications page] (https://vk.com/apps?act=manage)

Add the vk-api package to your require section in the composer.json file.

```bash
composer require mrsuh/vk-api:1.*
```

Add parameters to your app/config.yml file

```yml
mrsuh_vk_api:
    app_id: '58384343' # application id
    username: 'mrsuh6@gmail.com' # username
    password: '1Gw738hfud9828hf3XbSrQ3' # password
    scope: ['video', 'friends', 'messages'] # list of permissions
    version: 5.50
```

Add the MrsuhVkApiBundle to your application's kernel:

``` php
<?php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Mrsuh\VkApiBundle\MrsuhVkApiBundle(),
        // ...
    );
    ...
}
```

## Using ##

Now you can call api methods


```php

$this->get('mrsuh_vk_api')->call('messages.get', ['scope' => 3]))
```

helpful links:
* [application page] (https://vk.com/apps?act=manage)
* [permissions] (https://vk.com/dev/permissions)
* [error codes] (https://vk.com/dev/errors)
* [api methods] (https://vk.com/dev/methods)