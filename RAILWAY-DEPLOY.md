# Railway Deployment Guide

## 🚂 Deploy to Railway

### Step 1: Create Railway Account
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Authorize Railway to access your repositories

### Step 2: Create New Project
1. Click "New Project"
2. Select "Deploy from GitHub repo"
3. Choose: **mardy-cse/rscpl**
4. Railway will auto-detect Laravel

### Step 3: Add MySQL Database
1. In your project, click "New"
2. Select "Database" → "Add MySQL"
3. Railway will automatically create database and set environment variables

### Step 4: Configure Environment Variables
Click on your service → "Variables" tab → Add these:

```env
APP_NAME="Roller Shutter & Construction"
APP_ENV=production
APP_KEY=base64:your-key-will-be-generated
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=rollershutter14@gmail.com
MAIL_FROM_NAME="Roller Shutter & Construction"
```

**Important:** Railway will auto-fill MySQL variables (${{}}) - don't change them!

### Step 5: Generate APP_KEY
In Railway CLI or after first deploy:
```bash
railway run php artisan key:generate --show
```
Copy the generated key and update APP_KEY in variables.

### Step 6: Deploy
1. Railway will automatically deploy on push to `main` branch
2. First deployment takes 3-5 minutes
3. You'll get a URL like: `https://your-app.up.railway.app`

### Step 7: Post-Deployment Setup
Once deployed, open Railway shell:
```bash
railway run bash
```

Then run:
```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan optimize
```

### Step 8: Create Admin User
```bash
railway run php artisan tinker
```

Then:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@admin.com';
$user->password = bcrypt('your-secure-password');
$user->role = 'admin';
$user->save();
exit
```

### Step 9: Add Custom Domain (Optional)
1. Go to Settings → Domains
2. Click "Add Domain"
3. Enter your domain (e.g., rollershutter.com)
4. Update DNS records as shown
5. Update APP_URL in environment variables

## 📊 Railway Features

### Automatic Deployments
- Push to GitHub → Auto deploy
- Pull requests get preview deployments
- Rollback to previous versions anytime

### Database Backups
Railway doesn't have automatic backups. Consider:
1. Using Railway CLI to dump database weekly
2. Setting up Laravel Backup package
3. Exporting via phpMyAdmin on Railway

### Monitoring
- View logs in real-time
- Check resource usage
- Set up health checks

## 💰 Pricing
- **Free Tier**: $5 credit/month (good for testing)
- **Hobby**: $5/month + usage (unlimited projects)
- **Pro**: $20/month + usage (team features)

Your Laravel app will likely cost $10-15/month.

## 🐛 Troubleshooting

### Issue: "500 Error after deploy"
```bash
railway run php artisan optimize:clear
railway run php artisan migrate --force
```

### Issue: "Storage link not working"
```bash
railway run php artisan storage:link
```

### Issue: "Database connection failed"
Check that MySQL service is running and variables are set.

### Issue: "APP_KEY not set"
```bash
railway run php artisan key:generate --show
```
Then update APP_KEY in variables.

## 🔄 Update Deployment
Just push to GitHub:
```bash
git add .
git commit -m "Update"
git push origin main
```

Railway auto-deploys in 1-2 minutes.

## 📱 Access Admin Panel
Once deployed:
- Frontend: `https://your-app.up.railway.app`
- Admin: `https://your-app.up.railway.app/login`

Default admin credentials (create in tinker):
- Email: admin@admin.com
- Password: (your secure password)

---

## Quick Deploy Commands

```bash
# Connect to Railway CLI
npm i -g @railway/cli
railway login
railway link

# Run commands on Railway
railway run php artisan migrate
railway run php artisan optimize
railway run php artisan tinker

# View logs
railway logs

# Open in browser
railway open
```

---

**Need help?** Check Railway docs: https://docs.railway.app/
