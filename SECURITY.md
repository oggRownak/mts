# 🔒 Security Policy

## Supported Versions

We take security seriously. The following versions are currently being supported with security updates:

| Version | Supported          | Status |
| ------- | ------------------ | ------ |
| 2.0.x   | ✅ Yes             | Active |
| 1.0.x   | ⚠️ Limited Support | Maintenance |
| < 1.0   | ❌ No              | End of Life |

## 🛡️ Security Features

### Current Security Measures

MTS Hospital website implements the following security features:

#### Input Validation
- ✅ Whitelist-based language parameter validation
- ✅ Only 'en' and 'my' values accepted
- ✅ All other values rejected

#### Session Security
- ✅ Secure session configuration
- ✅ HTTP-only cookies
- ✅ Session regeneration on language change
- ✅ Proper session lifecycle management

#### XSS Prevention
- ✅ Output encoding for user data
- ✅ No user input directly rendered
- ✅ Proper HTML escaping

#### Error Handling
- ✅ Error display disabled in production
- ✅ Errors logged, not displayed
- ✅ Generic error messages to users
- ✅ Detailed logs for administrators

#### File Protection
- ✅ .htaccess protection for sensitive files
- ✅ No directory listing
- ✅ Proper file permissions

#### Header Security
- ✅ X-Content-Type-Options: nosniff
- ✅ X-Frame-Options: SAMEORIGIN
- ✅ X-XSS-Protection: 1; mode=block

## 🚨 Reporting a Vulnerability

### How to Report

If you discover a security vulnerability, please follow these steps:

#### 1. **DO NOT** Create a Public Issue
Security vulnerabilities should NOT be reported via public GitHub issues.

#### 2. Email Us Directly
Send details to: **security@mtshospital.example.com**
(Replace with actual security contact email)

#### 3. Include in Your Report

Please provide:
- **Description**: Clear description of the vulnerability
- **Impact**: Potential impact and severity
- **Steps to Reproduce**: Detailed steps to reproduce the issue
- **Affected Versions**: Which versions are affected
- **Proof of Concept**: If applicable
- **Suggested Fix**: If you have one

#### Report Template

```
Subject: [SECURITY] Brief description

Vulnerability Type: [e.g., XSS, SQL Injection, etc.]
Severity: [Critical/High/Medium/Low]

Description:
[Detailed description]

Steps to Reproduce:
1. ...
2. ...
3. ...

Impact:
[What can an attacker do with this?]

Affected Versions:
[Which versions are vulnerable?]

Proof of Concept:
[Code or steps demonstrating the vulnerability]

Suggested Fix:
[If you have a suggestion]
```

### What to Expect

#### Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Depends on severity
  - Critical: 24-48 hours
  - High: 3-7 days
  - Medium: 1-2 weeks
  - Low: Next release cycle

#### Our Process

1. **Acknowledgment**: We confirm receipt of your report
2. **Investigation**: We verify and assess the vulnerability
3. **Fix Development**: We develop and test a fix
4. **Disclosure**: We coordinate disclosure timing with you
5. **Release**: We release the security update
6. **Credit**: We credit you (unless you prefer anonymity)

## 🔐 Security Best Practices

### For Developers

When contributing to this project:

#### Code Security
```php
// ✅ Good - Whitelist validation
if (in_array($_GET['lang'], ['en', 'my'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// ❌ Bad - No validation
$_SESSION['lang'] = $_GET['lang'];
```

#### Session Handling
```php
// ✅ Good - Check session status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ❌ Bad - Assume session started
session_start();
```

#### Output Encoding
```php
// ✅ Good - Escape output
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');

// ❌ Bad - Direct output
echo $userInput;
```

#### Error Handling
```php
// ✅ Good - Log, don't display
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log($error);

// ❌ Bad - Display errors
ini_set('display_errors', 1);
```

### For Deployers

When deploying this application:

#### Environment Configuration
- ✅ Set `display_errors = 0` in production
- ✅ Enable `log_errors = 1`
- ✅ Use HTTPS when available
- ✅ Keep PHP version updated
- ✅ Regularly review error logs

#### File Permissions
```bash
# Files should be readable by web server
chmod 644 *.php
chmod 644 .htaccess

# Directories should be accessible
chmod 755 .
```

#### Server Configuration
- ✅ Disable directory listing
- ✅ Protect sensitive files via .htaccess
- ✅ Use secure session settings
- ✅ Enable security headers

## 🔍 Security Checklist

### Pre-Deployment Security Review

Before deploying to production:

- [ ] All input is validated
- [ ] Output is properly encoded
- [ ] Sessions are secure
- [ ] Errors are logged, not displayed
- [ ] .htaccess is properly configured
- [ ] File permissions are correct
- [ ] PHP version is up to date
- [ ] Security headers are enabled
- [ ] HTTPS is configured (if available)
- [ ] Error logs are monitored

### Regular Security Maintenance

Perform regularly:

- [ ] Update PHP to latest version
- [ ] Review error logs weekly
- [ ] Check for dependency updates
- [ ] Review access logs for anomalies
- [ ] Test security features
- [ ] Backup data regularly

## 🚫 Known Limitations

### Current Limitations

The following are known limitations, not security vulnerabilities:

1. **No CSRF Protection**: Not currently implemented (low risk for this application)
2. **No Rate Limiting**: Language switching is not rate-limited
3. **Basic Session Security**: Uses default PHP session security

These will be addressed in future versions.

## 📚 Security Resources

### References

- [PHP Security Guide](https://www.php.net/manual/en/security.php)
- [OWASP PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)
- [Session Security](https://www.php.net/manual/en/session.security.php)

### Tools

- **PHP Syntax Check**: `php -l file.php`
- **Security Scanner**: [RIPS](https://www.ripstech.com/)
- **Dependency Check**: `composer audit`

## 🏆 Security Hall of Fame

We recognize security researchers who help make this project more secure:

<!-- Names will be added here as vulnerabilities are reported and fixed -->

*No vulnerabilities reported yet.*

## 📜 Disclosure Policy

### Coordinated Disclosure

We follow coordinated disclosure:

1. **Private Report**: Vulnerabilities reported privately
2. **Investigation**: We verify and develop fix
3. **Coordination**: We coordinate disclosure with reporter
4. **Public Release**: Fix released, then vulnerability disclosed
5. **Credit**: Reporter credited (unless anonymous)

### Disclosure Timeline

- **Day 0**: Vulnerability reported
- **Day 1-2**: Initial response
- **Day 2-7**: Investigation and fix development
- **Day 7-30**: Testing and coordination
- **Day 30+**: Public disclosure (or sooner if critical)

## ⚖️ Legal

### Safe Harbor

We support security research and will not pursue legal action against researchers who:

- Report vulnerabilities responsibly
- Give us reasonable time to fix
- Don't exploit vulnerabilities
- Don't access user data
- Act in good faith

### Scope

**In Scope:**
- ✅ This application code
- ✅ Configuration files
- ✅ Deployment scripts

**Out of Scope:**
- ❌ Third-party services
- ❌ Social engineering
- ❌ Physical security
- ❌ DDoS attacks

## 🆘 Emergency Contact

For critical security issues requiring immediate attention:

**Email**: security@mtshospital.example.com  
**Subject**: [URGENT SECURITY]  
**Response**: Within 24 hours

## 🙏 Thank You

Thank you for helping keep MTS Hospital website secure!

Your responsible disclosure helps protect all users.

---

*Last updated: 2024-02-03*
