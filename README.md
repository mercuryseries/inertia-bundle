Enhanced Symfony adapter for Inertia.js
=========================================

This is an enhanced Inertia.js server-side adapter based on [rompetomp/inertia-bundle](https://github.com/rompetomp/inertia-bundle).

Installation
============

First, make sure you have the twig, encore and serializer recipe:

```console
$ composer require twig encore serializer
```

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require mercuryseries/inertia-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require mercuryseries/inertia-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    MercurySeries\Bundle\InertiaBundle\MercurySeriesInertiaBundle::class => ['all' => true],
];
```

### Step 3: Routes Configurations

Add the following route configuration if it's missing:

```diff
# config/routes.yaml

controllers:
    resource:
        path: ../src/Controller/
        namespace: App\Controller
    type: attribute
+   defaults:
+       csrf:
+           create: true
+           require:
+               - 'POST'
+               - 'PUT'
+               - 'PATCH'
+               - 'DELETE'
```

### Step 4: Setup

```shell
# If you use React
$ symfony console inertia:install react

# If you use Vue
$ symfony console inertia:install vue

# Install packages
$ npm install

# Build
$ npm run dev

# Start coding into assets/js/pages/ 🎉
```

### SSR Support

```shell
# If you use React
$ symfony console inertia:install react --ssr

# If you use Vue
$ symfony console inertia:install vue --ssr

# Build client and server bundles
$ npm run build

# Start SSR Server
$ symfony console inertia:start-ssr

# If you want to stop the SSR Server
$ symfony console inertia:stop-ssr
```
