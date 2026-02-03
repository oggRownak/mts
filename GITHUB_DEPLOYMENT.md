# 🚀 GitHub Deployment Guide for MTS Hospital Website

## 📋 Overview

This guide will help you deploy the MTS Hospital website on GitHub and make it live. Since this is a PHP project, we'll cover multiple deployment options.

---

## ⚠️ Important Note About GitHub Pages

**GitHub Pages does NOT support PHP** - it only supports static HTML, CSS, and JavaScript files. 

However, you can still use GitHub to:
1. ✅ **Host your code** (repository)
2. ✅ **Deploy to PHP hosting services** (Heroku, InfinityFree, 000webhost, etc.)
3. ✅ **Set up CI/CD** (automatic deployment)
4. ✅ **Collaborate** with team members

---

## 🎯 Deployment Options

### Option 1: Free PHP Hosting (Recommended for Testing)

#### A. InfinityFree (Completely Free)
1. **Create Account**: Go to [infinityfree.net](https://infinityfree.net)
2. **Create Website**: Click "Create Account"
3. **Upload Files**: Use File Manager or FTP
   - Upload `index.php` to `htdocs` folder
   - Upload `test_language.php` to `htdocs` folder
4. **Access**: Your site will be at `yoursite.infinityfree.net`

#### B. 000webhost (Free Tier Available)
1. **Sign Up**: Visit [000webhost.com](https://www.000webhost.com)
2. **Create Website**: Follow setup wizard
3. **Upload Files**: Use built-in File Manager
4. **Done**: Access your site at provided URL

#### C. AwardSpace (Free)
1. **Register**: [awardspace.com](https://www.awardspace.com)
2. **Setup Hosting**: Select free plan
3. **Deploy**: Upload via FTP or File Manager
4. **Access**: Use provided subdomain

---

### Option 2: Cloud Platforms (Professional Deployment)

#### A. Heroku (Free Tier Available)
1. **Install Heroku CLI**:
   ```bash
   # On Mac
   brew tap heroku/brew && brew install heroku
   
   # On Ubuntu/Debian
   curl https://cli-assets.heroku.com/install.sh | sh
   ```

2. **Login to Heroku**:
   ```bash
   heroku login
   ```

3. **Create App**:
   ```bash
   cd /path/to/your/project
   heroku create mts-hospital-app
   ```

4. **Add Composer.json** (required by Heroku):
   ```json
   {
     "require": {
       "php": "^7.4 || ^8.0"
     }
   }
   ```

5. **Create Procfile**:
   ```
   web: heroku-php-apache2
   ```

6. **Deploy**:
   ```bash
   git add .
   git commit -m "Deploy to Heroku"
   git push heroku main
   ```

7. **Open App**:
   ```bash
   heroku open
   ```

#### B. Railway (Modern Alternative)
1. **Visit**: [railway.app](https://railway.app)
2. **Connect GitHub**: Link your repository
3. **Deploy**: Click "Deploy from GitHub"
4. **Configure**: Set PHP runtime
5. **Done**: Get live URL

#### C. Render (Free Tier)
1. **Sign Up**: [render.com](https://render.com)
2. **New Web Service**: Select from GitHub
3. **Choose Repo**: Select your repository
4. **Configure**:
   - Environment: PHP
   - Build Command: (none)
   - Start Command: (auto-detected)
5. **Deploy**: Click "Create Web Service"

---

### Option 3: Traditional Shared Hosting

#### Popular Shared Hosting Providers:
- **Bluehost** (~$2.95/month)
- **HostGator** (~$2.75/month)
- **SiteGround** (~$3.99/month)
- **Namecheap** (~$1.58/month)

#### Deployment Steps:
1. **Purchase Hosting Plan**
2. **Access cPanel**
3. **File Manager**:
   - Navigate to `public_html`
   - Upload `index.php` and `test_language.php`
4. **Access**: Visit your domain

---

## 📦 Quick GitHub Setup

### 1. Push to GitHub (If Not Already Done)

```bash
# Navigate to project
cd /path/to/mts-hospital

# Initialize git (if needed)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit: MTS Hospital website"

# Add remote (replace with your GitHub repo URL)
git remote add origin https://github.com/yourusername/mts-hospital.git

# Push to GitHub
git push -u origin main
```

### 2. Make Repository Public

1. Go to your GitHub repository
2. Click **Settings**
3. Scroll to **Danger Zone**
4. Click **Change visibility** → **Make public**

---

## 🔧 Required Files for Deployment

### For Heroku Deployment

Create `composer.json`:
```json
{
  "name": "mts-hospital/website",
  "description": "MTS Hospital bilingual website",
  "require": {
    "php": "^7.4 || ^8.0 || ^8.1 || ^8.2"
  }
}
```

Create `Procfile`:
```
web: heroku-php-apache2
```

Create `.htaccess`:
```apache
# Enable sessions
php_flag session.auto_start on

# Error handling
php_flag display_errors off
php_flag log_errors on

# Security
php_value session.cookie_httponly 1
php_value session.cookie_secure 0

# Rewrite rules (if needed)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
</IfModule>
```

---

## 🌐 Environment Variables (If Needed)

If your project needs database or other config:

### Heroku:
```bash
heroku config:set DB_HOST=your_host
heroku config:set DB_NAME=your_db
heroku config:set DB_USER=your_user
heroku config:set DB_PASS=your_pass
```

### Railway/Render:
Set in dashboard under "Environment Variables"

---

## 📝 Pre-Deployment Checklist

- [ ] All PHP files are UTF-8 encoded without BOM
- [ ] `.gitignore` includes sensitive files
- [ ] Sessions are properly configured
- [ ] Error reporting is set to production mode
- [ ] Test on local server first (XAMPP/WAMP)
- [ ] All documentation files are included
- [ ] `test_language.php` is accessible for testing

---

## 🧪 Testing After Deployment

### 1. Basic Functionality Test
1. Open your deployed URL
2. Page should load in English by default
3. Click "MY 🇲🇲" button
4. Page should switch to Myanmar instantly
5. Click "EN 🇬🇧" button
6. Page should switch back to English instantly
7. No white screens should appear

### 2. Using Test Utility
1. Navigate to `yoursite.com/test_language.php`
2. Follow on-screen instructions
3. Run all tests
4. Verify all pass ✅

---

## 🔍 Common Deployment Issues

### Issue: "Page Not Found" (404)
**Solution**:
- Ensure `index.php` is in the correct directory
- Check your hosting provider's root directory (usually `public_html` or `htdocs`)

### Issue: White Screen After Deploy
**Solution**:
1. Check PHP version (must be 7.0+)
2. Enable error logging
3. Check error logs on hosting control panel
4. Verify file encoding is UTF-8 without BOM

### Issue: Sessions Not Working
**Solution**:
1. Check PHP session directory permissions
2. Verify `session.save_path` in php.ini
3. Contact hosting support if needed

### Issue: Language Not Switching
**Solution**:
1. Verify sessions are enabled
2. Check PHP error logs
3. Test with `test_language.php`
4. Clear browser cache

---

## 📊 Recommended Deployment Flow

```
┌─────────────────┐
│  Local Testing  │
│   (XAMPP/WAMP)  │
└────────┬────────┘
         │
         v
┌─────────────────┐
│  Push to GitHub │
│  (Code Backup)  │
└────────┬────────┘
         │
         v
┌─────────────────┐
│ Deploy to Free  │
│  PHP Hosting    │
│  (Testing)      │
└────────┬────────┘
         │
         v
┌─────────────────┐
│  Final Deploy   │
│ (Production)    │
└─────────────────┘
```

---

## 🎯 Quick Start Commands

### For Heroku:
```bash
# Create app
heroku create mts-hospital

# Add Procfile
echo "web: heroku-php-apache2" > Procfile

# Deploy
git add .
git commit -m "Deploy to Heroku"
git push heroku main

# Open
heroku open
```

### For InfinityFree:
1. Upload files via File Manager
2. Access at: `yoursite.infinityfree.net`

### For GitHub (Code Hosting Only):
```bash
# Push code
git push origin main

# Then deploy to PHP hosting separately
```

---

## 🔗 Useful Resources

### Hosting Platforms:
- Heroku: https://www.heroku.com
- Railway: https://railway.app
- Render: https://render.com
- InfinityFree: https://infinityfree.net
- 000webhost: https://www.000webhost.com

### Documentation:
- Heroku PHP: https://devcenter.heroku.com/categories/php-support
- PHP Sessions: https://www.php.net/manual/en/book.session.php

### Tools:
- FileZilla (FTP): https://filezilla-project.org
- GitHub Desktop: https://desktop.github.com

---

## 💡 Best Practices

1. **Always test locally first** using XAMPP/WAMP/MAMP
2. **Keep GitHub repo updated** as backup
3. **Use environment variables** for sensitive data
4. **Monitor error logs** after deployment
5. **Test language switching** immediately after deploy
6. **Keep documentation** in repository
7. **Use version control** (git tags/branches)

---

## 🆘 Need Help?

### If Deployment Fails:
1. Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
2. Review deployment platform logs
3. Test locally first
4. Verify PHP version compatibility
5. Check file permissions

### Support Channels:
- **Heroku**: https://help.heroku.com
- **Railway**: Discord community
- **GitHub**: Issues tab in repository

---

## ✅ Success Checklist

After deployment, verify:
- [ ] Site is accessible at public URL
- [ ] English language loads by default
- [ ] Myanmar language switch works instantly
- [ ] No white screens appear
- [ ] URLs are clean (no ?lang= parameter)
- [ ] Language persists across refreshes
- [ ] Test page (`test_language.php`) works
- [ ] No PHP errors in logs

---

## 🎉 You're Done!

Once deployed and tested, your MTS Hospital website is live!

### Share Your URL:
- With team members
- With stakeholders
- For user testing
- In documentation

### Next Steps:
1. Monitor usage
2. Gather feedback
3. Update as needed
4. Scale if necessary

---

**Happy Deploying! 🚀**

*For more information, see [README.md](README.md) and [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)*
