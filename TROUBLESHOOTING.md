# Language Switching Troubleshooting Guide

## The Original Problem

### What Users Experienced
When clicking the Myanmar (🇲🇲 MY) language button:

1. **URL changed** to: `http://localhost/MTSPrj/index.php?lang=my`
2. **Blank white screen** appeared
3. **Refreshing didn't help** - still blank white screen
4. **Only workaround**: Click back button → refresh page → finally see language changed

This was a critical UX issue making the site appear broken.

## Why It Happened

### Technical Explanation

The white screen occurred due to **output buffer mismanagement** combined with **silent PHP errors**. Here's the failure sequence:

#### Original Code Flow (BROKEN)
```
1. Start output buffering: ob_start()
2. Start session
3. Check for ?lang parameter
4. If found:
   a. Set session language
   b. Try to clear buffer: ob_end_clean()
   c. Try to send redirect header
   d. ❌ FAILURE: Header already sent OR buffer error
5. Page execution continues but output is broken
6. User sees: BLANK WHITE SCREEN
```

### Specific Causes

#### Cause 1: Header Already Sent
If any output occurred before the `header()` call (even whitespace), PHP would fail silently:
```php
// Any output here (even a space or BOM)
<?php
header("Location: index.php"); // ❌ FAILS - headers already sent
```

#### Cause 2: Buffer Management Error
```php
ob_start();
// ... some code ...
ob_end_clean(); // Clear buffer
// ... more code outputs something ...
header("Location: ..."); // ❌ FAILS - output already sent
```

#### Cause 3: Nested Buffers
If multiple output buffers were active, `ob_end_clean()` would only clear one:
```php
ob_start(); // Buffer 1
ob_start(); // Buffer 2
ob_end_clean(); // Only clears Buffer 2
header("Location: ..."); // ❌ FAILS - Buffer 1 still has output
```

#### Cause 4: Silent Errors
```php
ini_set('display_errors', 0); // Errors hidden
// ... error occurs during redirect ...
// User sees: BLANK WHITE SCREEN (error is hidden)
```

## The Fix

### New Code Flow (WORKING)
```
1. Start output buffering IMMEDIATELY: ob_start()
2. Start session
3. Check for ?lang parameter
4. If found:
   a. Set session language
   b. Build redirect URL
   c. Clear ALL buffers: while(ob_get_level() > 0) ob_end_clean()
   d. Send redirect header with explicit status: header(..., true, 302)
   e. Exit immediately: exit()
5. Ensure buffer is active: if(ob_get_level() == 0) ob_start()
6. Load page normally
7. Flush buffer safely: if(ob_get_level() > 0) ob_end_flush()
```

### Key Improvements

#### 1. Early Buffer Start
```php
<?php
// Start output buffering as early as possible to catch any whitespace
ob_start();
```
**Why**: Catches any accidental whitespace, BOM characters, or early output

#### 2. Clear ALL Buffers
```php
// Clear any output buffer before redirecting
while (ob_get_level() > 0) {
    ob_end_clean();
}
```
**Why**: Handles nested buffers by clearing all levels

#### 3. Explicit Redirect Status
```php
header("Location: " . $redirect_url, true, 302);
exit();
```
**Why**: 
- `true` replaces previous headers
- `302` is explicit temporary redirect status
- `exit()` ensures no further code runs

#### 4. Safe Buffer Operations
```php
// Ensure buffer exists before flushing
if (ob_get_level() > 0) {
    ob_end_flush();
}
```
**Why**: Prevents errors when buffer doesn't exist

## Common Issues and Solutions

### Issue 1: White Screen Still Appears

**Possible Causes:**
- Browser cache showing old version
- Session not writable
- PHP errors occurring

**Solutions:**
```bash
# Clear browser cache
Ctrl + Shift + Delete (or Cmd + Shift + Delete on Mac)

# Check PHP error log
tail -f /var/log/php/error.log

# Test session
<?php
session_start();
$_SESSION['test'] = 'works';
var_dump($_SESSION); // Should show: array('test' => 'works')
?>

# Check output buffering
<?php
ob_start();
echo "Test";
var_dump(ob_get_level()); // Should show: int(1)
ob_end_flush();
?>
```

### Issue 2: Language Doesn't Change

**Possible Causes:**
- Session not persisting
- Redirect not happening
- Translation array missing

**Solutions:**
```php
// Debug session
<?php
session_start();
var_dump($_SESSION); // Should show language
?>

// Debug redirect
<?php
if (isset($_GET['lang'])) {
    echo "Lang parameter detected: " . $_GET['lang'];
    $_SESSION['lang'] = $_GET['lang'];
    echo "Session set to: " . $_SESSION['lang'];
}
?>

// Debug translations
<?php
var_dump($translations); // Should show en and my arrays
var_dump($t); // Should show current language translations
?>
```

### Issue 3: "Headers Already Sent" Error

**Visible when `display_errors = 1`:**
```
Warning: Cannot modify header information - headers already sent by (output started at index.php:15)
```

**Solutions:**
```php
// Remove any whitespace or BOM before <?php
// Save file with UTF-8 WITHOUT BOM encoding
// Ensure ob_start() is very early in the file

// Check for early output
<?php
if (headers_sent($file, $line)) {
    echo "Headers already sent in $file on line $line";
}
?>
```

### Issue 4: Redirect Loop

**Symptom:** Browser says "Too many redirects"

**Cause:** Language keeps being added back to URL

**Solution:**
```php
// Ensure lang parameter is removed from redirect URL
$get_params = $_GET;
unset($get_params['lang']); // ← Critical line
if (!empty($get_params)) {
    $redirect_url .= '?' . http_build_query($get_params);
}
```

### Issue 5: URL Still Shows ?lang= Parameter

**Expected:** URL should be clean after redirect: `index.php`  
**Actual:** URL shows: `index.php?lang=my`

**Cause:** Redirect not happening

**Solutions:**
```php
// Debug redirect
if (isset($_GET['lang'])) {
    error_log("Attempting redirect...");
    header("Location: " . $redirect_url);
    error_log("After header (shouldn't see this)");
    exit();
}

// Check Apache mod_rewrite isn't interfering
# In .htaccess:
RewriteCond %{QUERY_STRING} ^lang=
RewriteRule .* - [L] # Might be blocking redirect
```

## Testing Checklist

### Basic Tests
- [ ] Click MY button → Page switches to Myanmar instantly
- [ ] Click EN button → Page switches to English instantly
- [ ] No white screens appear
- [ ] URL is clean (no ?lang= after redirect)
- [ ] Refresh page → Language persists
- [ ] Close and reopen browser → Language persists (session-based)

### Edge Case Tests
- [ ] Direct URL: `index.php?lang=my` → Should redirect to clean URL
- [ ] Invalid language: `index.php?lang=xx` → Should ignore and use default
- [ ] No session support → Should default to English
- [ ] JavaScript disabled → Language switching still works

### Browser Tests
- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile browsers

### Performance Tests
- [ ] Language switch completes in < 1 second
- [ ] No delay or loading spinner needed
- [ ] Smooth user experience

## Debugging Tools

### PHP Configuration Check
```php
<?php
phpinfo(); // Check output_buffering setting
echo "Buffer Level: " . ob_get_level();
echo "Session Status: " . session_status();
?>
```

### Session Debug
```php
<?php
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
```

### Output Buffer Debug
```php
<?php
ob_start();
echo "Test output";
echo "Buffer level: " . ob_get_level();
echo "Buffer length: " . ob_get_length();
echo "Buffer contents: " . ob_get_contents();
ob_end_flush();
?>
```

### Redirect Debug
```php
<?php
// Enable error display temporarily
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['lang'])) {
    echo "Before redirect...<br>";
    echo "Lang: " . $_GET['lang'] . "<br>";
    echo "Redirect URL: " . $redirect_url . "<br>";
    echo "Headers sent: " . (headers_sent() ? 'YES' : 'NO') . "<br>";
    // header("Location: " . $redirect_url); // Uncomment to test
    echo "After header...<br>";
}
?>
```

## Prevention Tips

### For Future Development

1. **Always start output buffering early**
   ```php
   <?php
   ob_start(); // FIRST thing after <?php
   ```

2. **Check buffer level before operations**
   ```php
   if (ob_get_level() > 0) {
       ob_end_clean(); // Safe
   }
   ```

3. **Use explicit redirect status codes**
   ```php
   header("Location: $url", true, 302); // Clear and explicit
   ```

4. **Always exit after redirects**
   ```php
   header("Location: $url");
   exit(); // Prevents further execution
   ```

5. **Log errors instead of displaying them**
   ```php
   ini_set('display_errors', 0);
   ini_set('log_errors', 1);
   ```

6. **Validate input properly**
   ```php
   if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'my'])) {
       // Safe to use
   }
   ```

## When to Ask for Help

Contact support if:
- White screens persist after applying fix
- Language switching works but is slow (> 2 seconds)
- Browser console shows JavaScript errors
- PHP error logs show repeated errors
- Session data not persisting between pages

## Additional Resources

- **PHP Output Buffering**: https://www.php.net/manual/en/book.outcontrol.php
- **PHP Header Function**: https://www.php.net/manual/en/function.header.php
- **PHP Sessions**: https://www.php.net/manual/en/book.session.php
- **HTTP Status Codes**: https://developer.mozilla.org/en-US/docs/Web/HTTP/Status

## Quick Reference

### Check if fix is applied
```bash
# Check line 7-8 in index.php
grep -n "ob_start()" index.php | head -1
# Should show: 8:ob_start()

# Check line 30-33 in index.php
grep -A2 "Clear any output buffer" index.php
# Should show while loop clearing all buffers
```

### Verify current language
```php
<?php
session_start();
echo "Current language: " . ($_SESSION['lang'] ?? 'not set');
?>
```

### Test redirect manually
```bash
# Using curl to see redirect
curl -I "http://localhost/MTSPrj/index.php?lang=my"
# Should show: Location: /MTSPrj/index.php
# Should show: HTTP/1.1 302 Found
```

## Summary

The fix resolves the white screen issue by:
1. ✅ Starting output buffering immediately
2. ✅ Clearing all nested buffers before redirect
3. ✅ Using explicit redirect headers
4. ✅ Properly exiting after redirect
5. ✅ Safely managing buffers throughout page lifecycle

**Result**: Smooth, instant language switching with zero white screens! 🎉
