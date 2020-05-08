# Backpack\Demo

Laravel BackPack's demo, which includes all Backpack packages.


![Example generated CRUD interface](https://backpackforlaravel.com/uploads/docs-4-0/getting_started/monster_crud_list_entries.png)


## How to Use

You can find the demo online at [demo.backpackforlaravel.com](https://demo.backpackforlaravel.com/admin), and play around. But some functionality is disabled, for security reasons (uploads, edits to users). If you want to run the demo without restrictions and/or make code edits and see how they're applied, you can install it on your own machine. See below.


## Install

1) Run in your terminal:

``` bash
git clone https://github.com/Webhosting4U/Laravel-demo.git backpack-demo
```

2) Set your database information in your .env file (use the .env.example as an example);

3) Run in your backpack-demo folder:
``` bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Usage 

1. Your admin panel is available at http://localhost/backpack-demo/admin
2. Login with email ```admin@example.com```, password ```admin```
3. [optional] You can register a different account, to check out the process and see your gravatar inside the admin panel. 
4. By default, registration is open only in your local environment. Check out ```config/backpack/base.php``` to change this and other preferences.

Note: Depending on your configuration you may need to define a site within NGINX or Apache; Your URL domain may change from localhost to what you have defined.

![Example generated CRUD interface](https://backpackforlaravel.com/uploads/docs-4-0/getting_started/tag_crud_list_entries.png)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@tabacitu.ro instead of using the issue tracker.

Please **[subscribe to the Backpack Newsletter](http://backpackforlaravel.com/newsletter)** so you can find out about any security updates, breaking changes or major features. We send an email every 1-2 months.

## Credits

- [Cristian Tabacitu][link-author]
- [All Contributors][link-contributors]

## License

Backpack is free for non-commercial use and 69 EUR/project for commercial use. Please see [License File](LICENSE.md) and [backpackforlaravel.com](https://backpackforlaravel.com/#pricing) for more information.


[link-author]: http://tabacitu.ro
[link-contributors]: ../../contributors

