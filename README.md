# Daily Catch Fish Shop - E-Commerce Platform

Fresh seafood e-commerce application built with **Laravel 12 (PHP 8.5)**, dynamic 3KM location radius calculation, customer mobile OTP authentication, custom cutting styles, and admin dashboard.

---

## 🌟 Key Features

1. **3KM Delivery Location Radius Enforcement**:
   - Built-in Haversine formula calculation for East Tambaram shop (`12.9249, 80.1278`).
   - Auto GPS detection & Nominatim OpenStreetMap address geocoding.
   - Hard disabling order placement if customer location > 3KM.

2. **Customer Mobile OTP Authentication**:
   - Instant 10-digit phone verification.
   - Saved delivery addresses management (`Home`, `Work`, `Other`).

3. **Custom Seafood Cutting Styles**:
   - Whole Fish Cleaned (Free)
   - Curry Cut (Free)
   - Boneless Fillet (+₹20)
   - Steak Cut (Free)

4. **Live Weight Transparency Guarantee**:
   - Fish selected & weighed live in store; COD payment workflow post-weighing.

---

## 🚀 How to Deploy Live (Free / Low Cost)

Netlify and Cloudflare Pages only host static frontends and cannot run PHP or database sessions. We have configured **Docker** for 1-click cloud hosting on **Render.com** or **Railway.app**:

### Option 1: Deploy on Render.com (Recommended Free Hosting)

1. Push this project code to **GitHub**.
2. Sign up at [Render.com](https://render.com/).
3. Click **New +** -> **Web Service**.
4. Connect your GitHub repository `dailycatch-fishstore`.
5. Render will automatically detect the included `Dockerfile` and `render.yaml`.
6. Click **Create Web Service**.
7. Render will build the container, seed the database automatically, and generate your live URL (e.g. `https://dailycatch-fishstore.onrender.com`)!

---

### Option 2: Deploy on Railway.app

1. Sign up at [Railway.app](https://railway.app/).
2. Click **New Project** -> **Deploy from GitHub repo**.
3. Select `dailycatch-fishstore`.
4. Railway will build the `Dockerfile` automatically and provide your live production domain!

---

## 💻 Local Development Setup

```bash
# 1. Install Dependencies
composer install
npm install

# 2. Database Migration & Seeding
php artisan migrate:fresh --seed

# 3. Serve Locally
php artisan serve --port=8080
```
