# B.R.A.I.T.O.S.📓

### Bug Report and Issue Tracker Online System

Braitos is a REST API for managing tasks and projects.

## Installation
1. Clone this repository
```bash
git clone https://github.com/breemark/braitos.git
```

2. Use the package manager [composer](https://getcomposer.org/) to install all the dependencies.

```bash
composer install
```
3. Create a .env file with the correct configuration, you can copy from .env.example file provided
4. Generate a Key and a Json Web Token Secret

```bash
php artisan key:generate
php artisan jwt:secret

```
5. Migrate
```bash
php artisan migrate
```


6. For development start the server with:
```bash
php artisan serve
```
## License
[MIT](https://choosealicense.com/licenses/mit/)