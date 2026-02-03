# 📤 Push to GitHub - Quick Guide

## 🎯 Goal
Get your MTS Hospital project on GitHub and ready for deployment.

---

## 📋 Prerequisites

- Git installed on your computer
- GitHub account (free)
- Terminal/Command Prompt access

---

## 🚀 Quick Steps

### 1. Create GitHub Repository

1. **Go to GitHub**: https://github.com
2. **Click** "+" → "New repository"
3. **Fill in**:
   - Repository name: `mts-hospital`
   - Description: "Bilingual hospital website with instant language switching"
   - Public or Private (your choice)
   - **Don't** initialize with README (we have one)
4. **Click** "Create repository"

### 2. Connect Local Repository to GitHub

```bash
# Navigate to your project
cd /path/to/mts-hospital

# If git is not initialized (check with: git status)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit: MTS Hospital bilingual website"

# Add GitHub remote (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/mts-hospital.git

# Push to GitHub
git push -u origin main
```

**Note**: If you get an error about "main" vs "master", try:
```bash
git branch -M main
git push -u origin main
```

### 3. Verify on GitHub

1. Refresh your GitHub repository page
2. You should see all your files
3. README.md will display automatically

---

## 🎨 Make It Look Professional

### Add Repository Description

1. Go to your repository on GitHub
2. Click ⚙️ next to "About"
3. Add:
   - **Description**: "Professional bilingual hospital website with instant language switching (EN/MY)"
   - **Website**: (if deployed)
   - **Topics**: `php`, `bilingual`, `hospital`, `myanmar`, `healthcare`, `language-switching`

### Add Badges

The README.md already has badges! They'll show automatically.

### Enable GitHub Pages (Optional)

**Note**: GitHub Pages doesn't support PHP, but you can use it for documentation:

1. Go to Settings → Pages
2. Source: Deploy from branch
3. Branch: main → /docs (if you have a docs folder)
4. Save

---

## 🔒 Security Check

Before pushing, ensure:

- [ ] No sensitive data in code
- [ ] No database passwords
- [ ] No API keys
- [ ] .gitignore is properly configured

**.gitignore** is already set up for you! ✅

---

## 📝 Update Repository After Changes

```bash
# Check what changed
git status

# Add all changes
git add .

# Commit with message
git commit -m "Description of changes"

# Push to GitHub
git push
```

---

## 🌐 Deploy from GitHub

### Option 1: Deploy to Heroku

```bash
# If Heroku remote not added
heroku git:remote -a your-heroku-app-name

# Push to Heroku
git push heroku main
```

### Option 2: Deploy to Railway

1. Go to https://railway.app
2. Click "New Project"
3. Select "Deploy from GitHub repo"
4. Choose your repository
5. Railway will auto-detect PHP and deploy

### Option 3: Deploy to Render

1. Go to https://render.com
2. Click "New +"
3. Select "Web Service"
4. Connect GitHub
5. Choose repository
6. Click "Create Web Service"

---

## 🔄 Set Up Auto-Deployment

GitHub Actions is already configured! It will:

- ✅ Validate PHP syntax on every push
- ✅ Check file encodings
- ✅ Run tests (if you add [deploy] in commit message)
- ✅ Create releases when you tag versions

### Create a Release

```bash
# Tag a version
git tag -a v2.0.0 -m "Version 2.0.0: Full deployment support"

# Push tag
git push origin v2.0.0
```

GitHub Actions will automatically create a release package!

---

## 👥 Invite Collaborators

1. Go to Settings → Collaborators
2. Click "Add people"
3. Enter GitHub username
4. Click "Add [username] to this repository"

---

## 🎯 Repository Settings Checklist

- [ ] Repository name set
- [ ] Description added
- [ ] Topics/tags added
- [ ] README displays correctly
- [ ] License file visible
- [ ] .gitignore working
- [ ] GitHub Actions enabled
- [ ] Branch protection (optional)
- [ ] Issues enabled
- [ ] Discussions enabled (optional)

---

## 📊 Track Your Repository

### Watch Activity

- **Commits**: See all changes
- **Issues**: Track bugs and features
- **Pull Requests**: Review contributions
- **Actions**: Monitor deployments
- **Insights**: View statistics

### Share Your Repository

```markdown
GitHub Repository: https://github.com/YOUR_USERNAME/mts-hospital
```

---

## 🔗 Useful Git Commands

### Checking Status
```bash
git status              # What changed?
git log                 # Commit history
git diff                # See specific changes
git branch             # List branches
```

### Undoing Changes
```bash
git checkout -- file.php    # Undo changes to file
git reset --soft HEAD~1     # Undo last commit (keep changes)
git reset --hard HEAD~1     # Undo last commit (discard changes)
```

### Branching
```bash
git checkout -b feature-name    # Create and switch to branch
git checkout main               # Switch to main
git merge feature-name          # Merge branch into current
git branch -d feature-name      # Delete branch
```

---

## 🆘 Common Issues

### Issue: "Authentication failed"

**Solution 1**: Use Personal Access Token
1. Go to GitHub Settings → Developer settings → Personal access tokens
2. Generate new token
3. Copy token
4. Use as password when pushing

**Solution 2**: Use SSH
```bash
# Generate SSH key (if not exists)
ssh-keygen -t ed25519 -C "your_email@example.com"

# Add to GitHub: Settings → SSH and GPG keys
cat ~/.ssh/id_ed25519.pub

# Change remote to SSH
git remote set-url origin git@github.com:YOUR_USERNAME/mts-hospital.git
```

### Issue: "fatal: refusing to merge unrelated histories"

```bash
git pull origin main --allow-unrelated-histories
```

### Issue: "Permission denied"

Check if you're the repository owner or have write access.

### Issue: "Large file warning"

File is too large for GitHub (>100MB). Add to .gitignore:
```bash
echo "large-file.zip" >> .gitignore
git rm --cached large-file.zip
git commit -m "Remove large file"
```

---

## 📚 Next Steps

After pushing to GitHub:

1. ✅ **Deploy** to live server (see DEPLOY_TO_LIVE.md)
2. ✅ **Share** repository URL with team
3. ✅ **Set up** continuous deployment
4. ✅ **Monitor** via GitHub Actions
5. ✅ **Invite** collaborators
6. ✅ **Create** issues for tasks
7. ✅ **Document** in wiki (optional)

---

## 🎉 Success!

Your code is now on GitHub and ready to:
- 🔄 Be deployed to live servers
- 👥 Accept contributions
- 📊 Track changes
- 🚀 Auto-deploy with CI/CD
- 🌟 Get stars from community

---

## 📞 Need Help?

- **Git Documentation**: https://git-scm.com/doc
- **GitHub Guides**: https://guides.github.com
- **This Project**: See README.md

---

## 🔐 GitHub Repository Settings

### Recommended Settings

**General:**
- ✅ Issues enabled
- ✅ Allow merge commits
- ✅ Allow squash merging
- ✅ Automatically delete head branches

**Branches:**
- ✅ Protect main branch
- ✅ Require pull request reviews
- ✅ Require status checks to pass

**Actions:**
- ✅ Allow all actions
- ✅ Read and write permissions

---

**Happy Coding! 🚀**

Your project is now on GitHub and ready for the world!
