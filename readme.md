## Get started

[Documentation](https://documenter.getpostman.com/view/4237127/RWgtTcsH).
<p>Installation</p>

Run the following command in terminal:

<p>Clone the App repository</p>
<p>`git clone git@bitbucket.org:St_Flitty/dynaris_test.git`</p>

<p>Open project Dir</p>
<p>`cd dynaris_test`</p>

<p>Copy environment configuration file</p>
<p>`cp .env.example .env`</p>

<p>Edit environment config file</p>
<p>`nano .env`</p>
- Fields need to set up.

- APP_NAME=LaravelTestApp.
- APP_ENV=prod
- APP_URL={your url}
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE={database name}
- DB_USERNAME={user owner}
- DB_PASSWORD={password}
<p>Install PHP packages and dependensies</p>
<p>`composer install`</p>

<p>Generate app key:</p>
<p>`php artisan key:gen`</p>

<p>Install JS packages and dependensies using one of that command:</p>
- If you use Yarn

- `yarn`
- `yarn prod`
<p></p>
- If you use NPM

- `npm install`
- `npm run prod`

<p>Create symlink for media files</p>
<p>`php artisan storage:link`</p>

<p>Create database tables</p>
<p>`php artisan migrate`</p>
