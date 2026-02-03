# Testing the Language Switching Fix

## Test Scenario 1: Basic Language Switch
1. Open the website: `http://localhost/MTSPrj/index.php`
2. Verify the page loads in English (default)
3. Click the "MY 🇲🇲" button
4. **Expected Result**: Page immediately reloads in Myanmar language, no white screen
5. Verify URL is: `http://localhost/MTSPrj/index.php` (no ?lang=my parameter)
6. Click the "EN 🇬🇧" button
7. **Expected Result**: Page immediately reloads in English, no white screen

## Test Scenario 2: Language Switch with Query Parameters
1. Open the website with a query parameter: `http://localhost/MTSPrj/index.php?page=services`
2. Click the "MY 🇲🇲" button
3. **Expected Result**: Page reloads in Myanmar, URL preserves the page parameter
4. Verify URL is: `http://localhost/MTSPrj/index.php?page=services` (lang parameter removed, page parameter kept)

## Test Scenario 3: Rapid Language Switching
1. Open the website
2. Quickly click: MY → EN → MY → EN
3. **Expected Result**: Each click should work smoothly without delays or white screens

## Test Scenario 4: Browser Back Button
1. Open the website in English
2. Click "MY 🇲🇲" button (switches to Myanmar)
3. Click the browser's back button
4. **Expected Result**: Page goes back to English
5. Click forward button
6. **Expected Result**: Page shows Myanmar again

## Test Scenario 5: Page Refresh
1. Open the website and switch to Myanmar
2. Press F5 or Ctrl+R to refresh the page
3. **Expected Result**: Page stays in Myanmar language
4. Switch to English and refresh
5. **Expected Result**: Page stays in English language

## Debugging White Screen Issues

If you still see a white screen:

### Check PHP Error Logs
```bash
# On Linux/Mac
tail -f /var/log/apache2/error.log

# On Windows (XAMPP)
# Check: C:\xampp\apache\logs\error.log
```

### Enable Display Errors (Temporary)
Edit line 4 of `index.php`:
```php
ini_set('display_errors', 1); // Change from 0 to 1
```
This will show errors on screen (helpful for debugging, but disable in production)

### Check Browser Console
1. Open Developer Tools (F12)
2. Check Console tab for JavaScript errors
3. Check Network tab for failed requests

### Common Issues and Solutions

**Issue**: Still seeing white screen
- **Solution**: Clear browser cache and cookies
- **Command**: Ctrl+Shift+Delete (Chrome/Firefox)

**Issue**: "Headers already sent" error
- **Solution**: Make sure no BOM (Byte Order Mark) at start of PHP files
- **Solution**: Check no spaces or output before `<?php` tag

**Issue**: Session not persisting
- **Solution**: Check PHP session configuration
- **Solution**: Verify session directory is writable: `session.save_path`

**Issue**: Language not changing
- **Solution**: Clear browser cookies
- **Solution**: Check session is starting: `session_status()` should return `PHP_SESSION_ACTIVE`

## Expected Behavior Summary

✅ **Should Work**:
- Immediate language switch without white screen
- Language persists after page reload
- URL stays clean (no lang parameter in URL bar)
- Other URL parameters are preserved

❌ **Should NOT Happen**:
- White blank screen
- Need to refresh multiple times
- Need to click back button to see changes
- `?lang=my` or `?lang=en` stays in URL after switch

## Browser Compatibility Testing

Test on multiple browsers:
- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Opera

All should work consistently.

## Performance Check

The language switch should be:
- **Fast**: < 1 second for the redirect
- **Smooth**: No visible loading delays
- **Reliable**: Works 100% of the time

## Success Criteria

The fix is successful when:
1. ✅ No white screens appear when switching languages
2. ✅ Page loads immediately after clicking language button
3. ✅ Language persists across page refreshes
4. ✅ URL stays clean without lang parameters
5. ✅ No need to refresh or use back button
