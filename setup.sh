
# install the required modules
composer install

echo "Installing dependencies..."

# generate key
php artisan key:generate

# setup permission
chmod -R 777 app/storage

# Adding auth options
composer require laravel/ui 2.1
php artisan ui vue --auth
npm run development


echo "Installing NPM packages"
npm install
npm run development
# npm run production

# echo "Creating the database : sqlite"
# touch ./database/database.sqlite

php artisan migrate

###
# Done and Clean up
###
echo "Ready to start server..."

# start laravel server
# php artisan serve


# Additional setup commands for optimizations
# composer install --optimize-autoloader --no-dev
# php artisan config:cache
# php artisan route:cache
# php artisan route:cache
