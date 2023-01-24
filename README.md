
## About Laravel DDScanner

Laravel DDScanner is a laravel console package that scan and remove instances of dd() and ddd() function within a laravel project.Laravel DDScanner is built on top of laravel command and it utilises the power of the elegant laravel console.

## Motivation

This package is inspired by an unforunate event, dd() slipped it way into production and all hell broke loose. The weekend after I have to create an artisan command in the CI/CD that can help prevent dd() from slipping into production. Long story short the command is now a package.




## Installation

To get the latest version of Laravel-ddscanner on your project, require it from "composer":

```bash
  $ composer require salamikola/laravel-ddscanner
```
    
Or you can add it directly in your composer.json file:

```bash
 {
    "require": {
        "salamikola/laravel-ddscanner": "1.0*"
    }
}
```

## Laravel
Register the provider directly in your app configuration file config/app.php:

```
'providers' => [
	// ...

	Salamikola\LaravelDDScanner::class,
]
```
## Usage

Go to the your project root terminal and enter the command below

```
php artisan dd:scanner

```
This will by default look for the app folder in your laravel root and scan recursively to the last file.

If you want to specify your starting path you can define it by adding the --path to the command

```
php artisan dd:scanner --path=C:\laragon\www\weekreed\app\Services

```

If you wish the scanner to tranverse just the top level without scanning the sub-directories you should add the --t flag

```
php artisan dd:scanner --path=C:\laragon\www\weekreed\app\Http --t

```
You wish to have more control over the recursive level you should make use of the --rl option

```
php artisan dd:scanner --path=C:\laragon\www\weekreed\app\Http --rl=2

```
The scanner will stop at the level 2 sub folder
NOTE: rl means recursive level, it's not advisable to use --t and --rl= together

Because a typical laravel project can contain different file extensions, e.g .php, .blade.php e.t.c. You can specify the type of file you want to scan using the --ext option


```
php artisan dd:scanner --ext=php --ext=blade.php

```
NOTE : --ext option can take in multiple values

You would rather have your dd() commented out instead of being deleted, make use of the --comment flag

```
php artisan dd:scanner --comment

```

After all the razzmatas, you want to see those files that tested dd positive, you can use the --s flag to show all files that were affected by the scan

```
php artisan dd:scanner --s

```

You can view all options and their description using the --help option

```
php artisan dd:scanner --help

```

# NOTE

Having dd() in your providers might halt the command, due to the fact that laravel boots your providers when starting the application.

If you are not a fan packages like me you can look up on my <a href="https://weekreed.com">webspace</a> where I talked about the alternatives you can consider

## Authors

- [@salamikola](https://www.github.com/salamikola)


## License

[MIT](https://choosealicense.com/licenses/mit/)


## 🚀 About Me
You can catch me goofing around on my webspace www.weekreed.com


## Acknowledgements

 - [The Laravel Team / Ecosysytem](https://laravel.com)
 - [Vale App](https://vale.ng)

 