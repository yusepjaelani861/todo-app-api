# Todo App API
[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

Framework Todo App Laravel

## Server Requirement
- PHP >=8.2
- Composer
- Node v20.3.1+
- MySQL / MongoDB / PostgreSQL 


## Installation

```
git clone https://github.com/yusepjaelani861/todo-app-api
```

After clone this repository, you must setup .env
```bash
cp .env.example .env
```

After that, please follow this instruction
```
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan jwt:secret
php artisan serve
```

## Usage
You can access this API in localhost is
```
http://localhost:8000
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)