## 🚀 Setup

### Installation
```
git clone https://github.com/zeeleluc/wheeldeal.git
cd wheeldeal
composer install
npm install
npm run build
```

### Configure environment
```
cp .env.example .env
php artisan key:generate
```

### Update .env with database settings
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wheeldeal
DB_USERNAME=root
DB_PASSWORD=
```

### Important for webhooks

- Create a public expose URL (e.g., using Laravel Valet share or ngrok) so the payment provider can reach the webhook.
- Then update `APP_URL` in your .env file to match this expose URL. For example:
```text
APP_URL=https://abc123.ngrok.io
```
This allows the payment provider webhook to reach the correct endpoint and test your local environment. The exact method for creating the expose URL can be chosen by the user.

### Database migration and seeder
```
php artisan migrate
php artisan db:seed
```

### Administrator
Open your .env file and review the `ADMIN_EMAIL` and `ADMIN_PASSWORD` values. If you want, you can change them to your preferred email and password, then reseed the database. Once done, you can log in with these credentials to access the admin account.

## 🧪 Running Tests

Run the PHPUnit tests:
```
php artisan test
```

## 📱 Usage

Step 1: Choose rental dates and number of passengers.

Step 2: Select a car (up to 5 cars shown, sorted by cheapest price).

Step 3: Review and confirm the reservation.