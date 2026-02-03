# ⚡ Quick Reference Card

## 🎯 One-Page Guide to Deploying MTS Hospital

---

## 📤 Push to GitHub (2 minutes)

```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/YOUR_USERNAME/mts-hospital.git
git push -u origin main
```

**Full Guide:** [PUSH_TO_GITHUB.md](PUSH_TO_GITHUB.md)

---

## 🚀 Deploy to Heroku (3 minutes)

```bash
heroku create mts-hospital
git push heroku main
heroku open
```

**Full Guide:** [GITHUB_DEPLOYMENT.md](GITHUB_DEPLOYMENT.md)

---

## 🆓 Deploy to Free Hosting (5 minutes)

1. Go to https://infinityfree.net
2. Create account
3. Upload files
4. Done!

**Full Guide:** [DEPLOY_TO_LIVE.md](DEPLOY_TO_LIVE.md)

---

## 🤖 Auto Deploy with Script

```bash
./deploy.sh
```

Follow the interactive prompts!

---

## 🧪 Test Locally

```bash
php -S localhost:8000
```

Open: http://localhost:8000

---

## 📚 Quick Documentation Links

| Need | Read |
|------|------|
| Deploy in 5 min | [DEPLOY_TO_LIVE.md](DEPLOY_TO_LIVE.md) |
| Push to GitHub | [PUSH_TO_GITHUB.md](PUSH_TO_GITHUB.md) |
| All platforms | [GITHUB_DEPLOYMENT.md](GITHUB_DEPLOYMENT.md) |
| What was added | [DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md) |
| Fix an issue | [TROUBLESHOOTING.md](TROUBLESHOOTING.md) |
| Contribute | [CONTRIBUTING.md](CONTRIBUTING.md) |

---

## ✅ Pre-Deployment Checklist

- [ ] Tested locally
- [ ] Language switching works
- [ ] No PHP errors
- [ ] .gitignore configured
- [ ] Ready to deploy!

---

## 🎉 Success Checklist

After deployment:
- [ ] Website loads
- [ ] EN → MY switch works
- [ ] MY → EN switch works
- [ ] No white screens
- [ ] test_language.php passes

---

## 🆘 Quick Troubleshooting

**White screen?**
→ Check PHP version (need 7.4+)

**Language not switching?**
→ Check sessions are enabled

**Can't push to GitHub?**
→ Use personal access token

**Full guide:** [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

---

## 📞 Support

1. Check documentation
2. Search existing issues
3. Create new issue on GitHub

---

## 🏆 Deployment Platforms

| Platform | Cost | Speed | Best For |
|----------|------|-------|----------|
| Heroku | Free | ⭐⭐⭐⭐ | Developers |
| Railway | Free | ⭐⭐⭐⭐⭐ | Everyone |
| InfinityFree | Free | ⭐⭐⭐ | Beginners |

---

## 💡 Pro Tips

1. Always test locally first
2. Keep GitHub updated
3. Monitor error logs
4. Start with free tier

---

**Choose your path and deploy! 🚀**
