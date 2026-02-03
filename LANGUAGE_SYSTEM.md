# Language System Documentation

## Overview
The MTS Hospital website uses a session-based language switching system supporting English and Myanmar languages.

## Architecture

### Language Storage
- **Session Variable**: `$_SESSION['lang']`
- **Default Language**: 'en' (English)
- **Supported Languages**: 
  - 'en' - English
  - 'my' - Myanmar (Burmese)

### File Structure
```
index.php
├── Language detection (lines 1-40)
├── Translation array (lines 150-198)
├── getCurrentUrl() function (lines 202-225)
└── Language toggle buttons (lines 580-587)
```

## How It Works

### 1. Language Detection Flow
```
Page Load
    ↓
Check session for language
    ↓
If not set → Default to 'en'
    ↓
Check for ?lang parameter
    ↓
If present → Update session → Redirect
    ↓
Load page with session language
```

### 2. Translation System

Translations are stored in an associative array:

```php
$translations = [
    'en' => [
        'home' => 'Home',
        'services' => 'Services',
        // ... more translations
    ],
    'my' => [
        'home' => 'ပင်မ',
        'services' => 'ဝန်ဆောင်မှုများ',
        // ... more translations
    ]
];
```

Usage in templates:
```php
<a href="#"><?php echo $t['home']; ?></a>
```

### 3. Language Toggle Buttons

The language buttons are constructed using:

```php
<a href="<?php echo $currentUrl . $langSeparator; ?>lang=my">
  <span class="flag">🇲🇲</span> MY
</a>
```

Where:
- `$currentUrl` - Current page URL without lang parameter
- `$langSeparator` - Either '?' or '&' based on existing params
- `lang=my` - Language parameter

## Code Components

### Output Buffering
```php
ob_start();           // Start at beginning
ob_end_clean();       // Clear before redirect
ob_end_flush();       // Flush at end of page
```

**Purpose**: Prevents "headers already sent" errors

### Session Management
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

**Purpose**: Ensures session is started only once

### URL Parsing
```php
$parsed_url = parse_url($_SERVER["REQUEST_URI"]);
$redirect_url = $parsed_url['path'];
```

**Purpose**: Safely extracts path and query parameters

### Redirect Logic
```php
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'my'])) {
    $_SESSION['lang'] = $_GET['lang'];
    ob_end_clean();
    header("Location: " . $redirect_url);
    exit();
}
```

**Purpose**: Updates session and redirects to clean URL

## Adding New Languages

To add a new language (e.g., Chinese):

1. **Add to validation array** (line 20):
```php
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'my', 'zh'])) {
```

2. **Add translations** (around line 150):
```php
$translations = [
    'en' => [...],
    'my' => [...],
    'zh' => [
        'home' => '首页',
        'services' => '服务',
        // ... more translations
    ]
];
```

3. **Add language button** (around line 580):
```php
<a href="<?php echo $currentUrl . $langSeparator; ?>lang=zh" 
   class="lang-btn <?php echo $currentLang === 'zh' ? 'active' : ''; ?>">
    <span class="flag">🇨🇳</span> ZH
</a>
```

## Translation Keys

Current translation keys used in the system:

| Key | English | Myanmar |
|-----|---------|---------|
| home | Home | ပင်မ |
| services | Services | ဝန်ဆောင်မှုများ |
| about | About | အကြောင်း |
| doctors | Doctors | ဆရာဝန်များ |
| ai_qa | AI Q&A | AI မေးမြန်း |
| contact | Contact | ဆက်သွယ်ရန် |
| make_appointment | Make Appointment | ရက်ချိန်းယူမည် |
| login | Login | ဝင်ရန် |
| logout | Logout | ထွက်မည် |
| my_appointments | My Appointments | ကျွန်ုပ်၏ ရက်ချိန်းများ |
| messages | Messages | စာများ |
| admin_dashboard | Admin Dashboard | စီမံခန့်ခွဲမှု |
| appointments | Appointments | ရက်ချိန်းများ |
| doctor_dashboard | Doctor Dashboard | ဆရာဝန် ပြန်လည်စစ်ဆေး |
| hr_dashboard | HR Dashboard | HR စီမံခန့်ခွဲမှု |
| reception_dashboard | Reception Dashboard | လက်ခံဌာန |
| pharmacy_dashboard | Pharmacy Dashboard | ဆေးဆိုင် |
| billing_dashboard | Billing Dashboard | ငွေစာရင်း |
| staff_dashboard | Staff Dashboard | ဝန်ထမ်း |
| new | New | အသစ် |

## Best Practices

### 1. Always use translation keys
❌ **Don't**:
```php
<h1>Welcome to MTS Hospital</h1>
```

✅ **Do**:
```php
<h1><?php echo $t['welcome']; ?></h1>
```

### 2. Inline translations for short content
For short dynamic content:
```php
<?php echo $currentLang == 'my' ? 'တောင်ငူ၊ မြန်မာ' : 'Taungoo, Myanmar'; ?>
```

### 3. No hardcoded language text
All visible text should be translatable

### 4. Consistent key naming
Use lowercase with underscores:
- ✅ `make_appointment`
- ❌ `makeAppointment`
- ❌ `Make-Appointment`

## Troubleshooting

### Language not switching
1. Check session is started
2. Verify translation keys exist
3. Clear browser cache/cookies

### Translations missing
1. Check translation array has the key
2. Verify language code is correct ('en' or 'my')
3. Check for typos in key names

### Wrong language displays
1. Check `$_SESSION['lang']` value
2. Verify URL parameters are processed correctly
3. Clear sessions: `session_destroy()`

## Security Considerations

1. **Input Validation**: Only 'en' and 'my' are accepted
```php
in_array($_GET['lang'], ['en', 'my'])
```

2. **XSS Prevention**: Always escape output
```php
htmlspecialchars($t['key'], ENT_QUOTES, 'UTF-8')
```

3. **Session Security**: Use secure session settings in production
```php
session_set_cookie_params([
    'lifetime' => 0,
    'secure' => true,      // HTTPS only
    'httponly' => true,    // No JavaScript access
    'samesite' => 'Strict' // CSRF protection
]);
```

## Performance

### Session Storage
- Fast: O(1) lookup time
- Persistent: Survives page reloads
- Lightweight: Minimal memory usage

### Translation Loading
- All translations loaded on page load
- No database queries needed
- Cached in PHP opcode cache

### URL Parsing
- Uses native PHP `parse_url()` - very fast
- Minimal regex operations
- No external dependencies

## Future Enhancements

Possible improvements:
1. **Database-driven translations** - For dynamic content
2. **AJAX language switching** - No page reload needed
3. **Browser language detection** - Auto-select language
4. **RTL support** - For Arabic, Hebrew, etc.
5. **Translation management UI** - Admin panel for translations
6. **Fallback language** - Show English if translation missing
7. **Language in URL path** - `/en/` or `/my/` in URL
