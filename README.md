Enhanced Symfony adapter for Inertia.js
=========================================

This is an enhanced Inertia.js server-side adapter based on [rompetomp/inertia-bundle](https://github.com/rompetomp/inertia-bundle).

Installation
============

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

### Step 3: Choose your stack

Add `--ssr` to any of the following options to add SSR support:

```shell
# React + Webpack Encore
$ symfony console inertia:install react --webpack

# React + Vite
$ symfony console inertia:install react --vite

# Vue + Webpack Encore
$ symfony console inertia:install vue --webpack

# Vue + Vite
$ symfony console inertia:install vue --vite
```

### Step 4: Routes Configurations

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

### Step 5: Install packages and compile assets

```shell
# Install npm and run:
$ npm install

# Start the development server:
$ npm run dev-server

# Start coding into assets/js/pages/ 🎉
```

### Production

```shell
# First you need to build both client and server bundles
$ npm run build

# Start/Stop the SSR Server
$ symfony console inertia:start-ssr
$ symfony console inertia:stop-ssr
```
