<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors on screen to prevent breaking headers
ini_set('log_errors', 1);

// Start output buffering as early as possible to catch any whitespace
ob_start();

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle language switch
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'my'])) {
    $_SESSION['lang'] = $_GET['lang'];
    
    // Parse the current URL to preserve the path and other parameters
    $parsed_url = parse_url($_SERVER["REQUEST_URI"]);
    $redirect_url = $parsed_url['path'];
    
    // Preserve other GET parameters except 'lang'
    $get_params = $_GET;
    unset($get_params['lang']);
    if (!empty($get_params)) {
        $redirect_url .= '?' . http_build_query($get_params);
    }
    
    // Clear any output buffer before redirecting
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Redirect with absolute path
    header("Location: " . $redirect_url, true, 302);
    exit();
}

// Ensure output buffering is active for the rest of the page
if (ob_get_level() == 0) {
    ob_start();
}

// Language handling - set default if not exists
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Default language
}

$currentLang = $_SESSION['lang'];

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['name'] : '';
$userRole = $isLoggedIn ? $_SESSION['role'] : '';

$userImage = '';
$unreadMessagesCount = 0;
$pendingAppointmentsCount = 0;

if ($isLoggedIn && in_array($userRole, ['admin', 'doctor', 'hr'])) {
    try {
        require_once __DIR__ . '/../config/database.php';
        $stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        $userImage = !empty($user['profile_image']) ? $user['profile_image'] : '';
    } catch(PDOException $e) {
        $userImage = '';
    }
}

if ($isLoggedIn) {
    try {
        if (!isset($conn)) {
            require_once __DIR__ . '/../config/database.php';
        }
        
        if ($userRole == 'user') {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as unread 
                FROM messages 
                WHERE recipient_id = ? AND status = 'unread'
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $unreadMessagesCount = $result['unread'];
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as replied 
                FROM contact_messages 
                WHERE email = ? AND status = 'replied' AND reply_message IS NOT NULL
            ");
            $stmt->execute([$_SESSION['email']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $unreadMessagesCount += $result['replied'];
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as pending 
                FROM appointments 
                WHERE user_id = ? AND status IN ('pending', 'confirmed')
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $pendingAppointmentsCount = $result['pending'];
            
        } elseif ($userRole == 'doctor') {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as unread 
                FROM messages 
                WHERE recipient_id = ? AND status = 'unread'
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $unreadMessagesCount = $result['unread'];
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as today 
                FROM appointments 
                WHERE doctor_id = ? AND DATE(appointment_date) = CURDATE() AND status = 'confirmed'
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $pendingAppointmentsCount = $result['today'];
            
        } elseif ($userRole == 'admin') {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as unread 
                FROM messages 
                WHERE recipient_id = ? AND status = 'unread'
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $unreadMessagesCount = $result['unread'];
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as pending 
                FROM appointments 
                WHERE status = 'pending'
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $pendingAppointmentsCount = $result['pending'];
            
        } elseif ($userRole == 'hr') {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as unread 
                FROM messages 
                WHERE recipient_id = ? AND status = 'unread'
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $unreadMessagesCount = $result['unread'];
        }
        
    } catch(PDOException $e) {
        $unreadMessagesCount = 0;
        $pendingAppointmentsCount = 0;
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
$baseURL = '/MTSPRJ'; 

// Language translations
$translations = [
    'en' => [
        'home' => 'Home',
        'services' => 'Services',
        'about' => 'About Us',
        'doctors' => 'Doctors',
        'ai_qa' => 'AI Q&A',
        'contact' => 'Contact',
        'make_appointment' => 'Make Appointment',
        'login' => 'Login',
        'logout' => 'Logout',
        'my_appointments' => 'My Appointments',
        'messages' => 'Messages',
        'admin_dashboard' => 'Admin Dashboard',
        'appointments' => 'Appointments',
        'doctor_dashboard' => 'Doctor Dashboard',
        'hr_dashboard' => 'HR Dashboard',
        'reception_dashboard' => 'Reception Dashboard',
        'pharmacy_dashboard' => 'Pharmacy Dashboard',
        'billing_dashboard' => 'Billing Dashboard',
        'staff_dashboard' => 'Staff Dashboard',
        'new' => 'New'
    ],
    'my' => [
        'home' => 'ပင်မ',
        'services' => 'ဝန်ဆောင်မှုများ',
        'about' => 'အကြောင်း',
        'doctors' => 'ဆရာဝန်များ',
        'ai_qa' => 'AI မေးမြန်း',
        'contact' => 'ဆက်သွယ်ရန်',
        'make_appointment' => 'ရက်ချိန်းယူမည်',
        'login' => 'ဝင်ရန်',
        'logout' => 'ထွက်မည်',
        'my_appointments' => 'ကျွန်ုပ်၏ ရက်ချိန်းများ',
        'messages' => 'စာများ',
        'admin_dashboard' => 'စီမံခန့်ခွဲမှု',
        'appointments' => 'ရက်ချိန်းများ',
        'doctor_dashboard' => 'ဆရာဝန် ပြန်လည်စစ်ဆေး',
        'hr_dashboard' => 'HR စီမံခန့်ခွဲမှု',
        'reception_dashboard' => 'လက်ခံဌာန',
        'pharmacy_dashboard' => 'ဆေးဆိုင်',
        'billing_dashboard' => 'ငွေစာရင်း',
        'staff_dashboard' => 'ဝန်ထမ်း',
        'new' => 'အသစ်'
    ]
];

$t = $translations[$currentLang];

// Get current URL for language switching
function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    
    // Parse the URI to separate path and query
    $parsed = parse_url($uri);
    $path = $parsed['path'];
    
    // Build query parameters without 'lang'
    $query_params = [];
    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $query_params);
        unset($query_params['lang']);
    }
    
    // Rebuild URL without lang parameter
    $currentUrl = $protocol . "://" . $host . $path;
    if (!empty($query_params)) {
        $currentUrl .= '?' . http_build_query($query_params);
    }
    
    return $currentUrl;
}

$currentUrl = getCurrentUrl();
$langSeparator = (strpos($currentUrl, '?') !== false) ? '&' : '?';
?>
<!doctype html>
<html lang="<?php echo $currentLang; ?>">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MTS Hospital - Quality Healthcare Services</title>
  <link href="https://fonts.googleapis.com/css2?family=Tora+Serif+JP&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 64 64'%3E%3Cdefs%3E%3ClinearGradient id='mtsGrad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%23a74fdc'/%3E%3Cstop offset='100%25' stop-color='%235a3fd9'/%3E%3C/linearGradient%3E%3C/defs%3E%3Ccircle cx='32' cy='32' r='28' fill='url(%23mtsGrad)'/%3E%3Crect x='28' y='18' width='8' height='28' rx='2' fill='%23ffffff'/%3E%3Crect x='20' y='26' width='24' height='8' rx='2' fill='%23ffffff'/%3E%3Cpath d='M40 40c1.6-1.6 4.2-1.6 5.8 0s1.6 4.2 0 5.8L40 52l-5.8-6.2c-1.6-1.6-1.6-4.2 0-5.8s4.2-1.6 5.8 0z' fill='%23ffe6f0'/%3E%3C/svg%3E">

  <style>
    :root {
      --primary: #a74fdc;
      --primary-dark: #8a3ec2;
      --secondary: #f3e9ff;
      --text-dark: #2d234a;
    }

    body {
      font-family: 'times new roman', serif;
    }

    .topbar {
      background: linear-gradient(135deg, #a74fdc, #c98dee);
      color: white;
      font-size: 14px;
    }

    .topbar a {
      color: white;
      text-decoration: none;
      transition: 0.3s;
    }

    .topbar a:hover {
      color: #ffe6f0;
      text-decoration: underline;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .contact-item i {
      font-size: 14px;
    }

    .site-header {
      background: white;
      padding: 15px 0;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .logo {
      font-size: 22px;
      font-weight: 700;
      color: var(--primary);
      transition: 0.3s;
    }

    .logo:hover {
      color: var(--primary-dark);
    }

    .nav-link {
      color: var(--text-dark) !important;
      font-weight: 500;
      padding: 8px 18px !important;
      transition: 0.3s;
      position: relative;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--primary) !important;
    }

    .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 18px;
      right: 18px;
      height: 3px;
      background: var(--primary);
      border-radius: 2px;
    }

    .nav-link.ai-chat {
      background: linear-gradient(135deg, #a74fdc15, #5a3fd915);
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .nav-link.ai-chat:hover {
      background: linear-gradient(135deg, #a74fdc25, #5a3fd925);
    }

    .nav-link.ai-chat i {
      font-size: 16px;
    }

    .btn {
      padding: 10px 24px;
      border-radius: 25px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(167, 79, 220, 0.3);
    }

    .user-dropdown {
      position: relative;
    }

    .user-avatar {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
      border: 1px solid var(--primary);
      cursor: pointer;
    }

    .user-menu-btn {
      background: var(--secondary);
      color: var(--primary);
      border: 1px solid var(--primary);
      padding: 5px 15px;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    .user-menu-btn:hover {
      background: var(--primary);
      color: white;
    }

    .dropdown-menu-custom {
      display: none;
      position: absolute;
      top: 55px;
      right: 0;
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      min-width: 220px;
      z-index: 1001;
      overflow: hidden;
    }

    .dropdown-menu-custom.show {
      display: block;
    }

    .dropdown-item-custom {
      padding: 12px 20px;
      color: var(--text-dark);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: 0.3s;
      border-bottom: 1px solid #f0e6ff;
      position: relative;
    }

    .dropdown-item-custom:hover {
      background: var(--secondary);
      color: var(--primary);
    }

    .dropdown-item-custom:last-child {
      border-bottom: none;
      color: #dc3545;
    }

    .dropdown-item-custom:last-child:hover {
      background: #fee2e2;
      color: #991b1b;
    }

    .notification-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 6px;
      border-radius: 10px;
      min-width: 18px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
      animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.1);
      }
    }

    .dropdown-notification-badge {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 12px;
      min-width: 20px;
      text-align: center;
    }

    .nav-item-with-badge {
      position: relative;
    }

    #mobileNav {
      background: white;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    #mobileNav a {
      color: var(--text-dark);
      text-decoration: none;
      transition: 0.3s;
    }

    #mobileNav a:hover {
      color: var(--primary);
      padding-left: 10px;
    }

    .ai-badge {
      background: linear-gradient(135deg, #a74fdc, #5a3fd9);
      color: white;
      font-size: 9px;
      padding: 2px 6px;
      border-radius: 8px;
      margin-left: 4px;
      font-weight: 700;
      text-transform: uppercase;
    }

    .mobile-notification-badge {
      display: inline-block;
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 12px;
      margin-left: 8px;
      min-width: 20px;
      text-align: center;
    }

    /* ─── Language Pill Toggle ─── */
    .lang-toggle-group {
      display: flex;
      align-items: center;
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.28);
      border-radius: 22px;
      padding: 3px;
      gap: 2px;
    }

    .lang-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 14px;
      border-radius: 18px;
      border: none;
      background: transparent;
      color: rgba(255,255,255,0.72);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.22s ease;
      font-family: inherit;
      white-space: nowrap;
    }

    .lang-btn:hover {
      color: white;
      background: rgba(255,255,255,0.14);
    }

    .lang-btn.active {
      background: white;
      color: var(--primary);
      box-shadow: 0 2px 8px rgba(0,0,0,0.18);
    }

    .lang-btn .flag {
      font-size: 16px;
      line-height: 1;
    }
  </style>
</head>
<body>

  <div class="topbar">
    <div class="container d-flex justify-content-between align-items-center py-2">
      <div class="d-flex gap-4 align-items-center">
        <div class="contact-item">
          <i class="fa-solid fa-phone"></i>
          <a href="tel:+959977977977">+95 9977 977 977</a>
        </div>
        <div class="contact-item d-none d-md-flex">
          <i class="fa-regular fa-envelope"></i>
          <a href="mailto:info@mts-hospital.com">info@mts-hospital.com</a>
        </div>
        <div class="contact-item d-none d-lg-flex">
          <i class="fa-solid fa-location-dot"></i>
          <span><?php echo $currentLang == 'my' ? 'တောင်ငူ၊ မြန်မာ' : 'Taungoo, Myanmar'; ?></span>
        </div>
      </div>

      <!-- Right – language toggle -->
      <div class="lang-toggle-group">
        <a href="<?php echo $currentUrl . $langSeparator; ?>lang=my" class="lang-btn <?php echo $currentLang === 'my' ? 'active' : ''; ?>">
          <span class="flag">🇲🇲</span> MY
        </a>
        <a href="<?php echo $currentUrl . $langSeparator; ?>lang=en" class="lang-btn <?php echo $currentLang === 'en' ? 'active' : ''; ?>">
          <span class="flag">🇬🇧</span> EN
        </a>
      </div>
    </div>
  </div>

  <header class="site-header">
    <div class="container d-flex align-items-center justify-content-between">
      <a class="logo" href="<?php echo $baseURL; ?>/index.php" style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
        <svg width="32" height="32" viewBox="0 0 64 64" aria-hidden="true">
          <defs>
            <linearGradient id="mtsGrad" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#a74fdc"/>
              <stop offset="100%" stop-color="#5a3fd9"/>
            </linearGradient>
          </defs>
          <circle cx="32" cy="32" r="28" fill="url(#mtsGrad)"/>
          <rect x="28" y="18" width="8" height="28" rx="2" fill="#ffffff"/>
          <rect x="20" y="26" width="24" height="8" rx="2" fill="#ffffff"/>
          <path d="M40 40c1.6-1.6 4.2-1.6 5.8 0s1.6 4.2 0 5.8L40 52l-5.8-6.2c-1.6-1.6-1.6-4.2 0-5.8s4.2-1.6 5.8 0z" fill="#ffe6f0"/>
        </svg>
        <span>MTS Hospital</span>
      </a>

      <nav class="d-none d-lg-block">
        <ul class="nav align-items-center">
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/index.php"><?php echo $t['home']; ?></a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'health-articles.php') ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/health-articles.php"><?php echo $t['services']; ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/about.php"><?php echo $t['about']; ?></a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'doctors.php') ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/doctors.php"><?php echo $t['doctors']; ?></a>
          </li>

          <?php if ($isLoggedIn): ?>
          <li class="nav-item">
            <a class="nav-link ai-chat <?php echo ($currentPage == 'medical-chat.php') ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/medical-chat.php">
              <i class="fa-solid fa-robot"></i>
              <span><?php echo $t['ai_qa']; ?></span>
              <span class="ai-badge"><?php echo $t['new']; ?></span>
            </a>
          </li>
          <?php endif; ?>

          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>" href="<?php echo $baseURL; ?>/pages/contact.php"><?php echo $t['contact']; ?></a>
          </li>
        </ul>
      </nav>

      <div class="d-flex align-items-center gap-3">
        <a href="<?php echo $baseURL; ?>/pages/book-appointment.php" class="btn d-none d-md-inline-block" style="border: 1px solid var(--primary); color: var(--primary);">
          <?php echo $t['make_appointment']; ?>
        </a>

        <?php if ($isLoggedIn): ?>
          <div class="user-dropdown">
            <button class="user-menu-btn d-flex align-items-center gap-2" onclick="toggleUserMenu()">
              <?php if ($userImage): ?>
                <img src="<?php echo htmlspecialchars($baseURL . '/' . $userImage); ?>" alt="<?php echo htmlspecialchars($userName); ?>" class="user-avatar">
              <?php else: ?>
                <i class="fa-solid fa-user-circle" style="font-size: 24px;"></i>
              <?php endif; ?>
              <span class="d-none d-md-inline"><?php echo htmlspecialchars(explode(' ', $userName)[0]); ?></span>
              <i class="fa-solid fa-chevron-down" style="font-size: 12px;"></i>
              
              <?php 
              $totalNotifications = ($userRole == 'hr') ? $unreadMessagesCount : ($unreadMessagesCount + $pendingAppointmentsCount);
              if ($totalNotifications > 0): 
              ?>
                <span class="notification-badge" id="headerNotificationBadge"><?php echo $totalNotifications > 99 ? '99+' : $totalNotifications; ?></span>
              <?php endif; ?>
            </button>

            <div id="userDropdownMenu" class="dropdown-menu-custom">
              <?php if ($userRole == 'user'): ?>
              <a href="<?php echo $baseURL; ?>/pages/my-appointment.php" class="dropdown-item-custom">
                <i class="fa-solid fa-calendar-check"></i>
                <span><?php echo $t['my_appointments']; ?></span>
                <?php if ($pendingAppointmentsCount > 0): ?>
                  <span class="dropdown-notification-badge" id="appointmentBadge"><?php echo $pendingAppointmentsCount; ?></span>
                <?php endif; ?>
              </a>
              <a href="<?php echo $baseURL; ?>/pages/my-messages.php" class="dropdown-item-custom">
                <i class="fa-solid fa-envelope"></i>
                <span><?php echo $t['messages']; ?></span>
                <?php if ($unreadMessagesCount > 0): ?>
                  <span class="dropdown-notification-badge" id="messageBadge"><?php echo $unreadMessagesCount; ?></span>
                <?php endif; ?>
              </a>
              
              <?php elseif ($userRole == 'admin'): ?>
              <a href="<?php echo $baseURL; ?>/admin/dashboard.php" class="dropdown-item-custom">
                <i class="fa-solid fa-gauge"></i>
                <span><?php echo $t['admin_dashboard']; ?></span>
              </a>
              <a href="<?php echo $baseURL; ?>/admin/appointments.php" class="dropdown-item-custom">
                <i class="fa-solid fa-calendar-check"></i>
                <span><?php echo $t['appointments']; ?></span>
                <?php if ($pendingAppointmentsCount > 0): ?>
                  <span class="dropdown-notification-badge" id="appointmentBadge"><?php echo $pendingAppointmentsCount; ?></span>
                <?php endif; ?>
              </a>
              <a href="<?php echo $baseURL; ?>/admin/messages.php" class="dropdown-item-custom">
                <i class="fa-solid fa-envelope"></i>
                <span><?php echo $t['messages']; ?></span>
                <?php if ($unreadMessagesCount > 0): ?>
                  <span class="dropdown-notification-badge" id="messageBadge"><?php echo $unreadMessagesCount; ?></span>
                <?php endif; ?>
              </a>
              
              <?php elseif ($userRole == 'doctor'): ?>
              <a href="<?php echo $baseURL; ?>/doctor/dashboard.php" class="dropdown-item-custom">
                <i class="fa-solid fa-user-doctor"></i>
                <span><?php echo $t['doctor_dashboard']; ?></span>
              </a>
              
              <a href="<?php echo $baseURL; ?>/doctor/messages.php" class="dropdown-item-custom">
                <i class="fa-solid fa-envelope"></i>
                <span><?php echo $t['messages']; ?></span>
                <?php if ($unreadMessagesCount > 0): ?>
                  <span class="dropdown-notification-badge" id="messageBadge"><?php echo $unreadMessagesCount; ?></span>
                <?php endif; ?>
              </a>
              
              <?php elseif ($userRole == 'hr'): ?>
              <a href="<?php echo $baseURL; ?>/hr/dashboard.php" class="dropdown-item-custom">
                <i class="fa-solid fa-users-gear"></i>
                <span><?php echo $t['hr_dashboard']; ?></span>
              </a>
              
              
              <?php elseif (in_array($userRole, ['reception'])): ?>
              <a href="<?php echo $baseURL; ?>/staff/reception/dashboard.php" class="dropdown-item-custom">
                <i class="fa-solid fa-clipboard-user"></i>
                <span><?php echo $t['reception_dashboard']; ?></span>
              </a>
              

              <?php elseif (in_array($userRole, [ 'pharmacy'])): ?>
              <a href="<?php echo $baseURL; ?>/staff/pharmacy/dashboard.php" class="dropdown-item-custom">
                <i class="fa-solid fa-clipboard-user"></i>
                <span><?php echo $t['pharmacy_dashboard']; ?></span>
              </a>
                
              <?php elseif (in_array($userRole, ['billing'])): ?>
              <a href="<?php echo $baseURL; ?>/staff/billing/dashboard.php" class="dropdown-item-custom">
                <i class="fa-solid fa-clipboard-user"></i>
                <span><?php echo $t['billing_dashboard']; ?></span>
              </a>

              <?php elseif (in_array($userRole, ['staff'])): ?>
              <a href="<?php echo $baseURL; ?>/staff/dashboard.php" class="dropdown-item-custom">
                <i class="fa-solid fa-clipboard-user"></i>
                <span><?php echo $t['staff_dashboard']; ?></span>
              </a>
              <?php endif; ?>
              
              
              <a href="<?php echo $baseURL; ?>/auth/logout.php" class="dropdown-item-custom">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span><?php echo $t['logout']; ?></span>
              </a>
            </div>
          </div>
        <?php else: ?>
          <a href="<?php echo $baseURL; ?>/auth/login.php" class="btn" style="background: var(--primary); color: white;">
            <?php echo $t['login']; ?>
          </a>
        <?php endif; ?>

        <button class="btn btn-light d-lg-none" id="mobileMenuBtn">
          <i class="fa fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- CLEANED MOBILE NAVIGATION - Only Main Links -->
  <div id="mobileNav" style="display:none;">
    <div class="container py-3">
      <ul class="list-unstyled mb-0">
        <li class="py-2"><a href="<?php echo $baseURL; ?>/index.php"><?php echo $t['home']; ?></a></li>
        <li class="py-2"><a href="<?php echo $baseURL; ?>/pages/health-articles.php"><?php echo $t['services']; ?></a></li>
        <li class="py-2"><a href="<?php echo $baseURL; ?>/pages/about.php"><?php echo $t['about']; ?></a></li>
        <li class="py-2"><a href="<?php echo $baseURL; ?>/pages/doctors.php"><?php echo $t['doctors']; ?></a></li>
        
        <?php if ($isLoggedIn): ?>
        <li class="py-2">
          <a href="<?php echo $baseURL; ?>/pages/medical-chat.php" style="display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-robot"></i>
            <span><?php echo $t['ai_qa']; ?></span>
            <span class="ai-badge"><?php echo $t['new']; ?></span>
          </a>
        </li>
        <?php endif; ?>

        <li class="py-2"><a href="<?php echo $baseURL; ?>/pages/contact.php"><?php echo $t['contact']; ?></a></li>
        
        <?php if ($isLoggedIn): ?>
          <li class="py-2" style="border-top: 1px solid #eee; margin-top: 10px; padding-top: 15px !important;">
            <a href="<?php echo $baseURL; ?>/auth/logout.php" style="color: #dc3545;">
              <i class="fa-solid fa-right-from-bracket"></i> <?php echo $t['logout']; ?>
            </a>
          </li>
        <?php else: ?>
          <li class="py-2" style="border-top: 1px solid #eee; margin-top: 10px; padding-top: 15px !important;">
            <a href="<?php echo $baseURL; ?>/auth/login.php">
              <i class="fa-solid fa-right-to-bracket"></i> <?php echo $t['login']; ?>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');

    if (mobileBtn && mobileNav) {
      mobileBtn.addEventListener('click', () => {
        mobileNav.style.display = mobileNav.style.display === 'none' ? 'block' : 'none';
      });
    }

    function toggleUserMenu() {
      const dropdown = document.getElementById('userDropdownMenu');
      dropdown.classList.toggle('show');
    }

    window.addEventListener('click', function(e) {
      const dropdown = document.getElementById('userDropdownMenu');
      const userBtn = e.target.closest('.user-menu-btn');
      
      if (!userBtn && dropdown && dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
      }
    });

    // Auto-refresh notification badges every 30 seconds
    function refreshNotificationBadges() {
      fetch('<?php echo $baseURL; ?>/ajax/get-notification-counts.php', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const headerBadge = document.getElementById('headerNotificationBadge');
          const messageBadge = document.getElementById('messageBadge');
          const appointmentBadge = document.getElementById('appointmentBadge');
          
          const totalCount = <?php echo $userRole == 'hr' ? 'data.messages' : '(data.messages + data.appointments)'; ?>;
          
          if (totalCount > 0) {
            if (headerBadge) {
              headerBadge.textContent = totalCount > 99 ? '99+' : totalCount;
              headerBadge.style.display = 'block';
            } else if (!headerBadge && totalCount > 0) {
              const userBtn = document.querySelector('.user-menu-btn');
              const newBadge = document.createElement('span');
              newBadge.className = 'notification-badge';
              newBadge.id = 'headerNotificationBadge';
              newBadge.textContent = totalCount > 99 ? '99+' : totalCount;
              userBtn.appendChild(newBadge);
            }
          } else {
            if (headerBadge) headerBadge.remove();
          }
          
          if (messageBadge) {
            if (data.messages > 0) {
              messageBadge.textContent = data.messages;
              messageBadge.style.display = 'block';
            } else {
              messageBadge.remove();
            }
          }
          
          <?php if ($userRole != 'hr'): ?>
          if (appointmentBadge) {
            if (data.appointments > 0) {
              appointmentBadge.textContent = data.appointments;
              appointmentBadge.style.display = 'block';
            } else {
              appointmentBadge.remove();
            }
          }
          <?php endif; ?>
        }
      })
      .catch(error => {
        console.error('Badge refresh error:', error);
      });
    }

    <?php if ($isLoggedIn): ?>
    setInterval(refreshNotificationBadges, 30000);
    <?php endif; ?>
  </script>

<?php if ($isLoggedIn && $userRole != 'hr'): ?>
<style>
.notification-popup-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 10000;
    width: 350px;
    max-width: calc(100vw - 40px);
}

.notification-popup {
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    padding: 16px;
    margin-bottom: 12px;
    border-left: 4px solid #a74fdc;
    animation: slideInRight 0.4s ease-out;
    position: relative;
    overflow: hidden;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(400px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification-popup.fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

@keyframes fadeOut {
    to {
        opacity: 0;
        transform: translateX(400px);
    }
}

.notification-popup-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.notification-popup-title {
    font-size: 14px;
    font-weight: 700;
    color: #2d234a;
    display: flex;
    align-items: center;
    gap: 8px;
}

.notification-popup-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #a74fdc, #c98dee);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.notification-popup-close {
    background: none;
    border: none;
    color: #999;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.notification-popup-close:hover {
    color: #dc3545;
    transform: rotate(90deg);
}

.notification-popup-body {
    font-size: 13px;
    color: #555;
    line-height: 1.5;
    margin-bottom: 12px;
}

.notification-popup-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notification-popup-time {
    font-size: 11px;
    color: #999;
}

.notification-popup-link {
    background: linear-gradient(135deg, #a74fdc, #c98dee);
    color: white;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: 0.3s;
}

.notification-popup-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(167, 79, 220, 0.4);
    color: white;
}

.notification-sound {
    display: none;
}

@media (max-width: 768px) {
    .notification-popup-container {
        top: 70px;
        right: 10px;
        left: 10px;
        width: auto;
    }
}
</style>

<div class="notification-popup-container" id="notificationContainer"></div>
<audio class="notification-sound" id="notificationSound">
    <source src="<?php echo $baseURL; ?>/assets/sounds/notification.mp3" type="audio/mpeg">
</audio>

<script>
let lastNotificationCheck = Date.now();
const notificationSound = document.getElementById('notificationSound');
let shownNotifications = new Set();

function showNotificationPopup(notification) {
    const container = document.getElementById('notificationContainer');
    const notificationId = `notif-${Date.now()}-${Math.random()}`;
    
    const popup = document.createElement('div');
    popup.className = 'notification-popup';
    popup.id = notificationId;
    
    const timeAgo = getTimeAgo(notification.time);
    
    popup.innerHTML = `
        <div class="notification-popup-header">
            <div class="notification-popup-title">
                <div class="notification-popup-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <span>${escapeHtml(notification.title)}</span>
            </div>
            <button class="notification-popup-close" onclick="closeNotification('${notificationId}')">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="notification-popup-body">
            ${escapeHtml(notification.message)}
        </div>
        <div class="notification-popup-footer">
            <span class="notification-popup-time">
                <i class="fa-solid fa-clock"></i> ${timeAgo}
            </span>
            <a href="${notification.link}" class="notification-popup-link">
                <?php echo $currentLang == 'my' ? 'စာကြည့်ရန်' : 'View Message'; ?>
            </a>
        </div>
    `;
    
    container.insertBefore(popup, container.firstChild);
    
    try {
        notificationSound.play().catch(e => console.log('Sound play failed:', e));
    } catch(e) {
        console.log('Sound play error:', e);
    }
    
    setTimeout(() => {
        closeNotification(notificationId);
    }, 8000);
}

function closeNotification(notificationId) {
    const popup = document.getElementById(notificationId);
    if (popup) {
        popup.classList.add('fade-out');
        setTimeout(() => {
            popup.remove();
        }, 300);
    }
}

function getTimeAgo(timestamp) {
    const now = new Date();
    const time = new Date(timestamp);
    const diff = Math.floor((now - time) / 1000);
    
    <?php if ($currentLang == 'my'): ?>
    if (diff < 60) return 'ယခု';
    if (diff < 3600) return Math.floor(diff / 60) + ' မိနစ် အရင်';
    if (diff < 86400) return Math.floor(diff / 3600) + ' နာရီ အရင်';
    return Math.floor(diff / 86400) + ' ရက် အရင်';
    <?php else: ?>
    if (diff < 60) return 'Just now';
    if (diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
    if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
    return Math.floor(diff / 86400) + ' days ago';
    <?php endif; ?>
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function checkNotifications() {
    fetch('<?php echo $baseURL; ?>/ajax/check-notifications.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.notifications && data.notifications.length > 0) {
            data.notifications.forEach(notification => {
                const notifKey = `${notification.type}-${notification.time}`;
                if (!shownNotifications.has(notifKey)) {
                    showNotificationPopup(notification);
                    shownNotifications.add(notifKey);
                }
            });
        }
        
        refreshNotificationBadges();
    })
    .catch(error => {
        console.error('Notification check error:', error);
    });
}

setInterval(checkNotifications, 30000);
setTimeout(checkNotifications, 5000);
</script>
<?php endif; ?>

</body>
</html>
<?php
// Flush output buffer
if (ob_get_level() > 0) {
    ob_end_flush();
}
?>

