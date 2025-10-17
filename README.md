## 🚀 Setup

```
git clone zeeleluc/wheeldeal
cd wheeldeal
composer install
npm install
npm run build
```

Configure environment:
```
cp .env.example .env
php artisan key:generate
```
Then update .env with your database settings.

Run database migrations and seed data:
```
php artisan migrate
php artisan db:seed
```

## 🧪 Running Tests

Run the PHPUnit tests:
```
php artisan test
```

## 📱 Usage

Step 1: Choose rental dates and number of passengers.

Step 2: Select a car (up to 5 cars shown, sorted by cheapest price).

Step 3: Review and confirm the reservation.