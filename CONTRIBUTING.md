# 🤝 Contributing to MTS Hospital

Thank you for your interest in contributing to the MTS Hospital project! This guide will help you get started.

---

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How to Contribute](#how-to-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)
- [Testing](#testing)
- [Documentation](#documentation)

---

## 📜 Code of Conduct

### Our Pledge

We are committed to providing a welcoming and inclusive experience for everyone. We expect all contributors to:

- ✅ Be respectful and inclusive
- ✅ Accept constructive criticism gracefully
- ✅ Focus on what's best for the community
- ✅ Show empathy towards others

### Unacceptable Behavior

- ❌ Harassment or discrimination
- ❌ Trolling or insulting comments
- ❌ Publishing others' private information
- ❌ Any unprofessional conduct

---

## 🚀 How to Contribute

### Types of Contributions

We welcome:

1. **🐛 Bug Reports** - Found a bug? Let us know!
2. **✨ Feature Requests** - Have an idea? Share it!
3. **📝 Documentation** - Improve or add docs
4. **💻 Code Contributions** - Fix bugs or add features
5. **🧪 Testing** - Help test the application
6. **🌐 Translations** - Add or improve translations

### Before You Start

1. Check if an issue already exists
2. If not, create a new issue to discuss your idea
3. Wait for feedback before starting work
4. Fork the repository and create a branch

---

## 🛠️ Development Setup

### Prerequisites

- PHP 7.4 or higher
- Git
- A code editor (VS Code recommended)
- A web server (Apache/Nginx or PHP built-in)

### Setup Steps

1. **Fork the repository**
   ```bash
   # Click "Fork" button on GitHub
   ```

2. **Clone your fork**
   ```bash
   git clone https://github.com/YOUR_USERNAME/mts-hospital.git
   cd mts-hospital
   ```

3. **Create a branch**
   ```bash
   git checkout -b feature/your-feature-name
   # or
   git checkout -b fix/bug-description
   ```

4. **Start development server**
   ```bash
   php -S localhost:8000
   ```

5. **Make your changes**
   - Edit files
   - Test locally
   - Commit changes

---

## 📏 Coding Standards

### PHP Code Style

Follow these conventions:

#### File Structure
```php
<?php
// File header (if applicable)

// Early initialization
ob_start();
session_start();

// Main logic

// Output flush at end
if (ob_get_level() > 0) ob_end_flush();
```

#### Naming Conventions
- **Variables**: `$camelCase`
- **Functions**: `camelCase()`
- **Constants**: `UPPER_CASE`
- **Classes**: `PascalCase`

#### Code Style
```php
// Good ✅
if ($condition) {
    doSomething();
}

// Bad ❌
if($condition){
    doSomething();
}
```

#### Comments
```php
// Good ✅
// Handle language switching with proper buffer management

// Bad ❌
// Check if lang is set
```

### Output Buffering

**Always:**
- Start buffering early: `ob_start()`
- Clear before redirects: `while(ob_get_level() > 0) ob_end_clean()`
- Flush at end: `if (ob_get_level() > 0) ob_end_flush()`

### Sessions

**Always:**
- Check status: `if (session_status() === PHP_SESSION_NONE)`
- Validate input: `in_array($_SESSION['lang'], ['en', 'my'])`
- Set defaults: `$_SESSION['lang'] = $_SESSION['lang'] ?? 'en'`

---

## 📝 Commit Guidelines

### Commit Message Format

Use conventional commits:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

### Examples

**Good ✅**
```
feat(language): add Thai language support

- Add Thai translations
- Update language selector
- Test language switching

Closes #42
```

**Good ✅**
```
fix(session): prevent session conflicts

Fix issue where sessions weren't properly initialized
on some hosting providers.

Fixes #123
```

**Bad ❌**
```
update stuff
```

### Commit Best Practices

- ✅ Use present tense ("add" not "added")
- ✅ Use imperative mood ("move" not "moves")
- ✅ Be specific and descriptive
- ✅ Reference issues when applicable
- ✅ Keep commits atomic (one logical change)

---

## 🔄 Pull Request Process

### Before Submitting

1. **Test your changes**
   ```bash
   # Test manually
   php -S localhost:8000
   
   # Test language switching
   # Test on multiple browsers
   ```

2. **Validate PHP syntax**
   ```bash
   php -l index.php
   ```

3. **Check file encoding**
   ```bash
   file -bi index.php
   # Should be: text/x-php; charset=utf-8
   ```

4. **Update documentation**
   - Update README.md if needed
   - Add to CHANGELOG.md
   - Document new features

5. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: your feature description"
   ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

### Creating the Pull Request

1. Go to original repository
2. Click "New Pull Request"
3. Select your branch
4. Fill in the template:

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Code refactoring

## Testing
- [ ] Tested locally
- [ ] All tests pass
- [ ] Manual testing completed

## Screenshots (if applicable)
Add screenshots here

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-reviewed code
- [ ] Commented complex code
- [ ] Updated documentation
- [ ] No new warnings
- [ ] Added tests (if applicable)
```

### Review Process

1. **Automated checks** will run
2. **Maintainer review** - may request changes
3. **Address feedback** - push additional commits
4. **Approval** - maintainer approves PR
5. **Merge** - PR is merged to main branch

### After Merge

1. Delete your branch
2. Pull latest changes
3. Thank you! 🎉

---

## 🧪 Testing

### Manual Testing Checklist

Before submitting, verify:

- [ ] Website loads without errors
- [ ] English language displays correctly
- [ ] Myanmar language displays correctly
- [ ] Language switch works instantly
- [ ] No white screens appear
- [ ] Language persists on refresh
- [ ] Works on Chrome, Firefox, Safari
- [ ] Works on mobile devices
- [ ] No console errors
- [ ] No PHP errors in logs

### Using Test Utility

```bash
# Navigate to test page
open http://localhost:8000/test_language.php

# Run all tests
# All should pass ✅
```

### Testing New Features

If adding new features:
1. Test all existing functionality
2. Test new feature thoroughly
3. Test edge cases
4. Test error handling
5. Document test results

---

## 📚 Documentation

### What to Document

- New features
- API changes
- Configuration changes
- Breaking changes
- Migration guides
- Examples and usage

### Where to Document

- **README.md** - Overview and quick start
- **Code comments** - Complex logic
- **Separate docs** - Detailed guides
- **CHANGELOG.md** - Version changes

### Documentation Style

```markdown
## Feature Name

### Description
Brief description of the feature

### Usage
\`\`\`php
// Example code
\`\`\`

### Parameters
- `param1` - Description
- `param2` - Description

### Returns
Description of return value

### Example
\`\`\`php
// Complete example
\`\`\`
```

---

## 🎯 Project-Specific Guidelines

### Language System

When working with languages:
- Add translations to language arrays
- Maintain consistency across languages
- Test with real Myanmar text
- Ensure proper UTF-8 encoding

### Buffer Management

Always maintain proper buffer handling:
- Start early: `ob_start()`
- Clear before redirects
- Flush at end
- Check levels: `ob_get_level()`

### Session Management

Follow session best practices:
- Check session status first
- Validate all inputs
- Set secure defaults
- Handle edge cases

---

## 🐛 Reporting Bugs

### Before Reporting

1. Check if bug already reported
2. Try to reproduce consistently
3. Test on clean installation
4. Gather relevant information

### Bug Report Template

```markdown
**Describe the bug**
Clear description of the bug

**To Reproduce**
Steps to reproduce:
1. Go to '...'
2. Click on '...'
3. See error

**Expected behavior**
What should happen

**Screenshots**
If applicable

**Environment:**
- PHP Version: [e.g., 7.4]
- Browser: [e.g., Chrome]
- OS: [e.g., Ubuntu]

**Additional context**
Any other relevant information
```

---

## ✨ Feature Requests

### Before Requesting

1. Check if already requested
2. Explain the use case
3. Consider implementation
4. Discuss with maintainers

### Feature Request Template

```markdown
**Is your feature request related to a problem?**
Clear description of the problem

**Describe the solution you'd like**
Clear description of desired solution

**Describe alternatives considered**
Alternative solutions considered

**Additional context**
Screenshots, mockups, examples
```

---

## 🤔 Questions?

- **General questions**: Use GitHub Discussions
- **Bug reports**: Create an Issue
- **Feature ideas**: Create an Issue with [Feature] tag
- **Security issues**: Email directly (don't create public issue)

---

## 📞 Getting Help

### Resources

- **Documentation**: Check all .md files
- **Issues**: Search existing issues
- **Discussions**: Ask in GitHub Discussions
- **Code**: Review existing code

### Community

- Be patient - maintainers are volunteers
- Be respectful in all communications
- Help others when you can
- Share your knowledge

---

## 🎉 Recognition

Contributors will be:
- Listed in CONTRIBUTORS.md
- Credited in release notes
- Mentioned in project README
- Forever appreciated! 💝

---

## 📜 License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

## 🙏 Thank You!

Thank you for contributing to MTS Hospital! Every contribution, no matter how small, helps make this project better.

**Happy Coding! 🚀**
