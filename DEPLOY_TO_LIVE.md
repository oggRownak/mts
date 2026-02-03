# 🚀 Deploy MTS Hospital to Live - Quick Guide

## 🎯 Goal
Get your MTS Hospital website live on the internet in under 10 minutes!

---

## ⚡ Fastest Path to Live Website

### Option 1: InfinityFree (100% Free, No Credit Card) ⭐ RECOMMENDED

**Time: ~5 minutes**

#### Steps:
1. **Sign Up**: Go to https://infinityfree.net → Click "Create Account"
2. **Create Website**: 
   - Enter subdomain name (e.g., `mts-hospital`)
   - Click "Create Account"
   - Wait for setup (2-3 minutes)
3. **Upload Files**:
   - Click "Control Panel" → "Online File Manager"
   - Navigate to `htdocs` folder
   - Click "Upload"
   - Upload these files:
     - ✅ `index.php`
     - ✅ `test_language.php`
     - ✅ `.htaccess`
4. **Done!** Visit: `http://mts-hospital.infinityfree.net`

**Pros**: Free, no credit card, easy setup  
**Cons**: Ads on free tier (removable with premium)

---

### Option 2: Heroku (Free Tier) 🔥 DEVELOPER CHOICE

**Time: ~8 minutes**

#### Prerequisites:
- Git installed
- Heroku account (free)

#### Steps:

**1. Install Heroku CLI**:
```bash
# Mac
brew tap heroku/brew && brew install heroku

# Windows - Download from:
# https://devcenter.heroku.com/articles/heroku-cli

# Linux
curl https://cli-assets.heroku.com/install.sh | sh
```

**2. Login**:
```bash
heroku login
```

**3. Navigate to Project**:
```bash
cd /path/to/mts-hospital
```

**4. Deploy**:
```bash
# Create Heroku app
heroku create mts-hospital-app

# Push code
git add .
git commit -m "Deploy to Heroku"
git push heroku main

# Open app
heroku open
```

**Done!** Your app is live at: `https://mts-hospital-app.herokuapp.com`

**Pros**: Professional, git-based, scalable  
**Cons**: Requires CLI knowledge

---

### Option 3: Railway (Modern & Fast) 🚂

**Time: ~3 minutes**

#### Steps:
1. **Sign Up**: Go to https://railway.app
2. **New Project**: Click "New Project"
3. **Deploy from GitHub**:
   - Connect GitHub account
   - Select repository
   - Click "Deploy"
4. **Configure**:
   - Railway auto-detects PHP
   - Wait for build (~1 minute)
5. **Done!** Click the generated URL

**Pros**: Fastest, modern UI, auto-deploy  
**Cons**: Limited free tier hours

---

## 📦 One-Command Deployment

We've included a deployment script for easy deployment:

```bash
# Make script executable (first time only)
chmod +x deploy.sh

# Run deployment script
./deploy.sh
```

The script will guide you through:
- ✅ Heroku deployment
- ✅ Railway deployment  
- ✅ Git push
- ✅ Creating deployment package

---

## 🎬 Step-by-Step Video Guide

### For InfinityFree:

1. **Sign up at InfinityFree** (1 min)
   - Go to infinityfree.net
   - Click "Create Account"
   - Enter email and password

2. **Create website** (2 min)
   - Choose subdomain name
   - Select free plan
   - Wait for account creation

3. **Upload files** (2 min)
   - Open File Manager
   - Go to `htdocs`
   - Upload `index.php`, `test_language.php`, `.htaccess`

4. **Test** (30 sec)
   - Visit your URL
   - Test language switching

**Total: ~5 minutes**

---

## 🔧 Files Required for Deployment

### Essential Files:
- ✅ `index.php` - Main website
- ✅ `test_language.php` - Testing page
- ✅ `.htaccess` - Apache configuration

### Heroku-Specific:
- ✅ `composer.json` - PHP version specification
- ✅ `Procfile` - Server configuration

### Optional:
- 📄 Documentation files (*.md)

All files are already included in this repository!

---

## 🌐 Deployment Comparison

| Platform | Cost | Speed | Difficulty | Best For |
|----------|------|-------|------------|----------|
| **InfinityFree** | Free | ⭐⭐⭐ | Easy | Quick testing |
| **Heroku** | Free tier | ⭐⭐⭐⭐ | Medium | Developers |
| **Railway** | Free tier | ⭐⭐⭐⭐⭐ | Easy | Modern apps |
| **000webhost** | Free | ⭐⭐⭐ | Easy | Beginners |
| **Shared Hosting** | $2-5/mo | ⭐⭐⭐⭐ | Easy | Production |

---

## ✅ Post-Deployment Checklist

After deployment, verify:

### 1. Basic Functionality:
```
[ ] Website loads
[ ] Default language is English
[ ] "MY 🇲🇲" button works
[ ] "EN 🇬🇧" button works
[ ] No white screens appear
[ ] Language persists on refresh
```

### 2. Test Page:
```
[ ] Access /test_language.php
[ ] Run all tests
[ ] All tests pass
```

### 3. Performance:
```
[ ] Page loads in < 2 seconds
[ ] Language switch is instant
[ ] No errors in browser console
```

---

## 🐛 Common Issues & Quick Fixes

### Issue: "Site Can't Be Reached"
**Fix**: Wait 5-10 minutes after deployment. DNS propagation takes time.

### Issue: "Internal Server Error"
**Fix**: 
1. Check `.htaccess` is uploaded
2. Verify PHP version is 7.0+
3. Check error logs

### Issue: White Screen After Deploy
**Fix**:
1. Enable error logging
2. Check PHP error logs
3. Verify file encoding is UTF-8
4. See TROUBLESHOOTING.md

### Issue: Language Not Switching
**Fix**:
1. Verify sessions are enabled
2. Check hosting allows sessions
3. Clear browser cache
4. Test with test_language.php

---

## 📱 Mobile Testing

After deployment, test on mobile:
1. Open site on phone
2. Test portrait mode
3. Test landscape mode
4. Test language switching
5. Verify responsive design

---

## 🔐 Security Checklist

Before going live:
```
[ ] Set display_errors = 0
[ ] Enable log_errors = 1
[ ] Verify .htaccess is active
[ ] Test input validation
[ ] Check session security
[ ] Verify HTTPS (if available)
```

---

## 🎯 Quick Commands Reference

### Push to GitHub:
```bash
git add .
git commit -m "Ready for deployment"
git push origin main
```

### Deploy to Heroku:
```bash
heroku create app-name
git push heroku main
heroku open
```

### Create Deployment Package:
```bash
zip -r deploy.zip index.php test_language.php .htaccess composer.json Procfile
```

### Test Locally:
```bash
php -S localhost:8000
# Then visit: http://localhost:8000
```

---

## 📊 Deployment Status

Track your deployment:

| Step | Status | Time |
|------|--------|------|
| Files prepared | ⏳ | 1 min |
| Platform selected | ⏳ | 1 min |
| Account created | ⏳ | 2 min |
| Files uploaded | ⏳ | 2 min |
| Testing | ⏳ | 2 min |
| **Total** | | **~8 min** |

---

## 🎉 Success!

Once deployed:
1. ✅ Share URL with team
2. ✅ Update documentation
3. ✅ Monitor for errors
4. ✅ Gather user feedback

### Your Live URL:
```
InfinityFree: http://your-site.infinityfree.net
Heroku: https://your-app.herokuapp.com
Railway: https://your-app.up.railway.app
```

---

## 🔗 Additional Resources

- **Full Guide**: See [GITHUB_DEPLOYMENT.md](GITHUB_DEPLOYMENT.md)
- **Troubleshooting**: See [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
- **Local Testing**: See [TESTING.md](TESTING.md)
- **Code Details**: See [README.md](README.md)

---

## 💡 Pro Tips

1. **Test locally first**: Use XAMPP/WAMP before deploying
2. **Keep GitHub updated**: Always have latest code in repo
3. **Monitor logs**: Check for errors after deployment
4. **Use free tier first**: Test before paying for hosting
5. **Document your URL**: Save it somewhere safe

---

## 🚀 Ready to Deploy?

Choose your platform and follow the steps above!

**Recommended for beginners**: InfinityFree  
**Recommended for developers**: Heroku  
**Recommended for speed**: Railway

---

## 🆘 Need Help?

1. Check error message
2. Read TROUBLESHOOTING.md
3. Check platform documentation
4. Google the error
5. Ask in platform forums

---

**Happy Deploying! 🎊**

Your website will be live in minutes!
