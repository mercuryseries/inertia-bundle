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

### Step 2: Webpack or Vite

Choose [`symfony/webpack-encore-bundle`](https://symfony.com/doc/current/frontend.html) if you want to use [Webpack](https://webpack.js.org/) or [`pentatrion/vite-bundle`](https://github.com/lhapaipai/vite-bundle) if you want to use [Vite](https://vitejs.dev/):

```shell
# Webpack
$ composer require symfony/webpack-encore-bundle

# Vite
$ composer require pentatrion/vite-bundle
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

### Step 4: Choose your stack

```shell
# React without SSR support
$ symfony console inertia:install react

# React with SSR support
$ symfony console inertia:install react --ssr

# Vue without SSR support
$ symfony console inertia:install vue

# Vue with SSR support
$ symfony console inertia:install vue --ssr
```

### Step 5: Install packages and compile assets

```shell
# Install packages
$ npm install

# Build
$ npm run dev

# Start coding into assets/js/pages/ 🎉
```

### Start/Stop the SSR Server

```shell
# First you need to build client and server bundles
$ npm run build

# To start the SSR Server
$ symfony console inertia:start-ssr

# To stop the SSR Server
$ symfony console inertia:stop-ssr
```
