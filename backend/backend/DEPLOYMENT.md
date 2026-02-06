# 🚀 Deployment Guide - Railway.app

**Somali Police System** - Free Hosting iyo Domain

---

## 📋 Prerequisites

Waxaad u baahan tahay:
- ✅ GitHub account (free)
- ✅ Railway account (free - https://railway.app)
- ✅ Git installed on your computer

---

## Step 1: Create GitHub Repository

### 1.1 Initialize Git (haddii aadan samayn)

```bash
cd c:\xampp\htdocs\Somali-Police-System\backend
git init
git add .
git commit -m "Initial commit - Somali Police System"
```

### 1.2 Create GitHub Repository

1. Go to https://github.com/new
2. Repository name: `somali-police-system`
3. Set to **Private** (recommended for police system)
4. Click "Create repository"

### 1.3 Push to GitHub

```bash
git remote add origin https://github.com/YOUR-USERNAME/somali-police-system.git
git branch -M main
git push -u origin main
```

---

## Step 2: Setup Railway Account

### 2.1 Sign Up

1. Go to https://railway.app
2. Click "Start a New Project"
3. Sign up with GitHub account
4. Authorize Railway to access your repositories

### 2.2 Create New Project

1. Click "New Project"
2. Select "Deploy from GitHub repo"
3. Choose `somali-police-system` repository
4. Railway will automatically detect it's a PHP/Laravel project

---

## Step 3: Add MySQL Database

### 3.1 Add Database Service

1. In your Railway project, click "+ New"
2. Select "Database"
3. Choose "MySQL"
4. Railway will create a MySQL database automatically

### 3.2 Get Database Credentials

Railway automatically creates these environment variables:
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`

These are already configured in your `.env.production` file!

---

## Step 4: Configure Environment Variables

### 4.1 Add APP_KEY

1. In Railway project, go to your service
2. Click "Variables" tab
3. Add new variable:
   - Name: `APP_KEY`
   - Value: Run this command locally to generate:
     ```bash
     php artisan key:generate --show
     ```
   - Copy the output (starts with `base64:`)

### 4.2 Add APP_URL

1. After first deployment, Railway gives you a URL like:
   `https://somali-police-system-production.up.railway.app`
2. Add variable:
   - Name: `APP_URL`
   - Value: Your Railway URL

### 4.3 Optional: Configure Email

If you want forgot password to work, add:
- `MAIL_MAILER=smtp`
- `MAIL_HOST=smtp.gmail.com`
- `MAIL_PORT=587`
- `MAIL_USERNAME=your-email@gmail.com`
- `MAIL_PASSWORD=your-app-password`
- `MAIL_ENCRYPTION=tls`

---

## Step 5: Deploy Application

### 5.1 Automatic Deployment

Railway automatically deploys when you push to GitHub!

```bash
git add .
git commit -m "Add deployment configuration"
git push origin main
```

### 5.2 Watch Deployment

1. Go to Railway dashboard
2. Click on your service
3. Go to "Deployments" tab
4. Watch the build logs

### 5.3 Run Migrations

After successful deployment:

1. In Railway, go to your service
2. Click "Settings" tab
3. Scroll to "Deploy Command"
4. Add: `php artisan migrate --force`

Or use Railway CLI:

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link to project
railway link

# Run migrations
railway run php artisan migrate --force
```

---

## Step 6: Import Database Data

### 6.1 Export Local Database

```bash
# In your local machine
cd c:\xampp\htdocs\Somali-Police-System\backend

# Export database
mysqldump -u root somali_police_db > database-export.sql
```

### 6.2 Import to Railway

Option 1: Using Railway CLI

```bash
railway connect MySQL
# Then paste SQL commands or use source command
```

Option 2: Using MySQL client

```bash
# Get Railway database credentials from Variables tab
mysql -h MYSQLHOST -P MYSQLPORT -u MYSQLUSER -p MYSQLDATABASE < database-export.sql
```

---

## Step 7: Get Your Free Domain

### 7.1 Railway Domain (Automatic)

Railway automatically provides:
`https://your-app-production.up.railway.app`

### 7.2 Custom Domain (Optional - Free)

**Option 1: Freenom (Free .tk, .ml, .ga domains)**

1. Go to https://www.freenom.com
2. Search for available domain (e.g., `somalipolice.tk`)
3. Register for free (12 months)
4. In Railway:
   - Go to Settings → Domains
   - Click "Custom Domain"
   - Enter your Freenom domain
   - Add CNAME record in Freenom DNS settings

**Option 2: Use Railway Subdomain**

Railway gives you: `somali-police-system.up.railway.app` (free forever)

---

## Step 8: Testing

### 8.1 Access Your Application

Visit your Railway URL:
`https://your-app.up.railway.app/login`

### 8.2 Test Features

- ✅ Login page loads
- ✅ Login with credentials works
- ✅ Dashboard accessible
- ✅ All modules working
- ✅ Database connection working
- ✅ Forgot password (if email configured)

---

## 🔧 Troubleshooting

### Issue: 500 Error

**Solution:**
1. Check Railway logs: Deployments → View Logs
2. Make sure `APP_KEY` is set
3. Run migrations: `railway run php artisan migrate --force`

### Issue: Database Connection Error

**Solution:**
1. Verify MySQL service is running in Railway
2. Check environment variables are set correctly
3. Make sure `.env.production` uses Railway variables

### Issue: Assets Not Loading

**Solution:**
1. Make sure `APP_URL` is set correctly
2. Clear cache: `railway run php artisan config:clear`

---

## 📊 Railway Free Tier Limits

- ✅ **$5 free credit** per month
- ✅ **500 hours** of usage
- ✅ **100 GB** bandwidth
- ✅ **1 GB** RAM per service
- ✅ **Unlimited** projects
- ✅ **Free SSL** certificate
- ✅ **Free subdomain**

This is enough for testing and small deployments!

---

## 🔄 Continuous Deployment

Every time you push to GitHub, Railway automatically:
1. Pulls latest code
2. Installs dependencies
3. Runs build commands
4. Deploys new version

```bash
# Make changes
git add .
git commit -m "Update feature"
git push origin main

# Railway automatically deploys!
```

---

## 📝 Important Notes

> [!WARNING]
> **Security**: Make sure to:
> - Set `APP_DEBUG=false` in production
> - Use strong `APP_KEY`
> - Keep database credentials secret
> - Set proper CORS settings

> [!IMPORTANT]
> **Database Backups**: Railway doesn't auto-backup free tier databases. Export regularly:
> ```bash
> railway run mysqldump > backup-$(date +%Y%m%d).sql
> ```

---

## 🎉 Success!

Hadda waxaad haysataa:
- ✅ Free hosting on Railway.app
- ✅ Free domain (Railway subdomain)
- ✅ Automatic deployments from GitHub
- ✅ MySQL database
- ✅ SSL certificate (HTTPS)

**Your application URL:**
`https://somali-police-system.up.railway.app`

Mahadsanid! 🚀
