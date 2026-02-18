<?php 
if(empty($connection)){
  header('location:./404');
} else {
// If user is logged in, redirect to home/dashboard
if(isset($_COOKIE['COOKIES_MEMBER']) && !empty($_COOKIE['COOKIES_MEMBER'])){
  header('location:./home');
  exit;
}

// Get shift/schedule data
$query_shift = "SELECT * FROM shift ORDER BY shift_id ASC";
$result_shift = $connection->query($query_shift);
$shifts = [];
if($result_shift && $result_shift->num_rows > 0){
  while($row = $result_shift->fetch_assoc()){
    $shifts[] = $row;
  }
}

// Get building/location data
$query_building = "SELECT * FROM building LIMIT 1";
$result_building = $connection->query($query_building);
$building = null;
if($result_building && $result_building->num_rows > 0){
  $building = $result_building->fetch_assoc();
}

// Get active posters
$query_poster = "SELECT * FROM poster WHERE active='Y' ORDER BY created_at DESC LIMIT 10";
$result_poster = $connection->query($query_poster);
$posters = [];
if($result_poster && $result_poster->num_rows > 0){
  while($row = $result_poster->fetch_assoc()){
    $posters[] = $row;
  }
}

// Get active gallery items
$query_galeri = "SELECT * FROM galeri WHERE active='Y' ORDER BY created_at DESC LIMIT 20";
$result_galeri = $connection->query($query_galeri);

// Load landing page settings
$ls = [];
$q_ls = $connection->query("SELECT setting_key, setting_value FROM landing_settings");
if($q_ls && $q_ls->num_rows > 0){
  while($r_ls = $q_ls->fetch_assoc()){
    $ls[$r_ls['setting_key']] = $r_ls['setting_value'];
  }
}
// Helper function
function ls($ls, $key, $default = '') {
    return isset($ls[$key]) && $ls[$key] !== '' ? $ls[$key] : $default;
}
$galeris = [];
if($result_galeri && $result_galeri->num_rows > 0){
  while($row = $result_galeri->fetch_assoc()){
    $galeris[] = $row;
  }
}

// Get active athletes
$query_atlet = "SELECT * FROM atlet WHERE active='Y' ORDER BY created_at DESC LIMIT 20";
$result_atlet = $connection->query($query_atlet);
$atlets = [];
if($result_atlet && $result_atlet->num_rows > 0){
  while($row = $result_atlet->fetch_assoc()){
    $atlets[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
  <title><?php echo $website_name; ?> — Halim Karate Champion</title>
  <meta name="description" content="<?php echo $meta_description; ?>">
  <meta name="keywords" content="karate, dojo, HKC, Halim Karate Champion, INKANAS, absensi">
  <link rel="shortcut icon" href="<?php echo $base_url; ?>/sw-content/favicon.png">
  <link rel="apple-touch-icon" href="<?php echo $base_url; ?>/sw-content/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Noto+Serif:wght@700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
  <style>
    /* ============ THEME VARIABLES ============ */
    :root {
      --bg-primary: #0a0a0a;
      --bg-secondary: #0f0f0f;
      --bg-tertiary: #050505;
      --bg-card: rgba(255,255,255,0.03);
      --bg-card-hover: rgba(255,255,255,0.06);
      --border-card: rgba(255,255,255,0.06);
      --border-subtle: rgba(255,255,255,0.05);
      --text-primary: #f0f0f0;
      --text-secondary: rgba(255,255,255,0.6);
      --text-muted: rgba(255,255,255,0.45);
      --text-faint: rgba(255,255,255,0.25);
      --navbar-bg: rgba(10,10,10,0.85);
      --navbar-bg-scroll: rgba(10,10,10,0.95);
      --hero-grad-1: #0a0a0a;
      --hero-grad-2: #1a0505;
      --hero-grad-3: #2d0a0a;
      --logo-bg: rgba(0,0,0,0.4);
      --logo-border: rgba(255,215,0,0.2);
      --stat-bg: rgba(255,255,255,0.03);
      --stat-border: rgba(255,255,255,0.06);
      --schedule-badge-bg: rgba(227,6,19,0.1);
      --feature-red-bg: rgba(227,6,19,0.12);
      --feature-gold-bg: rgba(255,215,0,0.12);
      --feature-green-bg: rgba(76,175,80,0.12);
      --feature-blue-bg: rgba(33,150,243,0.12);
      --btn-login-border: rgba(255,215,0,0.3);
      --btn-login-hover-bg: rgba(255,215,0,0.1);
      --btn-secondary-bg: rgba(255,255,255,0.05);
      --btn-secondary-border: rgba(255,255,255,0.15);
      --cta-glow: rgba(227,6,19,0.08);
      --shadow-navbar: rgba(0,0,0,0.5);
      --shadow-card: rgba(0,0,0,0.3);
      --navbar-brand-bg: #111;
      --scroll-icon: rgba(255,255,255,0.3);
      --hero-badge-bg: rgba(255,215,0,0.1);
      --hero-badge-border: rgba(255,215,0,0.3);
    }

    [data-theme="light"] {
      --bg-primary: #f5f5f5;
      --bg-secondary: #ffffff;
      --bg-tertiary: #e8e8e8;
      --bg-card: rgba(0,0,0,0.02);
      --bg-card-hover: rgba(0,0,0,0.04);
      --border-card: rgba(0,0,0,0.08);
      --border-subtle: rgba(0,0,0,0.06);
      --text-primary: #1a1a1a;
      --text-secondary: rgba(0,0,0,0.6);
      --text-muted: rgba(0,0,0,0.45);
      --text-faint: rgba(0,0,0,0.3);
      --navbar-bg: rgba(255,255,255,0.9);
      --navbar-bg-scroll: rgba(255,255,255,0.97);
      --hero-grad-1: #f5f5f5;
      --hero-grad-2: #fff0f0;
      --hero-grad-3: #ffe0e0;
      --logo-bg: rgba(255,255,255,0.8);
      --logo-border: rgba(200,160,0,0.35);
      --stat-bg: rgba(0,0,0,0.03);
      --stat-border: rgba(0,0,0,0.08);
      --schedule-badge-bg: rgba(227,6,19,0.08);
      --feature-red-bg: rgba(227,6,19,0.08);
      --feature-gold-bg: rgba(200,160,0,0.1);
      --feature-green-bg: rgba(76,175,80,0.08);
      --feature-blue-bg: rgba(33,150,243,0.08);
      --btn-login-border: rgba(180,130,0,0.4);
      --btn-login-hover-bg: rgba(180,130,0,0.08);
      --btn-secondary-bg: rgba(0,0,0,0.04);
      --btn-secondary-border: rgba(0,0,0,0.12);
      --cta-glow: rgba(227,6,19,0.05);
      --shadow-navbar: rgba(0,0,0,0.08);
      --shadow-card: rgba(0,0,0,0.06);
      --navbar-brand-bg: #f0f0f0;
      --scroll-icon: rgba(0,0,0,0.3);
      --hero-badge-bg: rgba(200,160,0,0.1);
      --hero-badge-border: rgba(200,160,0,0.35);
    }

    /* ============ RESET & BASE ============ */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; transition: background-color 0.35s ease, color 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg-primary);
      color: var(--text-primary);
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; height: auto; }

    /* ============ NAVBAR ============ */
    .navbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
      display: flex; align-items: center; justify-content: space-between;
      padding: 16px 24px;
      background: var(--navbar-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border-subtle);
      transition: all 0.3s ease;
    }
    .navbar.scrolled {
      padding: 10px 24px;
      background: var(--navbar-bg-scroll);
      box-shadow: 0 4px 30px var(--shadow-navbar);
    }
    .navbar-brand { display: flex; align-items: center; gap: 12px; }
    .navbar-brand img { width: 40px; height: 40px; border-radius: 8px; object-fit: contain; background: var(--navbar-brand-bg); padding: 2px; }
    .navbar-brand span {
      font-family: 'Noto Serif', serif;
      font-size: 1.1rem; font-weight: 700;
      background: linear-gradient(135deg, #FFD700, #FFA500);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .navbar-actions { display: flex; align-items: center; gap: 8px; }
    .nav-menu {
      display: flex; align-items: center; gap: 4px;
    }
    .nav-menu a {
      padding: 6px 12px; border-radius: 6px;
      font-size: 0.82rem; font-weight: 500;
      color: var(--text-secondary);
      transition: all 0.3s ease;
      white-space: nowrap;
    }
    .nav-menu a:hover {
      color: #C8A000;
      background: var(--bg-card);
    }
    .nav-menu a.active {
      color: #C8A000;
      background: var(--bg-card);
    }
    .btn-phone {
      width: 38px; height: 38px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      color: #4CAF50;
      border: 1px solid var(--border-card);
      background: var(--bg-card);
      transition: all 0.3s;
      font-size: 1.1rem;
    }
    .btn-phone:hover {
      background: #4CAF50;
      color: #fff;
      border-color: #4CAF50;
    }
    .navbar-links { display: flex; align-items: center; gap: 8px; }
    .navbar-links a {
      padding: 8px 16px; border-radius: 8px;
      font-size: 0.85rem; font-weight: 500;
      transition: all 0.3s ease;
    }
    .navbar-links .btn-login {
      color: #C8A000; border: 1px solid var(--btn-login-border);
    }
    [data-theme="light"] .navbar-links .btn-login { color: #9a7300; }
    .navbar-links .btn-login:hover {
      background: var(--btn-login-hover-bg); border-color: #C8A000;
    }
    .navbar-links .btn-register {
      background: linear-gradient(135deg, #E30613, #B71C1C);
      color: #fff;
    }
    .navbar-links .btn-register:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(227,6,19,0.4);
    }
    /* Hamburger */
    .hamburger {
      display: none;
      width: 40px; height: 40px;
      border-radius: 8px;
      border: 1px solid var(--border-card);
      background: var(--bg-card);
      flex-direction: column;
      align-items: center; justify-content: center;
      gap: 5px;
      cursor: pointer;
      transition: all 0.3s;
    }
    .hamburger span {
      display: block;
      width: 20px; height: 2px;
      background: var(--text-primary);
      border-radius: 2px;
      transition: all 0.3s;
    }
    .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }
    /* Theme Toggle */
    .theme-toggle {
      width: 40px; height: 40px;
      border-radius: 10px;
      border: 1px solid var(--border-card);
      background: var(--bg-card);
      color: var(--text-secondary);
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px;
      transition: all 0.3s ease;
    }
    .theme-toggle:hover {
      border-color: #FFD700;
      color: #FFD700;
      background: var(--btn-login-hover-bg);
    }
    .theme-toggle .icon-sun { display: none; }
    .theme-toggle .icon-moon { display: flex; }
    [data-theme="light"] .theme-toggle .icon-sun { display: flex; }
    [data-theme="light"] .theme-toggle .icon-moon { display: none; }

    /* ============ HERO ============ */
    .hero {
      min-height: 100vh; min-height: 100dvh;
      display: flex; align-items: center; justify-content: center;
      position: relative;
      background: linear-gradient(170deg, var(--hero-grad-1) 0%, var(--hero-grad-2) 30%, var(--hero-grad-3) 50%, var(--hero-grad-2) 70%, var(--hero-grad-1) 100%);
      overflow: hidden;
      padding: 100px 24px 60px;
    }
    .hero::before {
      content: '';
      position: absolute; top: -50%; left: -50%;
      width: 200%; height: 200%;
      background: radial-gradient(ellipse at 30% 50%, rgba(227,6,19,0.08) 0%, transparent 50%),
                  radial-gradient(ellipse at 70% 30%, rgba(255,215,0,0.05) 0%, transparent 50%);
      animation: heroGlow 8s ease-in-out infinite alternate;
    }
    @keyframes heroGlow {
      0% { transform: translate(0, 0) rotate(0deg); }
      100% { transform: translate(-2%, 2%) rotate(3deg); }
    }
    .hero::after {
      content: '';
      position: absolute; bottom: 0; left: 0; right: 0;
      height: 120px;
      background: linear-gradient(to top, var(--bg-primary), transparent);
    }
    .hero-content {
      text-align: center; position: relative; z-index: 2;
      max-width: 700px;
    }
    .hero-logo-wrap {
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 28px;
    }
    .hero-logo {
      width: 180px; height: auto;
      border-radius: 16px;
      border: 2px solid var(--logo-border);
      padding: 12px;
      background: var(--logo-bg);
      box-shadow:
        0 0 40px rgba(227,6,19,0.3),
        0 0 80px rgba(227,6,19,0.1);
      animation: logoPulse 3s ease-in-out infinite;
      object-fit: contain;
    }
    @keyframes logoPulse {
      0%, 100% { box-shadow: 0 0 40px rgba(227,6,19,0.3), 0 0 80px rgba(227,6,19,0.1); }
      50% { box-shadow: 0 0 60px rgba(227,6,19,0.5), 0 0 120px rgba(227,6,19,0.2); }
    }
    .hero-badge {
      display: inline-block;
      padding: 6px 18px; border-radius: 50px;
      background: var(--hero-badge-bg);
      border: 1px solid var(--hero-badge-border);
      color: #C8A000;
      font-size: 0.75rem; font-weight: 600;
      letter-spacing: 3px; text-transform: uppercase;
      margin-bottom: 20px;
    }
    .hero h1 {
      font-family: 'Noto Serif', serif;
      font-size: clamp(2.2rem, 6vw, 3.8rem);
      font-weight: 700;
      line-height: 1.15;
      margin-bottom: 16px;
    }
    .hero h1 .gold {
      background: linear-gradient(135deg, #FFD700, #FFA500, #FFD700);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .hero h1 .red {
      color: #E30613;
      -webkit-text-fill-color: #E30613;
    }
    .hero p {
      font-size: clamp(0.95rem, 2.5vw, 1.1rem);
      color: var(--text-secondary);
      line-height: 1.7;
      margin-bottom: 36px;
      max-width: 500px;
      margin-left: auto; margin-right: auto;
    }
    .hero-buttons { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
    .btn-primary-hero {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 14px 32px; border-radius: 12px;
      background: linear-gradient(135deg, #E30613, #B71C1C);
      color: #fff;
      font-size: 0.95rem; font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(227,6,19,0.3);
    }
    .btn-primary-hero:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(227,6,19,0.5);
    }
    .btn-secondary-hero {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 14px 32px; border-radius: 12px;
      background: var(--btn-secondary-bg);
      border: 1px solid var(--btn-secondary-border);
      color: var(--text-primary);
      font-size: 0.95rem; font-weight: 500;
      transition: all 0.3s ease;
    }
    .btn-secondary-hero:hover {
      background: var(--btn-login-hover-bg);
      border-color: rgba(200,160,0,0.4);
      color: #C8A000;
    }

    /* ============ SECTIONS COMMON ============ */
    .section { padding: 80px 24px; }
    .section-header { text-align: center; margin-bottom: 50px; }
    .section-label {
      display: inline-block;
      font-size: 0.7rem; font-weight: 700;
      letter-spacing: 3px; text-transform: uppercase;
      color: #E30613;
      margin-bottom: 12px;
    }
    .section-title {
      font-family: 'Noto Serif', serif;
      font-size: clamp(1.6rem, 4vw, 2.4rem);
      font-weight: 700;
      margin-bottom: 14px;
    }
    .section-desc {
      color: var(--text-muted);
      font-size: 0.95rem;
      max-width: 550px;
      margin: 0 auto;
      line-height: 1.7;
    }
    .container { max-width: 1100px; margin: 0 auto; }

    /* ============ ABOUT ============ */
    .about { background: var(--bg-secondary); }
    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 50px;
      align-items: center;
    }
    .about-image {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
    }
    .about-image img {
      width: 100%;
      border-radius: 20px;
      border: 1px solid var(--border-subtle);
    }
    .about-image::after {
      content: '';
      position: absolute; inset: 0;
      border-radius: 20px;
      background: linear-gradient(135deg, rgba(227,6,19,0.1), transparent);
    }
    .about-text h2 {
      font-family: 'Noto Serif', serif;
      font-size: clamp(1.5rem, 3.5vw, 2.2rem);
      margin-bottom: 18px;
      line-height: 1.3;
    }
    .about-text p {
      color: var(--text-secondary);
      line-height: 1.8;
      margin-bottom: 14px;
      font-size: 0.95rem;
    }
    .about-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-top: 28px;
    }
    .stat-card {
      text-align: center;
      padding: 20px 12px;
      border-radius: 14px;
      background: var(--stat-bg);
      border: 1px solid var(--stat-border);
    }
    .stat-card .number {
      font-family: 'Noto Serif', serif;
      font-size: 1.8rem; font-weight: 700;
      background: linear-gradient(135deg, #FFD700, #FFA500);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .stat-card .label {
      font-size: 0.72rem; color: var(--text-muted);
      margin-top: 4px; text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* ============ FEATURES ============ */
    .features { background: var(--bg-primary); }
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
    }
    .feature-card {
      padding: 32px 24px;
      border-radius: 18px;
      background: var(--bg-card);
      border: 1px solid var(--border-card);
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }
    .feature-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, #E30613, transparent);
      opacity: 0;
      transition: opacity 0.4s ease;
    }
    .feature-card:hover {
      transform: translateY(-4px);
      border-color: rgba(227,6,19,0.2);
      box-shadow: 0 20px 40px var(--shadow-card);
    }
    .feature-card:hover::before { opacity: 1; }
    .feature-icon {
      width: 52px; height: 52px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 24px;
      margin-bottom: 18px;
    }
    .feature-icon.red { background: var(--feature-red-bg); color: #E30613; }
    .feature-icon.gold { background: var(--feature-gold-bg); color: #C8A000; }
    .feature-icon.green { background: var(--feature-green-bg); color: #4CAF50; }
    .feature-icon.blue { background: var(--feature-blue-bg); color: #2196F3; }
    .feature-card h3 {
      font-size: 1.05rem; font-weight: 600;
      margin-bottom: 8px;
    }
    .feature-card p {
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.6;
    }

    /* ============ SCHEDULE ============ */
    .schedule { background: var(--bg-secondary); }
    .schedule-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }
    .schedule-card {
      padding: 28px;
      border-radius: 18px;
      background: var(--bg-card);
      border: 1px solid var(--border-card);
      transition: all 0.3s ease;
    }
    .schedule-card:hover {
      border-color: rgba(227,6,19,0.2);
      transform: translateY(-2px);
    }
    .schedule-card .time-badge {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 14px;
      border-radius: 8px;
      background: var(--schedule-badge-bg);
      color: #E30613;
      font-size: 0.8rem; font-weight: 600;
      margin-bottom: 14px;
    }
    .schedule-card h3 {
      font-size: 1.1rem; font-weight: 600;
      margin-bottom: 8px;
    }
    .schedule-card .time-range {
      font-size: 1.5rem; font-weight: 700;
      background: linear-gradient(135deg, #FFD700, #FFA500);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 4px;
    }
    .schedule-card .desc {
      font-size: 0.82rem;
      color: var(--text-muted);
    }

    /* ============ POSTER ============ */
    .poster-section { background: var(--bg-primary); }
    .poster-slider {
      display: flex;
      gap: 24px;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
      padding-bottom: 16px;
      scrollbar-width: none;
      justify-content: center;
      flex-wrap: wrap;
    }
    @media (max-width: 768px) {
      .poster-slider {
        flex-wrap: nowrap;
        justify-content: flex-start;
      }
    }
    .poster-slider::-webkit-scrollbar { display: none; }
    .poster-slide {
      scroll-snap-align: center;
      flex: 0 0 280px;
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid var(--border-card);
      background: var(--bg-card);
      transition: all 0.4s ease;
      cursor: pointer;
    }
    .poster-slide:hover {
      transform: translateY(-4px);
      border-color: rgba(227,6,19,0.3);
      box-shadow: 0 20px 40px var(--shadow-card);
    }
    .poster-slide img {
      width: 100%;
      height: auto;
      display: block;
    }
    .poster-slide .poster-caption {
      padding: 14px 16px;
      font-size: 0.9rem;
      font-weight: 600;
      text-align: center;
    }
    .poster-nav {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 18px;
    }
    .poster-nav button {
      width: 40px; height: 40px;
      border-radius: 50%;
      border: 1px solid var(--border-card);
      background: var(--bg-card);
      color: var(--text-primary);
      font-size: 18px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: all 0.3s ease;
    }
    .poster-nav button:hover {
      background: rgba(227,6,19,0.15);
      border-color: #E30613;
      color: #E30613;
    }

    /* ============ GALERI ============ */
    .galeri-section { background: var(--bg-secondary); }
    .galeri-tabs {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-bottom: 28px;
      flex-wrap: wrap;
    }
    .galeri-tab {
      padding: 8px 20px;
      border-radius: 50px;
      border: 1px solid var(--border-card);
      background: transparent;
      color: var(--text-muted);
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      font-family: 'Poppins', sans-serif;
    }
    .galeri-tab.active,
    .galeri-tab:hover {
      background: rgba(227,6,19,0.15);
      border-color: #E30613;
      color: #E30613;
    }
    .galeri-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      justify-content: center;
    }
    .galeri-item {
      width: 280px;
      max-width: 100%;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid var(--border-card);
      background: var(--bg-card);
      transition: all 0.4s ease;
      cursor: pointer;
      position: relative;
    }
    @media (max-width: 640px) {
      .galeri-item {
        width: calc(50% - 8px);
      }
    }
    @media (max-width: 400px) {
      .galeri-item {
        width: 100%;
      }
    }
    .galeri-item:hover {
      transform: translateY(-4px);
      border-color: rgba(227,6,19,0.25);
      box-shadow: 0 16px 32px var(--shadow-card);
    }
    .galeri-item img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
    }
    .galeri-item .video-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 40px;
      display: flex; align-items: center; justify-content: center;
      background: rgba(0,0,0,0.35);
      transition: background 0.3s ease;
    }
    .galeri-item:hover .video-overlay {
      background: rgba(0,0,0,0.5);
    }
    .video-overlay ion-icon {
      font-size: 48px;
      color: #fff;
      filter: drop-shadow(0 2px 8px rgba(0,0,0,0.4));
    }
    .galeri-item .galeri-caption {
      padding: 10px 14px;
      font-size: 0.82rem;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .galeri-item .galeri-badge {
      position: absolute;
      top: 10px; right: 10px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.68rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .galeri-badge.foto-badge {
      background: rgba(33,150,243,0.85);
      color: #fff;
    }
    .galeri-badge.video-badge {
      background: rgba(227,6,19,0.85);
      color: #fff;
    }

    /* Lightbox Modal */
    .lightbox-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9999;
      background: rgba(0,0,0,0.92);
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .lightbox-overlay.active { display: flex; }
    .lightbox-content {
      max-width: 90vw;
      max-height: 85vh;
      border-radius: 16px;
      overflow: hidden;
      position: relative;
    }
    .lightbox-content img,
    .lightbox-content video {
      max-width: 90vw;
      max-height: 85vh;
      border-radius: 16px;
      display: block;
    }
    .lightbox-close {
      position: absolute;
      top: 16px; right: 16px;
      width: 44px; height: 44px;
      border-radius: 50%;
      border: none;
      background: rgba(255,255,255,0.15);
      color: #fff;
      font-size: 24px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      z-index: 10000;
      transition: background 0.3s ease;
    }
    .lightbox-close:hover { background: rgba(227,6,19,0.6); }
    .lightbox-caption {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      padding: 16px 20px;
      background: linear-gradient(transparent, rgba(0,0,0,0.8));
      color: #fff;
      font-size: 0.9rem;
      font-weight: 500;
    }

    /* ============ CTA ============ */
    .cta {
      padding: 80px 24px;
      text-align: center;
      position: relative;
      background: linear-gradient(170deg, var(--hero-grad-1), var(--hero-grad-2), var(--hero-grad-1));
    }
    .cta::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at center, var(--cta-glow), transparent 70%);
    }
    .cta-content { position: relative; z-index: 2; }
    .cta h2 {
      font-family: 'Noto Serif', serif;
      font-size: clamp(1.6rem, 4vw, 2.5rem);
      margin-bottom: 14px;
    }
    .cta p {
      color: var(--text-muted);
      font-size: 1rem;
      margin-bottom: 32px;
      max-width: 450px;
      margin-left: auto; margin-right: auto;
      line-height: 1.7;
    }
    .cta-buttons { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

    /* ============ FOOTER ============ */
    .footer {
      padding: 60px 24px 24px;
      background: var(--bg-tertiary);
      border-top: 1px solid var(--border-subtle);
    }
    .footer-grid {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr 1.2fr 1.5fr;
      gap: 40px;
      max-width: 1300px;
      margin: 0 auto 40px;
    }
    .footer-map iframe {
      width: 100%;
      height: 200px;
      border: 0;
      border-radius: 12px;
      filter: brightness(0.85) contrast(1.1);
      transition: filter 0.3s;
    }
    .footer-map iframe:hover {
      filter: brightness(1) contrast(1);
    }
    .footer-map-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 10px;
      font-size: 0.8rem;
      color: #C8A000;
      transition: color 0.3s;
    }
    .footer-map-link:hover { color: #FFD700; }
    .footer-col h4 {
      font-family: 'Noto Serif', serif;
      font-size: 1rem; font-weight: 700;
      color: #C8A000;
      margin-bottom: 16px;
      position: relative;
      padding-bottom: 10px;
    }
    .footer-col h4::after {
      content: '';
      position: absolute;
      bottom: 0; left: 0;
      width: 30px; height: 2px;
      background: linear-gradient(90deg, #E30613, #C8A000);
      border-radius: 2px;
    }
    .footer-about-logo {
      display: flex; align-items: center; gap: 10px;
      margin-bottom: 14px;
    }
    .footer-about-logo img { width: 40px; height: 40px; border-radius: 50%; }
    .footer-about-logo span {
      font-family: 'Noto Serif', serif;
      font-size: 1.1rem; font-weight: 700;
      background: linear-gradient(135deg, #FFD700, #FFA500);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .footer-about-desc {
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.7;
    }
    .footer-nav-links {
      display: flex; flex-direction: column; gap: 10px;
    }
    .footer-nav-links a {
      font-size: 0.85rem;
      color: var(--text-muted);
      transition: all 0.3s;
      display: flex; align-items: center; gap: 8px;
    }
    .footer-nav-links a:hover {
      color: #C8A000;
      padding-left: 6px;
    }
    .footer-nav-links a ion-icon {
      font-size: 0.7rem;
      color: #E30613;
    }
    .footer-schedule {
      width: 100%;
      border-collapse: collapse;
    }
    .footer-schedule td {
      font-size: 0.82rem;
      padding: 5px 0;
      color: var(--text-muted);
      border-bottom: 1px solid var(--border-subtle);
    }
    .footer-schedule td:first-child {
      font-weight: 600;
      color: var(--text-secondary);
      width: 70px;
    }
    .footer-schedule .closed {
      color: #E30613;
      font-style: italic;
    }
    .footer-contact-item {
      display: flex; align-items: flex-start; gap: 10px;
      margin-bottom: 14px;
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.6;
    }
    .footer-contact-item ion-icon {
      font-size: 1.2rem;
      color: #C8A000;
      flex-shrink: 0;
      margin-top: 2px;
    }
    .footer-contact-item a {
      color: var(--text-muted);
      transition: color 0.3s;
    }
    .footer-contact-item a:hover { color: #C8A000; }
    .footer-divider {
      border: none;
      border-top: 1px solid var(--border-subtle);
      max-width: 1200px;
      margin: 0 auto 20px;
    }
    .footer-bottom {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
      flex-wrap: wrap;
      gap: 10px;
    }
    .footer-copy {
      font-size: 0.75rem;
      color: var(--text-faint);
    }
    .footer-copy a { color: var(--text-muted); }
    .footer-socials {
      display: flex; gap: 12px;
    }
    .footer-socials a {
      width: 34px; height: 34px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: var(--bg-card);
      border: 1px solid var(--border-card);
      color: var(--text-muted);
      font-size: 1rem;
      transition: all 0.3s;
    }
    .footer-socials a:hover {
      background: #C8A000;
      color: #000;
      border-color: #C8A000;
    }

    /* ============ SCROLL INDICATOR ============ */
    .scroll-indicator {
      position: absolute;
      bottom: 40px; left: 50%;
      transform: translateX(-50%);
      z-index: 3;
      animation: scrollBounce 2s ease-in-out infinite;
    }
    .scroll-indicator ion-icon {
      font-size: 28px;
      color: var(--scroll-icon);
    }
    @keyframes scrollBounce {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50% { transform: translateX(-50%) translateY(10px); }
    }

    /* ============ ANIMATIONS ============ */
    .fade-up {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .fade-up.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* ============ ATLET KEBANGGAAN ============ */
    .atlet-section { background: var(--bg-primary); }
    .atlet-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 24px;
      margin-top: 24px;
    }
    .atlet-card {
      background: var(--bg-card);
      border: 1px solid var(--border-card);
      border-radius: 16px;
      overflow: hidden;
      cursor: default;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
    }
    .atlet-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 40px rgba(255,215,0,0.12);
      border-color: rgba(255,215,0,0.25);
    }
    .atlet-card-img {
      width: 100%; height: 300px;
      object-fit: cover; object-position: top center;
      display: block;
    }
    .atlet-card-body {
      padding: 20px;
      text-align: center;
    }
    .atlet-card-body h3 {
      font-family: 'Noto Serif', serif;
      font-size: 1.1rem;
      margin-bottom: 6px;
      background: linear-gradient(135deg, #FFD700, #FFA500);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .atlet-prestasi {
      font-size: 0.85rem;
      color: #E30613;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-bottom: 4px;
    }
    .atlet-kategori {
      font-size: 0.78rem;
      color: var(--text-muted);
    }
    .atlet-medal-icon {
      width: 18px; height: 18px;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 1024px) {
      .nav-menu { display: none; }
      .hamburger { display: flex; }
      .nav-menu.open {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 100%; left: 0; right: 0;
        background: var(--navbar-bg-scroll);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 12px 24px 20px;
        border-bottom: 1px solid var(--border-subtle);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        gap: 2px;
      }
      .nav-menu.open a {
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 0.9rem;
      }
      .footer-grid { grid-template-columns: 1fr 1fr 1fr; gap: 30px; }
    }
    @media (max-width: 768px) {
      .navbar-links .link-text { display: none; }
      .about-grid { grid-template-columns: 1fr; gap: 30px; }
      .about-image { order: -1; }
      .about-stats { grid-template-columns: repeat(3, 1fr); gap: 10px; }
      .stat-card { padding: 14px 8px; }
      .stat-card .number { font-size: 1.4rem; }
      .hero { padding: 80px 20px 60px; }
      .hero-logo { width: 140px; }
      .section { padding: 60px 20px; }
      .features-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .feature-card { padding: 20px 16px; }
      .feature-icon { width: 44px; height: 44px; font-size: 20px; }
      .schedule-grid { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr; gap: 24px; }
      .footer-col h4::after { left: 0; }
      .footer-bottom { flex-direction: column; text-align: center; }
    }
    @media (max-width: 400px) {
      .features-grid { grid-template-columns: 1fr; }
      .hero-buttons { flex-direction: column; }
      .hero-buttons a { width: 100%; justify-content: center; }
      .cta-buttons { flex-direction: column; align-items: center; }
      .cta-buttons a { width: 100%; justify-content: center; }
    }
  </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar">
  <a href="./" class="navbar-brand">
    <img src="<?php echo $base_url; ?>/sw-admin/sw-assets/img/logo-dojo_hkcpng.png" alt="Logo">
    <span>DOJO HKC</span>
  </a>
  <div class="nav-menu" id="navMenu">
    <a href="#" onclick="scrollToTop();return false;">Beranda</a>
    <a href="#tentang">Tentang</a>
    <a href="#fitur">Fitur</a>
    <a href="#jadwal">Jadwal</a>
    <a href="#poster">Poster</a>
    <a href="#galeri">Galeri</a>
    <a href="#atlet">Atlet</a>
    <a href="#kontak">Kontak</a>
  </div>
  <div class="navbar-actions">
    <button class="theme-toggle" id="themeToggle" aria-label="Ganti tema">
      <span class="icon-moon"><ion-icon name="moon-outline"></ion-icon></span>
      <span class="icon-sun"><ion-icon name="sunny-outline"></ion-icon></span>
    </button>
    <a href="tel:08129215459" class="btn-phone" title="Hubungi Kami"><ion-icon name="call-outline"></ion-icon></a>
    <div class="navbar-links">
      <a href="./home" class="btn-login"><ion-icon name="log-in-outline"></ion-icon> <span class="link-text">Masuk</span></a>
      <a href="./registrasi" class="btn-register"><span class="link-text">Daftar</span></a>
    </div>
    <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-logo-wrap">
      <img src="<?php echo $base_url; ?>/sw-admin/sw-assets/img/logo-dojo_hkcpng.png" alt="Dojo HKC Logo" class="hero-logo">
    </div>
    <div class="hero-badge"><?php echo ls($ls, 'hero_badge', 'INSTITUT KARATE-DO NASIONAL'); ?></div>
    <h1><?php echo ls($ls, 'hero_title', '<span class="gold">Halim</span> <span class="red">Karate</span> Champion'); ?></h1>
    <p><?php echo ls($ls, 'hero_subtitle', 'Bergabunglah bersama kami dalam perjalanan seni bela diri karate. Disiplin, kekuatan, dan kehormatan — dimulai dari sini.'); ?></p>
    <div class="hero-buttons">
      <a href="./registrasi" class="btn-primary-hero">
        <ion-icon name="flash-outline"></ion-icon> <?php echo ls($ls, 'hero_btn_primary', 'Mulai Bergabung'); ?>
      </a>
      <a href="#jadwal" class="btn-secondary-hero">
        <ion-icon name="calendar-outline"></ion-icon> <?php echo ls($ls, 'hero_btn_secondary', 'Lihat Jadwal'); ?>
      </a>
    </div>
  </div>
  <div class="scroll-indicator">
    <ion-icon name="chevron-down-outline"></ion-icon>
  </div>
</section>

<!-- ===== ABOUT ===== -->
<section class="about section" id="tentang">
  <div class="container">
    <div class="about-grid">
      <div class="about-image fade-up">
        <?php
          $aboutImg = ls($ls, 'about_image');
          if(!empty($aboutImg) && file_exists('sw-content/landing/'.$aboutImg)){
            echo '<img src="'.$base_url.'/sw-content/landing/'.$aboutImg.'" alt="Dojo HKC" style="padding:40px; background:var(--bg-card); aspect-ratio:1; object-fit:contain;">';
          } else {
            echo '<img src="'.$base_url.'/sw-admin/sw-assets/img/logo-dojo_hkcpng.png" alt="Dojo HKC" style="padding:40px; background:var(--bg-card); aspect-ratio:1; object-fit:contain;">';
          }
        ?>
      </div>
      <div class="about-text fade-up">
        <span class="section-label"><?php echo ls($ls, 'about_label', 'Tentang Kami'); ?></span>
        <h2><?php echo ls($ls, 'about_title', 'Membentuk Karakter Melalui <span style="color:#E30613">Karate</span>'); ?></h2>
        <p><?php echo ls($ls, 'about_desc1', 'Dojo HKC (Halim Karate Champion) adalah pusat pelatihan karate di bawah naungan <strong>INKANAS</strong> (Institut Karate-Do Nasional) yang berkomitmen membentuk karakter disiplin, tangguh, dan berprestasi.'); ?></p>
        <p><?php echo ls($ls, 'about_desc2', 'Dengan pelatih bersertifikat dan fasilitas lengkap, kami menyediakan program latihan untuk semua tingkatan — dari pemula hingga atlet profesional.'); ?></p>
        <div class="about-stats">
          <div class="stat-card">
            <div class="number"><?php
              $q = $connection->query("SELECT COUNT(*) as total FROM employees");
              $r = $q->fetch_assoc();
              echo $r['total'];
            ?></div>
            <div class="label">Anggota</div>
          </div>
          <div class="stat-card">
            <div class="number"><?php echo count($shifts); ?></div>
            <div class="label">Sesi Latihan</div>
          </div>
          <div class="stat-card">
            <div class="number"><?php echo ls($ls, 'about_stat_label', '4+'); ?></div>
            <div class="label">Tahun</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="features section" id="fitur">
  <div class="container">
    <div class="section-header fade-up">
      <span class="section-label"><?php echo ls($ls, 'feature_label', 'Fitur Sistem'); ?></span>
      <h2 class="section-title"><?php echo ls($ls, 'feature_title', 'Kelola Kehadiran dengan Mudah'); ?></h2>
      <p class="section-desc"><?php echo ls($ls, 'feature_desc', 'Sistem absensi digital yang memudahkan pencatatan kehadiran, izin, cuti, dan riwayat latihan Anda.'); ?></p>
    </div>
    <div class="features-grid">
      <?php
        $featuresJson = ls($ls, 'features_json', '[]');
        $featuresArr = json_decode($featuresJson, true);
        if(!is_array($featuresArr) || empty($featuresArr)){
          // Fallback defaults
          $featuresArr = [
            ['icon'=>'camera-outline','color'=>'red','title'=>'Absensi Selfie','desc'=>'Absen masuk & pulang dengan foto selfie dan deteksi lokasi GPS radius otomatis.'],
            ['icon'=>'location-outline','color'=>'gold','title'=>'Deteksi Radius','desc'=>'Sistem memverifikasi lokasi Anda berada dalam radius dojo sebelum bisa absen.'],
            ['icon'=>'document-text-outline','color'=>'green','title'=>'Izin & Cuti','desc'=>'Ajukan izin atau cuti langsung dari aplikasi dan pantau status persetujuannya.'],
            ['icon'=>'stats-chart-outline','color'=>'blue','title'=>'Riwayat Lengkap','desc'=>'Lihat riwayat kehadiran, keterlambatan, dan rekap bulanan secara detail.']
          ];
        }
        foreach($featuresArr as $feat):
      ?>
      <div class="feature-card fade-up">
        <div class="feature-icon <?php echo htmlspecialchars($feat['color'] ?? 'red'); ?>"><ion-icon name="<?php echo htmlspecialchars($feat['icon'] ?? 'star-outline'); ?>"></ion-icon></div>
        <h3><?php echo htmlspecialchars($feat['title'] ?? ''); ?></h3>
        <p><?php echo htmlspecialchars($feat['desc'] ?? ''); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== SCHEDULE ===== -->
<section class="schedule section" id="jadwal">
  <div class="container">
    <div class="section-header fade-up">
      <span class="section-label">Jadwal Latihan</span>
      <h2 class="section-title">Waktu Latihan Tersedia</h2>
      <p class="section-desc">Pilih sesi latihan yang sesuai dengan jadwal Anda.</p>
    </div>
    <div class="schedule-grid">
      <?php if(!empty($shifts)): ?>
        <?php foreach($shifts as $i => $shift): ?>
          <div class="schedule-card fade-up">
            <div class="time-badge">
              <ion-icon name="time-outline"></ion-icon> Sesi <?php echo ($i+1); ?>
            </div>
            <h3><?php echo htmlspecialchars($shift['shift_name']); ?></h3>
            <div class="time-range"><?php echo $shift['time_in']; ?> — <?php echo $shift['time_out']; ?></div>
            <p class="desc">Durasi latihan termasuk pemanasan dan pendinginan</p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="schedule-card fade-up">
          <div class="time-badge"><ion-icon name="time-outline"></ion-icon> Info</div>
          <h3>Hubungi Admin</h3>
          <p class="desc">Jadwal latihan akan segera tersedia</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ===== ATLET KEBANGGAAN ===== -->
<?php if(!empty($atlets)): ?>
<section class="atlet-section section" id="atlet">
  <div class="container" style="max-width:1100px;margin:0 auto;padding:60px 24px;">
    <div class="fade-up" style="text-align:center;margin-bottom:32px;">
      <span class="section-label">Kebanggaan Dojo</span>
      <h2 style="font-family:'Noto Serif',serif;font-size:clamp(1.5rem,3.5vw,2.2rem);margin-bottom:8px;">Atlet <span style="color:#FFD700">Berprestasi</span></h2>
      <p style="color:var(--text-muted);max-width:500px;margin:0 auto;">Para karateka kebanggaan Dojo HKC yang telah mengharumkan nama di berbagai kejuaraan.</p>
    </div>
    <div class="atlet-grid">
      <?php foreach($atlets as $a): ?>
      <div class="atlet-card fade-up">
        <img src="<?php echo $base_url; ?>/sw-content/atlet/<?php echo htmlspecialchars($a['foto']); ?>" alt="<?php echo htmlspecialchars($a['nama']); ?>" class="atlet-card-img" loading="lazy">
        <div class="atlet-card-body">
          <h3><?php echo htmlspecialchars($a['nama']); ?></h3>
          <div class="atlet-prestasi">
            <ion-icon name="trophy-outline"></ion-icon>
            <?php echo htmlspecialchars($a['prestasi']); ?>
          </div>
          <?php if(!empty($a['kategori'])): ?>
          <div class="atlet-kategori"><?php echo htmlspecialchars($a['kategori']); ?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== POSTER REKRUTMEN ===== -->
<?php if(!empty($posters)): ?>
<section class="poster-section section" id="poster">
  <div class="container" style="max-width:1100px;margin:0 auto;padding:60px 24px;">
    <div class="fade-up" style="text-align:center;margin-bottom:32px;">
      <span class="section-label">Open Recruitment</span>
      <h2 style="font-family:'Noto Serif',serif;font-size:clamp(1.5rem,3.5vw,2.2rem);margin-bottom:8px;">Poster <span style="color:#E30613">Rekrutmen</span></h2>
      <p style="color:var(--text-muted);max-width:500px;margin:0 auto;">Bergabunglah bersama kami! Lihat info rekrutmen anggota baru.</p>
    </div>
    <div class="poster-slider" id="posterSlider">
      <?php foreach($posters as $p): ?>
        <div class="poster-slide fade-up" onclick="openLightbox('<?php echo $base_url; ?>/sw-content/poster/<?php echo htmlspecialchars($p['file']); ?>', '<?php echo htmlspecialchars($p['judul']); ?>', 'foto')">
          <img src="<?php echo $base_url; ?>/sw-content/poster/<?php echo htmlspecialchars($p['file']); ?>" alt="<?php echo htmlspecialchars($p['judul']); ?>" loading="lazy">
          <div class="poster-caption"><?php echo htmlspecialchars($p['judul']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if(count($posters) > 2): ?>
    <div class="poster-nav">
      <button onclick="document.getElementById('posterSlider').scrollBy({left:-300,behavior:'smooth'})" aria-label="Previous">
        <ion-icon name="chevron-back-outline"></ion-icon>
      </button>
      <button onclick="document.getElementById('posterSlider').scrollBy({left:300,behavior:'smooth'})" aria-label="Next">
        <ion-icon name="chevron-forward-outline"></ion-icon>
      </button>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- ===== GALERI FOTO & VIDEO ===== -->
<?php if(!empty($galeris)): ?>
<section class="galeri-section section" id="galeri">
  <div class="container" style="max-width:1100px;margin:0 auto;padding:60px 24px;">
    <div class="fade-up" style="text-align:center;margin-bottom:32px;">
      <span class="section-label">Dokumentasi</span>
      <h2 style="font-family:'Noto Serif',serif;font-size:clamp(1.5rem,3.5vw,2.2rem);margin-bottom:8px;">Galeri <span style="color:#FFD700">Foto & Video</span></h2>
      <p style="color:var(--text-muted);max-width:500px;margin:0 auto;">Momen pertandingan dan latihan karate yang penuh semangat.</p>
    </div>
    <div class="galeri-tabs fade-up">
      <button class="galeri-tab active" onclick="filterGaleri('semua', this)">Semua</button>
      <button class="galeri-tab" onclick="filterGaleri('foto', this)">Foto</button>
      <button class="galeri-tab" onclick="filterGaleri('video', this)">Video</button>
    </div>
    <div class="galeri-grid" id="galeriGrid">
      <?php foreach($galeris as $g): ?>
        <?php
          $ytId = '';
          if($g['tipe'] == 'video'){
            if(preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $g['file'], $m)){
              $ytId = $m[1];
            }
          }
        ?>
        <?php if($g['tipe'] == 'foto'): ?>
        <div class="galeri-item fade-up" data-tipe="foto" onclick="openLightbox('<?php echo $base_url; ?>/sw-content/galeri/<?php echo htmlspecialchars($g['file']); ?>', '<?php echo htmlspecialchars($g['judul']); ?>', 'foto')">
          <img src="<?php echo $base_url; ?>/sw-content/galeri/<?php echo htmlspecialchars($g['file']); ?>" alt="<?php echo htmlspecialchars($g['judul']); ?>" loading="lazy">
          <span class="galeri-badge foto-badge">Foto</span>
          <div class="galeri-caption"><?php echo htmlspecialchars($g['judul']); ?></div>
        </div>
        <?php elseif($ytId): ?>
        <div class="galeri-item fade-up" data-tipe="video" onclick="openLightbox('<?php echo $ytId; ?>', '<?php echo htmlspecialchars($g['judul']); ?>', 'youtube')">
          <img src="https://img.youtube.com/vi/<?php echo $ytId; ?>/hqdefault.jpg" alt="<?php echo htmlspecialchars($g['judul']); ?>" loading="lazy">
          <div class="video-overlay">
            <ion-icon name="play-circle-outline"></ion-icon>
          </div>
          <span class="galeri-badge video-badge">Video</span>
          <div class="galeri-caption"><?php echo htmlspecialchars($g['judul']); ?></div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- Lightbox Modal -->
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox(event)">
  <button class="lightbox-close" onclick="closeLightbox(event)">
    <ion-icon name="close-outline"></ion-icon>
  </button>
  <div class="lightbox-content" id="lightboxContent"></div>
</div>

<!-- ===== CTA ===== -->
<section class="cta" id="daftar">
  <div class="cta-content fade-up">
    <span class="section-label"><?php echo ls($ls, 'cta_label', 'Siap Berlatih?'); ?></span>
    <h2><?php echo ls($ls, 'cta_title', 'Bergabung Bersama <span style="color:#E30613">Dojo HKC</span>'); ?></h2>
    <p><?php echo ls($ls, 'cta_desc', 'Daftarkan diri Anda sekarang dan mulai perjalanan karate bersama kami.'); ?></p>
    <div class="cta-buttons">
      <a href="./registrasi" class="btn-primary-hero">
        <ion-icon name="person-add-outline"></ion-icon> Daftar Sekarang
      </a>
      <a href="./home" class="btn-secondary-hero">
        <ion-icon name="log-in-outline"></ion-icon> Sudah Punya Akun
      </a>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer" id="kontak">
  <div class="footer-grid">
    <!-- Column 1: About -->
    <div class="footer-col">
      <div class="footer-about-logo">
        <img src="<?php echo $base_url; ?>/sw-admin/sw-assets/img/logo-dojo_hkcpng.png" alt="Logo">
        <span><?php echo ls($ls, 'footer_text', 'DOJO HKC'); ?></span>
      </div>
      <p class="footer-about-desc">
        Dojo HKC (Halim Karate Champion) adalah pusat pelatihan karate di bawah naungan INKANAS yang berkomitmen membentuk karakter disiplin, tangguh, dan berprestasi.
      </p>
    </div>
    <!-- Column 2: Navigation -->
    <div class="footer-col">
      <h4>Navigasi</h4>
      <div class="footer-nav-links">
        <a href="#tentang"><ion-icon name="chevron-forward-outline"></ion-icon> Tentang Kami</a>
        <a href="#fitur"><ion-icon name="chevron-forward-outline"></ion-icon> Fitur Sistem</a>
        <a href="#jadwal"><ion-icon name="chevron-forward-outline"></ion-icon> Jadwal Latihan</a>
        <a href="#poster"><ion-icon name="chevron-forward-outline"></ion-icon> Poster</a>
        <a href="#galeri"><ion-icon name="chevron-forward-outline"></ion-icon> Galeri</a>
        <a href="#atlet"><ion-icon name="chevron-forward-outline"></ion-icon> Atlet Berprestasi</a>
        <a href="./registrasi"><ion-icon name="chevron-forward-outline"></ion-icon> Daftar</a>
      </div>
    </div>
    <!-- Column 3: Schedule -->
    <div class="footer-col">
      <h4>Jam Latihan</h4>
      <table class="footer-schedule">
        <tr><td>Senin</td><td class="closed">Tutup</td></tr>
        <tr><td>Selasa</td><td>17.00 – 21.00</td></tr>
        <tr><td>Rabu</td><td>18.30 – 21.00</td></tr>
        <tr><td>Kamis</td><td>19.00 – 21.00</td></tr>
        <tr><td>Jumat</td><td>19.00 – 21.00</td></tr>
        <tr><td>Sabtu</td><td class="closed">Tutup</td></tr>
        <tr><td>Minggu</td><td>14.00 – 16.00</td></tr>
      </table>
    </div>
    <!-- Column 4: Contact -->
    <div class="footer-col">
      <h4>Kontak</h4>
      <div class="footer-contact-item">
        <ion-icon name="location-outline"></ion-icon>
        <span>2, RT.2/RW.4, Lubang Buaya, Kec. Cipayung, Kota Jakarta Timur, DKI Jakarta 13810</span>
      </div>
      <div class="footer-contact-item">
        <ion-icon name="call-outline"></ion-icon>
        <a href="tel:08129215459">0812-9215-459</a>
      </div>
      <div class="footer-contact-item">
        <ion-icon name="logo-whatsapp"></ion-icon>
        <a href="https://wa.me/628129215459" target="_blank">WhatsApp</a>
      </div>
    </div>
    <!-- Column 5: Google Maps -->
    <div class="footer-col">
      <h4>Lokasi Kami</h4>
      <div class="footer-map">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.6!2d106.8900!3d-6.3350!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ed7b3f16b4e5%3A0x72e30e8e8e8e8e8e!2sLubang+Buaya%2C+Cipayung%2C+East+Jakarta+City%2C+Jakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
          allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <a href="https://maps.app.goo.gl/YourDojoLink" target="_blank" class="footer-map-link">
          <ion-icon name="navigate-outline"></ion-icon> Buka di Google Maps
        </a>
      </div>
    </div>
  </div>
  <hr class="footer-divider">
  <div class="footer-bottom">
    <p class="footer-copy">
      &copy; 2021 — <?php echo date('Y'); ?> <?php echo $website_name; ?> — 
      Halim Karate Champion &bull; INKANAS &bull; 
      <a href="https://s-widodo.com" target="_blank">s-widodo.com</a>
    </p>
    <div class="footer-socials">
      <a href="https://wa.me/628129215459" target="_blank" title="WhatsApp"><ion-icon name="logo-whatsapp"></ion-icon></a>
      <a href="tel:08129215459" title="Telepon"><ion-icon name="call-outline"></ion-icon></a>
    </div>
  </div>
</footer>

<!-- ===== SCRIPTS ===== -->
<script>
// Hamburger menu
(function() {
  const hamburger = document.getElementById('hamburgerBtn');
  const navMenu = document.getElementById('navMenu');
  if (hamburger && navMenu) {
    hamburger.addEventListener('click', function() {
      hamburger.classList.toggle('active');
      navMenu.classList.toggle('open');
    });
    // Close menu when clicking a link
    navMenu.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        hamburger.classList.remove('active');
        navMenu.classList.remove('open');
      });
    });
  }
})();

// Scroll to top
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Navbar scroll effect
window.addEventListener('scroll', function() {
  const navbar = document.getElementById('navbar');
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

// Scroll animation (Intersection Observer)
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry, index) => {
    if (entry.isIntersecting) {
      setTimeout(() => {
        entry.target.classList.add('visible');
      }, index * 80);
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

// Theme toggle
(function() {
  const toggle = document.getElementById('themeToggle');
  const html = document.documentElement;
  const saved = localStorage.getItem('dojo-theme');
  if (saved) html.setAttribute('data-theme', saved);

  toggle.addEventListener('click', function() {
    const current = html.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    if (next === 'dark') {
      html.removeAttribute('data-theme');
    } else {
      html.setAttribute('data-theme', next);
    }
    localStorage.setItem('dojo-theme', next);
  });
})();

// Lightbox
function openLightbox(src, caption, tipe) {
  const overlay = document.getElementById('lightboxOverlay');
  const content = document.getElementById('lightboxContent');
  if (tipe === 'youtube') {
    content.innerHTML = '<div style="position:relative;padding-bottom:56.25%;width:min(90vw,800px);height:0;overflow:hidden;border-radius:16px;">' +
      '<iframe src="https://www.youtube.com/embed/' + src + '?autoplay=1&rel=0" ' +
      'style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;border-radius:16px;" ' +
      'allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ' +
      'allowfullscreen></iframe></div>' +
      '<div class="lightbox-caption">' + caption + '</div>';
  } else if (tipe === 'video') {
    content.innerHTML = '<video src="' + src + '" controls autoplay style="max-width:90vw;max-height:85vh;border-radius:16px;"></video>' +
      '<div class="lightbox-caption">' + caption + '</div>';
  } else {
    content.innerHTML = '<img src="' + src + '" alt="' + caption + '">' +
      '<div class="lightbox-caption">' + caption + '</div>';
  }
  overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeLightbox(e) {
  if (e.target === document.getElementById('lightboxOverlay') || e.currentTarget.classList.contains('lightbox-close')) {
    const overlay = document.getElementById('lightboxOverlay');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    // Stop video if playing
    const vid = overlay.querySelector('video');
    if (vid) vid.pause();
    // Remove iframe (YouTube) to stop playback
    const iframe = overlay.querySelector('iframe');
    if (iframe) iframe.remove();
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const overlay = document.getElementById('lightboxOverlay');
    if (overlay.classList.contains('active')) {
      overlay.classList.remove('active');
      document.body.style.overflow = '';
      const vid = overlay.querySelector('video');
      if (vid) vid.pause();
      const iframe = overlay.querySelector('iframe');
      if (iframe) iframe.remove();
    }
  }
});

// Gallery filter
function filterGaleri(tipe, btn) {
  document.querySelectorAll('.galeri-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.galeri-item').forEach(item => {
    if (tipe === 'semua' || item.getAttribute('data-tipe') === tipe) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
}
</script>

</body>
</html>
<?php } ?>
