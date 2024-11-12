## Laravel Cashier (Stripe)

Implementing Laravel Cashier (Stripe) in your Laravel application is easy. First, install the package via Composer:

```bash
sail composer require laravel/cashier
```

Next, publish the Cashier configuration file:

```bash
sail artisan vendor:publish --tag="cashier-migrations"
```

Then, run the migration to create the necessary tables:

```bash
sail artisan migrate
```
Add trait in User Model

```php
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Billable;
}
```

Add Stripe Keys in .env file

```bash
STRIPE_KEY=your-stripe-key
STRIPE_SECRET=your-stripe-secret
```

Add Stripe Keys in Cashier Config

```php
'stripe_key' => env('STRIPE_KEY'),
'stripe_secret' => env('STRIPE_SECRET'),
```

Go to site: 
- [In developers mode take APIkeys](https://dashboard.stripe.com/test/apikeys).

Paste Publishable key to STRIPE_KEY and Secret key to STRIPE_SECRET.

Add MIX_STRIPE_KEY to .env so you could access key from frontend, but not directly.
```php
MIX_STRIPE_KEY="${STRIPE_KEY}"
```

### Stripe JS

From this page you can implement scripts and modules for payment on frontend.
But we are using Inertia and Vue 3, so we will skip this page and go to project on GitHub.
- [Stripe JS](https://docs.stripe.com/js)

To install module go to GitHub stripe/stripe-js
- [Project on GitHub](https://docs.stripe.com/js)

Use npm to install the Stripe.js module:
```bash
sail npm install @stripe/stripe-js
```

After installation you can use it in your Vue 3 project.
```js
import {loadStripe} from '@stripe/stripe-js';
```

You can now use the Cashier package to handle subscription billing and manage customer information in your Laravel application.

