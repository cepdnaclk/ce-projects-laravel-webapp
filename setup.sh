
# Copy env file
echo "Setup env file with defaults..."
php -r "file_exists('.env') || copy('.env.example', '.env');"

# install the required modules
echo "Installing dependencies..."
composer install

# generate key
php artisan key:generate

# setup permission
chmod -R 777 app/storage bootstrap/cache

# Adding auth options
composer require laravel/ui 2.1
php artisan ui vue --auth
npm run development


echo "Installing NPM packages"
npm install
npm run development
# npm run production

# echo "Creating the database : sqlite"
# mkdir -p database
# touch database/database.sqlite

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
