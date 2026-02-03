# Changelog

All notable changes to the MTS Hospital website project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
- GitHub deployment documentation
- Heroku deployment support
- Railway deployment support
- Automated deployment script
- GitHub Actions workflow
- Comprehensive deployment guides
- Contributing guidelines
- License file

---

## [2.0.0] - 2024-02-03

### Added
- Complete language switching fix
- Output buffer management system
- Robust redirect logic with proper HTTP status codes
- URL parsing to preserve query parameters
- Comprehensive documentation suite
- Testing utility (test_language.php)
- Multiple deployment guides

### Fixed
- **Critical**: White screen issue when switching to Myanmar language
- **Critical**: Language parameter not being removed from URL after switch
- Session initialization timing issues
- Buffer overflow on redirects
- Header already sent errors

### Changed
- Improved output buffering strategy
- Enhanced session management
- Better error handling and logging
- Cleaner URL structure after language switch

### Technical Details
- Early output buffering (line 8)
- Buffer clearing before redirects (lines 30-33)
- Explicit HTTP 302 redirects (line 36)
- Safe buffer flush at page end (lines 1176-1178)

---

## [1.0.0] - 2024-01-XX

### Added
- Initial bilingual website
- English and Myanmar language support
- Basic language switching mechanism
- Session-based language persistence
- Responsive design
- Basic security features

### Known Issues
- White screen appears when switching to Myanmar language
- Language parameter remains in URL after switch
- Inconsistent behavior across different hosting environments

---

## Version History Summary

| Version | Date | Type | Description |
|---------|------|------|-------------|
| 2.0.0 | 2024-02-03 | Major | Fixed language switching, added deployment |
| 1.0.0 | 2024-01-XX | Initial | First release with basic features |

---

## Migration Guides

### From 1.0.0 to 2.0.0

**What Changed:**
- Output buffering implementation
- Redirect logic
- URL handling

**How to Upgrade:**
1. Backup current `index.php`
2. Replace with new version
3. Test language switching
4. Monitor error logs
5. No database changes needed
6. No configuration changes needed

**Breaking Changes:**
- None (fully backward compatible)

---

## Future Releases

### Planned for 3.0.0
- [ ] Database integration
- [ ] Multi-page support
- [ ] Admin panel
- [ ] User authentication
- [ ] Appointment booking system

### Planned for 2.1.0
- [ ] Additional languages (Thai, Chinese)
- [ ] Dark mode support
- [ ] Accessibility improvements
- [ ] Performance optimizations

---

## Release Process

### How We Release

1. **Development** - Work on features in branches
2. **Testing** - Comprehensive testing
3. **Documentation** - Update all docs
4. **Version Bump** - Update version numbers
5. **Changelog** - Document changes
6. **Release** - Create GitHub release
7. **Deploy** - Deploy to production

### Version Numbering

We use Semantic Versioning:
- **MAJOR** (X.0.0) - Breaking changes
- **MINOR** (0.X.0) - New features, backward compatible
- **PATCH** (0.0.X) - Bug fixes, backward compatible

---

## Support Policy

### Supported Versions

| Version | Supported | End of Life |
|---------|-----------|-------------|
| 2.0.x   | ✅ Yes    | TBD         |
| 1.0.x   | ⚠️ Limited | 2024-06-01  |

### Security Updates

- Latest version receives immediate security patches
- Previous version receives security patches for 6 months
- Older versions are not supported

---

## Contributors

### Version 2.0.0
- Language switching fix
- Deployment infrastructure
- Documentation improvements

### Version 1.0.0
- Initial development
- Core features
- Basic documentation

---

## Links

- **Repository**: https://github.com/yourusername/mts-hospital
- **Issues**: https://github.com/yourusername/mts-hospital/issues
- **Releases**: https://github.com/yourusername/mts-hospital/releases
- **Documentation**: See README.md and related .md files

---

## Notes

### For Developers

When releasing a new version:
1. Update this CHANGELOG
2. Update version in composer.json (if applicable)
3. Create git tag: `git tag -a v2.0.0 -m "Version 2.0.0"`
4. Push tag: `git push origin v2.0.0`
5. Create GitHub release
6. Update deployment

### For Users

To check your current version:
```php
// Check index.php header comments
// Or check git tag:
git describe --tags
```

---

**Thank you for using MTS Hospital website! 🏥**
