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
  <link rel="shortcut icon" href="<?php echo $base_url; ?>/sw-admin/sw-assets/img/logo-dojo_hkcpng.png">
  <link rel="apple-touch-icon" href="<?php echo $base_url; ?>/sw-admin/sw-assets/img/logo-dojo_hkcpng.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Noto+Serif:wght@700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
  <style>
    /* ============ THEME VARIABLES ============ */
    :root {
      --bg-primary: #080808;
      --bg-secondary: #0d0d0d;
      --bg-tertiary: #050505;
      --bg-card: rgba(255,255,255,0.04);
      --bg-card-hover: rgba(255,255,255,0.07);
      --border-card: rgba(255,255,255,0.08);
      --border-subtle: rgba(255,255,255,0.06);
      --text-primary: #f2f0ed;
      --text-secondary: rgba(255,255,255,0.65);
      --text-muted: rgba(255,255,255,0.45);
      --text-faint: rgba(255,255,255,0.22);
      --navbar-bg: rgba(8,8,8,0.8);
      --navbar-bg-scroll: rgba(8,8,8,0.95);
      --hero-grad-1: #080808;
      --hero-grad-2: #1a0505;
      --hero-grad-3: #2d0a0a;
      --logo-bg: rgba(0,0,0,0.5);
      --logo-border: rgba(255,215,0,0.25);
      --stat-bg: rgba(255,255,255,0.04);
      --stat-border: rgba(255,255,255,0.08);
      --schedule-badge-bg: rgba(227,6,19,0.12);
      --feature-red-bg: rgba(227,6,19,0.12);
      --feature-gold-bg: rgba(255,215,0,0.12);
      --feature-green-bg: rgba(76,175,80,0.12);
      --feature-blue-bg: rgba(33,150,243,0.12);
      --btn-login-border: rgba(255,215,0,0.3);
      --btn-login-hover-bg: rgba(255,215,0,0.1);
      --btn-secondary-bg: rgba(255,255,255,0.06);
      --btn-secondary-border: rgba(255,255,255,0.15);
      --cta-glow: rgba(227,6,19,0.1);
      --shadow-navbar: rgba(0,0,0,0.6);
      --shadow-card: rgba(0,0,0,0.4);
      --navbar-brand-bg: #111;
      --scroll-icon: rgba(255,255,255,0.3);
      --hero-badge-bg: rgba(255,215,0,0.1);
      --hero-badge-border: rgba(255,215,0,0.3);
      --mesh-1: rgba(227,6,19,0.04);
      --mesh-2: rgba(255,215,0,0.03);
      --mesh-3: rgba(33,150,243,0.03);
      --glass-bg: rgba(255,255,255,0.03);
      --glass-border: rgba(255,255,255,0.08);
      --card-shine: rgba(255,255,255,0.05);
    }

    [data-theme="light"] {
      --bg-primary: #FDFCF6;
      --bg-secondary: #F8F6F0;
      --bg-tertiary: #F0EDE5;
      --bg-card: rgba(255,255,255,0.92);
      --bg-card-hover: rgba(255,255,255,0.98);
      --border-card: rgba(0,0,0,0.06);
      --border-subtle: rgba(0,0,0,0.04);
      --text-primary: #1A1A1A;
      --text-secondary: #4A4A4A;
      --text-muted: #6B6B6B;
      --text-faint: rgba(26,26,26,0.2);
      --navbar-bg: rgba(253,252,246,0.85);
      --navbar-bg-scroll: rgba(253,252,246,0.96);
      --hero-grad-1: #FDFCF6;
      --hero-grad-2: #FFF5E6;
      --hero-grad-3: #FFECD2;
      --logo-bg: rgba(255,255,255,0.95);
      --logo-border: rgba(212,175,55,0.45);
      --stat-bg: rgba(255,255,255,0.95);
      --stat-border: rgba(212,175,55,0.18);
      --schedule-badge-bg: rgba(227,6,19,0.08);
      --feature-red-bg: rgba(227,6,19,0.06);
      --feature-gold-bg: rgba(212,175,55,0.08);
      --feature-green-bg: rgba(56,142,60,0.06);
      --feature-blue-bg: rgba(25,118,210,0.06);
      --btn-login-border: rgba(212,175,55,0.45);
      --btn-login-hover-bg: rgba(212,175,55,0.08);
      --btn-secondary-bg: rgba(255,255,255,0.9);
      --btn-secondary-border: rgba(0,0,0,0.08);
      --cta-glow: rgba(227,6,19,0.06);
      --shadow-navbar: rgba(0,0,0,0.04);
      --shadow-card: rgba(0,0,0,0.06);
      --navbar-brand-bg: #F5F3ED;
      --scroll-icon: rgba(26,26,26,0.3);
      --hero-badge-bg: rgba(212,175,55,0.1);
      --hero-badge-border: rgba(212,175,55,0.35);
      --mesh-1: rgba(227,6,19,0.04);
      --mesh-2: rgba(255,180,50,0.05);
      --mesh-3: rgba(25,118,210,0.03);
      --glass-bg: rgba(255,255,255,0.80);
      --glass-border: rgba(0,0,0,0.06);
      --card-shine: rgba(255,252,240,0.5);
    }

    /* Light mode — premium card depth (neutral, soft shadows) */
    [data-theme="light"] .feature-card,
    [data-theme="light"] .schedule-card,
    [data-theme="light"] .poster-slide,
    [data-theme="light"] .galeri-item {
      box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.03);
      border: 1px solid rgba(0,0,0,0.06);
    }
    [data-theme="light"] .feature-card:hover,
    [data-theme="light"] .schedule-card:hover,
    [data-theme="light"] .poster-slide:hover,
    [data-theme="light"] .galeri-item:hover {
      box-shadow: 0 8px 30px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
      border-color: rgba(0,0,0,0.08);
    }
    [data-theme="light"] .stat-card {
      box-shadow: 0 1px 4px rgba(0,0,0,0.03), 0 4px 12px rgba(0,0,0,0.02);
      background: rgba(255,255,255,0.95);
      border: 1px solid rgba(0,0,0,0.05);
    }
    [data-theme="light"] .stat-card:hover {
      box-shadow: 0 8px 24px rgba(0,0,0,0.07);
    }
    [data-theme="light"] .about-image {
      box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.03);
    }
    [data-theme="light"] .navbar.scrolled {
      box-shadow: 0 1px 12px rgba(0,0,0,0.05);
      border-bottom: 1px solid rgba(0,0,0,0.04);
    }
    [data-theme="light"] .hero {
      background: linear-gradient(170deg, #FDFCF6 0%, #FFF5E6 35%, #FFECD2 65%, #FDFCF6 100%);
    }
    [data-theme="light"] .cta {
      background: linear-gradient(170deg, #FDFCF6 0%, #FFF5E6 40%, #FFECD2 70%, #F8F6F0 100%);
    }
    [data-theme="light"] .footer {
      background: #F0EDE5;
    }

    /* ============ RESET & BASE ============ */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; transition: background-color 0.35s ease, color 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease; }
    ::selection { background: rgba(227,6,19,0.3); color: #fff; }
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
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .navbar.scrolled {
      padding: 10px 24px;
      background: var(--navbar-bg-scroll);
      box-shadow: 0 4px 30px var(--shadow-navbar), 0 0 60px rgba(200,160,0,0.05);
      border-bottom: 1px solid transparent;
      border-image: linear-gradient(90deg, transparent, rgba(200,160,0,0.3), rgba(227,6,19,0.2), transparent) 1;
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
    /* Floating particles */
    .hero-particles {
      position: absolute; inset: 0; z-index: 1;
      overflow: hidden; pointer-events: none;
    }
    .particle {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      animation: particleFloat linear infinite;
    }
    @keyframes particleFloat {
      0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
      10% { opacity: 1; }
      90% { opacity: 1; }
      100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
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
      animation: logoPulse 3s ease-in-out infinite, logoFloat 6s ease-in-out infinite;
      object-fit: contain;
    }
    @keyframes logoPulse {
      0%, 100% { box-shadow: 0 0 40px rgba(227,6,19,0.3), 0 0 80px rgba(227,6,19,0.1); }
      50% { box-shadow: 0 0 60px rgba(227,6,19,0.5), 0 0 120px rgba(227,6,19,0.2); }
    }
    @keyframes logoFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
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
    .section {
      padding: 100px 24px;
      position: relative;
      overflow: hidden;
    }
    .section::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 20% 50%, var(--mesh-1) 0%, transparent 50%),
                  radial-gradient(ellipse at 80% 20%, var(--mesh-2) 0%, transparent 50%),
                  radial-gradient(ellipse at 50% 80%, var(--mesh-3) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }
    .section > * { position: relative; z-index: 1; }
    .section-header { text-align: center; margin-bottom: 60px; }
    .section-label {
      display: inline-block;
      font-size: 0.7rem; font-weight: 700;
      letter-spacing: 4px; text-transform: uppercase;
      color: #E30613;
      margin-bottom: 14px;
      position: relative;
    }
    .section-title {
      font-family: 'Noto Serif', serif;
      font-size: clamp(1.8rem, 4.5vw, 2.6rem);
      font-weight: 700;
      margin-bottom: 16px;
      letter-spacing: -0.02em;
    }
    .section-desc {
      color: var(--text-muted);
      font-size: 0.95rem;
      max-width: 550px;
      margin: 0 auto;
      line-height: 1.8;
    }
    .container { max-width: 1100px; margin: 0 auto; }

    /* ============ ABOUT ============ */
    .about { background: var(--bg-secondary); }
    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
    }
    .about-image {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
    }
    .about-image img {
      width: 100%;
      border-radius: 24px;
      border: 1px solid var(--border-subtle);
      transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .about-image:hover img {
      transform: scale(1.03);
    }
    .about-image::after {
      content: '';
      position: absolute; inset: 0;
      border-radius: 24px;
      background: linear-gradient(135deg, rgba(227,6,19,0.08), transparent 60%);
      pointer-events: none;
    }
    .about-image::before {
      content: '';
      position: absolute; top: -2px; left: -2px; right: -2px; bottom: -2px;
      border-radius: 26px;
      background: linear-gradient(135deg, rgba(227,6,19,0.3), rgba(255,215,0,0.2), transparent);
      z-index: -1;
    }
    .about-text h2 {
      font-family: 'Noto Serif', serif;
      font-size: clamp(1.5rem, 3.5vw, 2.2rem);
      margin-bottom: 18px;
      line-height: 1.3;
      letter-spacing: -0.02em;
    }
    .about-text p {
      color: var(--text-secondary);
      line-height: 1.85;
      margin-bottom: 14px;
      font-size: 0.95rem;
    }
    .about-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-top: 32px;
    }
    .stat-card {
      text-align: center;
      padding: 24px 14px;
      border-radius: 16px;
      background: var(--glass-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--glass-border);
      transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(135deg, transparent 30%, rgba(255,215,0,0.1) 50%, transparent 70%);
      transform: translateX(-100%);
      transition: transform 0.7s ease;
    }
    .stat-card:hover::before { transform: translateX(100%); }
    .stat-card::after {
      content: '';
      position: absolute; top: -1px; left: -1px; right: -1px; bottom: -1px;
      border-radius: 17px;
      background: linear-gradient(135deg, rgba(255,215,0,0.2), transparent 50%, rgba(227,6,19,0.15));
      z-index: -1;
      opacity: 0;
      transition: opacity 0.4s ease;
    }
    .stat-card:hover::after { opacity: 1; }
    .stat-card:hover {
      transform: translateY(-6px) scale(1.03);
      box-shadow: 0 16px 40px rgba(0,0,0,0.2);
    }
    .stat-card .number {
      font-family: 'Noto Serif', serif;
      font-size: 2rem; font-weight: 700;
      background: linear-gradient(135deg, #FFD700, #FFA500, #FFD700);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      background-size: 200% 100%;
      animation: statShimmer 3s ease-in-out infinite;
    }
    @keyframes statShimmer {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
    .stat-card .label {
      font-size: 0.7rem; color: var(--text-muted);
      margin-top: 6px; text-transform: uppercase;
      letter-spacing: 1.5px;
      font-weight: 500;
    }

    /* ============ FEATURES ============ */
    .features {
      background: var(--bg-primary);
    }
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
    }
    .feature-card {
      padding: 36px 28px;
      border-radius: 20px;
      background: var(--glass-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--glass-border);
      transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }
    .feature-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, #E30613, #FFD700, #E30613);
      background-size: 200% 100%;
      opacity: 0;
      transition: opacity 0.4s ease;
      animation: featureLineGlow 3s ease-in-out infinite;
    }
    @keyframes featureLineGlow {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
    .feature-card::after {
      content: '';
      position: absolute; top: -50%; left: -50%;
      width: 200%; height: 200%;
      background: radial-gradient(circle, var(--card-shine) 0%, transparent 60%);
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }
    .feature-card:hover {
      transform: translateY(-8px) scale(1.02);
      border-color: rgba(227,6,19,0.2);
      box-shadow: 0 24px 48px var(--shadow-card), 0 0 0 1px rgba(227,6,19,0.08);
    }
    .feature-card:hover::before { opacity: 1; }
    .feature-card:hover::after { opacity: 1; }
    .feature-icon {
      width: 52px; height: 52px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 24px;
      margin-bottom: 18px;
      transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .feature-card:hover .feature-icon {
      transform: scale(1.15) rotate(-5deg);
    }
    .feature-icon.red { background: var(--feature-red-bg); color: #E30613; }
    .feature-icon.gold { background: var(--feature-gold-bg); color: #C8A000; }
    .feature-icon.green { background: var(--feature-green-bg); color: #4CAF50; }
    .feature-icon.blue { background: var(--feature-blue-bg); color: #2196F3; }
    .feature-card h3 {
      font-size: 1.05rem; font-weight: 600;
      margin-bottom: 8px;
      transition: color 0.3s;
    }
    .feature-card:hover h3 { color: #C8A000; }
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
      gap: 24px;
    }
    .schedule-card {
      padding: 32px;
      border-radius: 20px;
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }
    .schedule-card::before {
      content: '';
      position: absolute; top: 0; left: 0;
      width: 3px; height: 0;
      background: linear-gradient(180deg, #E30613, #FFD700);
      border-radius: 0 2px 2px 0;
      transition: height 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .schedule-card:hover::before { height: 100%; }
    .schedule-card:hover {
      border-color: rgba(227,6,19,0.15);
      transform: translateY(-4px);
      box-shadow: 0 20px 40px var(--shadow-card);
    }
    .schedule-card .time-badge {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 16px;
      border-radius: 50px;
      background: var(--schedule-badge-bg);
      color: #E30613;
      font-size: 0.78rem; font-weight: 600;
      margin-bottom: 16px;
    }
    .schedule-card h3 {
      font-size: 1.1rem; font-weight: 600;
      margin-bottom: 10px;
    }
    .schedule-card .time-range {
      font-size: 1.6rem; font-weight: 700;
      background: linear-gradient(135deg, #FFD700, #FFA500);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 6px;
    }
    .schedule-card .desc {
      font-size: 0.82rem;
      color: var(--text-muted);
      line-height: 1.6;
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
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid var(--border-card);
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
      cursor: pointer;
    }
    .poster-slide:hover {
      transform: translateY(-6px) scale(1.02);
      border-color: rgba(227,6,19,0.3);
      box-shadow: 0 24px 48px var(--shadow-card), 0 0 0 1px rgba(227,6,19,0.1);
    }
    .poster-slide img {
      width: 100%;
      height: auto;
      display: block;
      transition: transform 0.5s ease;
    }
    .poster-slide:hover img {
      transform: scale(1.05);
    }
    .poster-slide .poster-caption {
      padding: 16px 18px;
      font-size: 0.9rem;
      font-weight: 600;
      text-align: center;
    }
    .poster-nav {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 24px;
    }
    .poster-nav button {
      width: 44px; height: 44px;
      border-radius: 50%;
      border: 1px solid var(--border-card);
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
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
      transform: scale(1.1);
    }

    /* ============ GALERI ============ */
    .galeri-section { background: var(--bg-secondary); }
    .galeri-tabs {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-bottom: 32px;
      flex-wrap: wrap;
    }
    .galeri-tab {
      padding: 10px 24px;
      border-radius: 50px;
      border: 1px solid var(--border-card);
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      color: var(--text-muted);
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      font-family: 'Poppins', sans-serif;
    }
    .galeri-tab.active,
    .galeri-tab:hover {
      background: linear-gradient(135deg, rgba(227,6,19,0.15), rgba(227,6,19,0.08));
      border-color: rgba(227,6,19,0.4);
      color: #E30613;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(227,6,19,0.15);
    }
    .galeri-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .galeri-item {
      width: 280px;
      max-width: 100%;
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid var(--border-card);
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
      cursor: pointer;
      position: relative;
    }
    @media (max-width: 640px) {
      .galeri-item {
        width: calc(50% - 10px);
      }
    }
    @media (max-width: 400px) {
      .galeri-item {
        width: 100%;
      }
    }
    .galeri-item:hover {
      transform: translateY(-6px) scale(1.02);
      border-color: rgba(227,6,19,0.25);
      box-shadow: 0 20px 40px var(--shadow-card), 0 0 0 1px rgba(227,6,19,0.1);
    }
    .galeri-item img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
      transition: transform 0.5s ease;
    }
    .galeri-item:hover img {
      transform: scale(1.08);
    }
    .galeri-item .video-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 44px;
      display: flex; align-items: center; justify-content: center;
      background: rgba(0,0,0,0.3);
      transition: background 0.3s ease;
    }
    .galeri-item:hover .video-overlay {
      background: rgba(0,0,0,0.5);
    }
    .video-overlay ion-icon {
      font-size: 52px;
      color: #fff;
      filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5));
      transition: transform 0.3s ease;
    }
    .galeri-item:hover .video-overlay ion-icon {
      transform: scale(1.15);
    }
    .galeri-item .galeri-caption {
      padding: 12px 16px;
      font-size: 0.82rem;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .galeri-item .galeri-badge {
      position: absolute;
      top: 12px; right: 12px;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.68rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      backdrop-filter: blur(8px);
    }
    .galeri-badge.foto-badge {
      background: rgba(33,150,243,0.8);
      color: #fff;
    }
    .galeri-badge.video-badge {
      background: rgba(227,6,19,0.8);
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
      padding: 100px 24px;
      text-align: center;
      position: relative;
      background: linear-gradient(170deg, var(--hero-grad-1), var(--hero-grad-2), var(--hero-grad-3), var(--hero-grad-1));
      overflow: hidden;
    }
    .cta::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 30% 50%, var(--cta-glow), transparent 50%),
                  radial-gradient(ellipse at 70% 30%, rgba(255,215,0,0.04), transparent 50%);
    }
    .cta::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255,215,0,0.3), rgba(227,6,19,0.3), transparent);
    }
    .cta-content { position: relative; z-index: 2; }
    .cta h2 {
      font-family: 'Noto Serif', serif;
      font-size: clamp(1.8rem, 4.5vw, 2.8rem);
      margin-bottom: 16px;
      letter-spacing: -0.02em;
    }
    .cta p {
      color: var(--text-muted);
      font-size: 1rem;
      margin-bottom: 36px;
      max-width: 480px;
      margin-left: auto; margin-right: auto;
      line-height: 1.8;
    }
    .cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

    /* ============ FOOTER ============ */
    .footer {
      padding: 80px 24px 28px;
      background: var(--bg-tertiary);
      border-top: 1px solid transparent;
      background-image: linear-gradient(var(--bg-tertiary), var(--bg-tertiary)),
                        linear-gradient(90deg, transparent, rgba(255,215,0,0.25), rgba(227,6,19,0.25), transparent);
      background-origin: padding-box, border-box;
      background-clip: padding-box, border-box;
      position: relative;
    }
    .footer::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(ellipse at 20% 20%, var(--mesh-1) 0%, transparent 50%),
                  radial-gradient(ellipse at 80% 80%, var(--mesh-2) 0%, transparent 50%);
      pointer-events: none;
    }
    .footer > * { position: relative; z-index: 1; }
    .footer-grid {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr 1.2fr 1.5fr;
      gap: 40px;
      max-width: 1300px;
      margin: 0 auto 48px;
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
    .footer-about-logo img { width: 52px; height: 52px; border-radius: 10px; object-fit: contain; }
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
      transform: translateY(60px);
      transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1),
                  transform 1s cubic-bezier(0.16, 1, 0.3, 1),
                  filter 0.8s ease;
      filter: blur(4px);
      will-change: opacity, transform, filter;
    }
    .fade-up.visible {
      opacity: 1;
      transform: translateY(0);
      filter: blur(0);
    }
    .fade-left {
      opacity: 0;
      transform: translateX(-80px) rotate(-2deg);
      transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1),
                  transform 1s cubic-bezier(0.16, 1, 0.3, 1),
                  filter 0.8s ease;
      filter: blur(3px);
      will-change: opacity, transform, filter;
    }
    .fade-left.visible {
      opacity: 1;
      transform: translateX(0) rotate(0deg);
      filter: blur(0);
    }
    .fade-right {
      opacity: 0;
      transform: translateX(80px) rotate(2deg);
      transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1),
                  transform 1s cubic-bezier(0.16, 1, 0.3, 1),
                  filter 0.8s ease;
      filter: blur(3px);
      will-change: opacity, transform, filter;
    }
    .fade-right.visible {
      opacity: 1;
      transform: translateX(0) rotate(0deg);
      filter: blur(0);
    }
    .fade-scale {
      opacity: 0;
      transform: scale(0.8);
      transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1),
                  transform 1s cubic-bezier(0.16, 1, 0.3, 1),
                  filter 0.8s ease;
      filter: blur(5px);
      will-change: opacity, transform, filter;
    }
    .fade-scale.visible {
      opacity: 1;
      transform: scale(1);
      filter: blur(0);
    }
    .fade-rotate {
      opacity: 0;
      transform: perspective(800px) rotateY(-15deg) translateX(-30px);
      transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1),
                  transform 1.1s cubic-bezier(0.16, 1, 0.3, 1),
                  filter 0.8s ease;
      filter: blur(4px);
      will-change: opacity, transform, filter;
    }
    .fade-rotate.visible {
      opacity: 1;
      transform: perspective(800px) rotateY(0deg) translateX(0);
      filter: blur(0);
    }
    /* Stagger delays for sequential card reveals */
    .stagger-1 { transition-delay: 0s !important; }
    .stagger-2 { transition-delay: 0.08s !important; }
    .stagger-3 { transition-delay: 0.16s !important; }
    .stagger-4 { transition-delay: 0.24s !important; }
    .stagger-5 { transition-delay: 0.32s !important; }
    .stagger-6 { transition-delay: 0.40s !important; }
    .stagger-7 { transition-delay: 0.48s !important; }


    /* Shimmer text */
    @keyframes shimmer {
      0% { background-position: -200% center; }
      100% { background-position: 200% center; }
    }
    .shimmer-gold {
      background: linear-gradient(90deg, #FFD700, #FFA500, #FFD700, #FFA500, #FFD700);
      background-size: 200% auto;
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: shimmer 4s linear infinite;
    }

    /* Premium glow button pulse */
    @keyframes btnPulse {
      0%, 100% { box-shadow: 0 4px 20px rgba(227,6,19,0.3); }
      50% { box-shadow: 0 4px 30px rgba(227,6,19,0.5), 0 0 60px rgba(227,6,19,0.15); }
    }
    .btn-primary-hero { animation: btnPulse 3s ease-in-out infinite; }
    .btn-primary-hero:hover { animation: none; }

    /* Reveal line accent */
    .section-label::after {
      content: '';
      display: block;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #E30613, #FFD700);
      margin-top: 8px;
      transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .section-label.visible::after,
    .visible .section-label::after {
      width: 50px;
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

    /* --- TABLET (max 1024px) --- */
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
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 0.95rem;
      }
      .footer-grid { grid-template-columns: 1fr 1fr 1fr; gap: 30px; }
      .section { padding: 80px 20px; }
      .section-header { margin-bottom: 40px; }
      .about-grid { gap: 40px; }
      .schedule-card { padding: 24px; }
      .cta { padding: 80px 20px; }
    }

    /* --- MOBILE LANDSCAPE / SMALL TABLET (max 768px) --- */
    @media (max-width: 768px) {
      /* Navbar */
      .navbar { padding: 8px 12px; }
      .navbar-actions { gap: 6px; }
      .navbar-brand { gap: 8px; }
      .navbar-brand img { width: 30px; height: 30px; }
      .navbar-brand span { font-size: 0.9rem; }
      .navbar-links .link-text { display: none; }
      .theme-toggle { width: 38px; height: 38px; font-size: 17px; border-radius: 50%; }
      .btn-phone { width: 38px; height: 38px; font-size: 1rem; }
      .hamburger { width: 38px; height: 38px; }
      .navbar-links { gap: 6px; }
      .navbar-links .btn-login {
        width: 38px; height: 38px;
        padding: 0; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
      }
      .navbar-links .btn-register {
        padding: 8px 14px; font-size: 0.75rem; border-radius: 8px;
      }
      .navbar.scrolled { padding: 6px 12px; }

      /* Hero */
      .hero { padding: 90px 16px 50px; min-height: auto; min-height: 90dvh; }
      .hero-logo { width: 120px; padding: 10px; border-radius: 14px; }
      .hero-badge { font-size: 0.65rem; letter-spacing: 2px; padding: 5px 14px; margin-bottom: 16px; }
      .hero h1 { font-size: clamp(1.6rem, 7vw, 2.4rem); margin-bottom: 12px; }
      .hero p { font-size: 0.9rem; margin-bottom: 28px; line-height: 1.65; }
      .hero-buttons { gap: 10px; }
      .btn-primary-hero, .btn-secondary-hero { padding: 12px 24px; font-size: 0.88rem; border-radius: 10px; }
      .scroll-indicator { bottom: 24px; }

      /* About */
      .about-grid { grid-template-columns: 1fr; gap: 30px; }
      .about-image { order: -1; max-width: 320px; margin: 0 auto; }
      .about-text h2 { text-align: center; }
      .about-text p { text-align: center; }
      .about-stats { grid-template-columns: repeat(3, 1fr); gap: 10px; }
      .stat-card { padding: 16px 8px; }
      .stat-card .number { font-size: 1.4rem; }
      .stat-card .label { font-size: 0.6rem; letter-spacing: 1px; }

      /* Sections */
      .section { padding: 60px 16px; }
      .section-header { margin-bottom: 36px; }
      .section-label { font-size: 0.65rem; letter-spacing: 3px; }
      .section-title { margin-bottom: 12px; }
      .section-desc { font-size: 0.88rem; }

      /* Features */
      .features-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .feature-card { padding: 20px 16px; border-radius: 16px; }
      .feature-icon { width: 42px; height: 42px; font-size: 18px; margin-bottom: 14px; border-radius: 12px; }
      .feature-card h3 { font-size: 0.92rem; }
      .feature-card p { font-size: 0.78rem; }

      /* Schedule */
      .schedule-grid { grid-template-columns: 1fr; gap: 12px; }
      .schedule-card { padding: 22px 18px; border-radius: 16px; }
      .schedule-card .time-range { font-size: 1.3rem; }
      .schedule-card h3 { font-size: 1rem; }

      /* Poster */
      .poster-slide { flex: 0 0 220px; }
      .poster-slide .poster-caption { font-size: 0.82rem; padding: 12px 14px; }
      .poster-nav button { width: 38px; height: 38px; font-size: 16px; }

      /* Gallery */
      .galeri-tabs { flex-wrap: wrap; gap: 6px; }
      .galeri-tab { font-size: 0.78rem; padding: 6px 14px; }
      .galeri-item img { height: 160px; }
      .galeri-item .galeri-caption { font-size: 0.75rem; padding: 10px 12px; }
      .galeri-badge { font-size: 0.6rem; padding: 4px 10px; top: 8px; right: 8px; }

      /* CTA */
      .cta { padding: 60px 16px; }
      .cta h2 { margin-bottom: 12px; }
      .cta p { font-size: 0.9rem; margin-bottom: 28px; }

      /* Footer */
      .footer { padding: 50px 16px 24px; }
      .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
      .footer-col h4 { font-size: 0.9rem; margin-bottom: 12px; }
      .footer-about-desc { font-size: 0.8rem; }
      .footer-nav-links a { font-size: 0.8rem; }
      .footer-schedule td { font-size: 0.78rem; }
      .footer-contact-item { font-size: 0.8rem; }
      .footer-map iframe { height: 160px; }
      .footer-col h4::after { left: 0; }
      .footer-bottom { flex-direction: column; text-align: center; gap: 12px; }
      .footer-copy { font-size: 0.7rem; }

      /* Lightbox */
      .lightbox-content { max-width: 95vw; max-height: 80vh; }
      .lightbox-content img, .lightbox-content video { max-width: 95vw; max-height: 80vh; }
      .lightbox-close { top: 10px; right: 10px; width: 38px; height: 38px; font-size: 20px; }
    }

    /* --- MOBILE PORTRAIT (max 480px) --- */
    @media (max-width: 480px) {
      /* Hero */
      .hero { padding: 80px 14px 40px; }
      .hero-logo { width: 100px; padding: 8px; }
      .hero-logo-wrap { margin-bottom: 20px; }
      .hero h1 { font-size: clamp(1.4rem, 8vw, 2rem); }
      .hero p { font-size: 0.85rem; max-width: 100%; }
      .hero-buttons { flex-direction: column; width: 100%; }
      .hero-buttons a { width: 100%; justify-content: center; }
      .btn-primary-hero, .btn-secondary-hero { padding: 13px 20px; font-size: 0.9rem; width: 100%; justify-content: center; }

      /* About */
      .about-image { max-width: 260px; }
      .about-stats { gap: 8px; }
      .stat-card { padding: 14px 6px; border-radius: 12px; }
      .stat-card .number { font-size: 1.2rem; }
      .stat-card .label { font-size: 0.55rem; }

      /* Sections */
      .section { padding: 50px 14px; }
      .section-header { margin-bottom: 28px; }

      /* Features */
      .features-grid { grid-template-columns: 1fr; gap: 10px; }
      .feature-card { padding: 20px 18px; }

      /* Schedule */
      .schedule-card { padding: 20px 16px; }
      .schedule-card .time-range { font-size: 1.15rem; }
      .schedule-card .time-badge { font-size: 0.72rem; padding: 5px 12px; }

      /* Poster */
      .poster-slide { flex: 0 0 200px; border-radius: 14px; }
      .poster-nav { gap: 8px; margin-top: 16px; }

      /* Gallery */
      .galeri-item { width: calc(50% - 8px) !important; }
      .galeri-item img { height: 130px; }

      /* CTA */
      .cta { padding: 50px 14px; }
      .cta p { font-size: 0.85rem; }
      .cta-buttons { flex-direction: column; align-items: center; width: 100%; }
      .cta-buttons a { width: 100%; justify-content: center; }

      /* Footer */
      .footer { padding: 40px 14px 20px; }
      .footer-grid { grid-template-columns: 1fr; gap: 24px; }
      .footer-about-logo img { width: 44px; height: 44px; }
      .footer-about-logo span { font-size: 1rem; }
      .footer-map iframe { height: 140px; border-radius: 10px; }
    }

    /* --- SMALL MOBILE (max 360px) --- */
    @media (max-width: 360px) {
      .hero { padding: 75px 12px 36px; }
      .hero-logo { width: 85px; }
      .hero h1 { font-size: 1.35rem; }
      .hero p { font-size: 0.82rem; }
      .hero-badge { font-size: 0.6rem; letter-spacing: 1.5px; padding: 4px 12px; }
      .section { padding: 40px 12px; }
      .feature-card { padding: 18px 14px; }
      .feature-card h3 { font-size: 0.85rem; }
      .feature-card p { font-size: 0.75rem; }
      .stat-card .number { font-size: 1.1rem; }
      .galeri-item { width: 100% !important; }
      .galeri-item img { height: 180px; }
      .footer { padding: 36px 12px 18px; }
      .cta { padding: 40px 12px; }
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
    <a href="https://wa.me/628129215459" target="_blank" class="btn-phone" title="WhatsApp Kami"><ion-icon name="logo-whatsapp"></ion-icon></a>
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
  <div class="hero-particles" id="heroParticles"></div>
  <div class="hero-content">
    <div class="hero-logo-wrap">
      <img src="<?php echo $base_url; ?>/sw-admin/sw-assets/img/logo-dojo_hkcpng.png" alt="Dojo HKC Logo" class="hero-logo">
    </div>
    <div class="hero-badge"><?php echo ls($ls, 'hero_badge', 'INSTITUT KARATE-DO NASIONAL'); ?></div>
    <h1><?php echo ls($ls, 'hero_title', '<span class="gold shimmer-gold">Halim</span> <span class="red">Karate</span> Champion'); ?></h1>
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
      <div class="about-image fade-left">
        <?php
          $aboutImg = ls($ls, 'about_image');
          if(!empty($aboutImg) && file_exists('sw-content/landing/'.$aboutImg)){
            echo '<img src="'.$base_url.'/sw-content/landing/'.$aboutImg.'" alt="Dojo HKC" style="padding:40px; background:var(--bg-card); aspect-ratio:1; object-fit:contain;">';
          } else {
            echo '<img src="'.$base_url.'/sw-admin/sw-assets/img/logo-dojo_hkcpng.png" alt="Dojo HKC" style="padding:40px; background:var(--bg-card); aspect-ratio:1; object-fit:contain;">';
          }
        ?>
      </div>
      <div class="about-text fade-right">
        <span class="section-label"><?php echo ls($ls, 'about_label', 'Tentang Kami'); ?></span>
        <h2><?php echo ls($ls, 'about_title', 'Membentuk Karakter Melalui <span style="color:#E30613">Karate</span>'); ?></h2>
        <p><?php echo ls($ls, 'about_desc1', 'Dojo HKC (Halim Karate Champion) adalah pusat pelatihan karate di bawah naungan <strong>INKANAS</strong> (Institut Karate-Do Nasional) yang berkomitmen membentuk karakter disiplin, tangguh, dan berprestasi.'); ?></p>
        <p><?php echo ls($ls, 'about_desc2', 'Dengan pelatih bersertifikat dan fasilitas lengkap, kami menyediakan program latihan untuk semua tingkatan — dari pemula hingga atlet profesional.'); ?></p>
        <div class="about-stats">
          <div class="stat-card">
            <div class="number">120+</div>
            <div class="label">Atlet</div>
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
            ['icon'=>'camera-outline','color'=>'red','title'=>'Absensi Selfie','desc'=>'Absen dengan verifikasi wajah menggunakan teknologi AI dan deteksi lokasi GPS radius otomatis.'],
            ['icon'=>'location-outline','color'=>'gold','title'=>'Deteksi Radius','desc'=>'Sistem memverifikasi lokasi Anda berada dalam radius dojo sebelum bisa absen.'],
            ['icon'=>'document-text-outline','color'=>'green','title'=>'Izin','desc'=>'Ajukan izin langsung dari aplikasi dan pantau status persetujuannya.'],
            ['icon'=>'stats-chart-outline','color'=>'blue','title'=>'Riwayat Lengkap','desc'=>'Lihat riwayat kehadiran, keterlambatan, dan rekap bulanan secara detail.']
          ];
        }
        foreach($featuresArr as $feat):
      ?>
      <div class="feature-card fade-scale">
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
          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=Dojo+HKC+Halim+Karate+Champion,Lubang+Buaya,Cipayung,Jakarta+Timur&zoom=16"
          allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <a href="https://www.google.com/maps/search/Dojo+HKC+Halim+Karate+Champion+Lubang+Buaya+Cipayung+Jakarta+Timur" target="_blank" class="footer-map-link">
          <ion-icon name="navigate-outline"></ion-icon> Buka di Google Maps
        </a>
      </div>
    </div>
  </div>
  <hr class="footer-divider">
  <div class="footer-bottom">
    <p class="footer-copy">
      &copy; 2021 — <?php echo date('Y'); ?> <?php echo $website_name; ?> — 
      Halim Karate Champion &bull; INKANAS
    </p>
    <div class="footer-socials">
      <a href="https://wa.me/628129215459" target="_blank" title="WhatsApp"><ion-icon name="logo-whatsapp"></ion-icon></a>
      <a href="tel:08129215459" title="Telepon"><ion-icon name="call-outline"></ion-icon></a>
    </div>
  </div>
</footer>

<!-- ===== SCRIPTS ===== -->
<script>
// ===== HAMBURGER MENU =====
(function() {
  const hamburger = document.getElementById('hamburgerBtn');
  const navMenu = document.getElementById('navMenu');
  if (hamburger && navMenu) {
    hamburger.addEventListener('click', function() {
      hamburger.classList.toggle('active');
      navMenu.classList.toggle('open');
    });
    navMenu.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        hamburger.classList.remove('active');
        navMenu.classList.remove('open');
      });
    });
  }
})();

// ===== SCROLL TO TOP =====
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== NAVBAR SCROLL EFFECT + ACTIVE LINK =====
(function() {
  const navbar = document.getElementById('navbar');
  const navLinks = document.querySelectorAll('.nav-menu a[href^="#"]');
  const sections = [];
  navLinks.forEach(link => {
    const id = link.getAttribute('href').substring(1);
    const sec = document.getElementById(id);
    if (sec) sections.push({ el: sec, link: link });
  });

  window.addEventListener('scroll', function() {
    // Navbar solid on scroll
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
    // Active link highlighting
    let current = '';
    sections.forEach(s => {
      const top = s.el.offsetTop - 120;
      if (window.scrollY >= top) current = s.link.getAttribute('href');
    });
    navLinks.forEach(l => {
      l.style.color = l.getAttribute('href') === current ? '#C8A000' : '';
      l.style.fontWeight = l.getAttribute('href') === current ? '600' : '';
    });
  }, { passive: true });
})();

// ===== FLOATING PARTICLES =====
(function() {
  const container = document.getElementById('heroParticles');
  if (!container) return;
  const colors = [
    'rgba(255,215,0,0.4)', 'rgba(227,6,19,0.3)', 'rgba(255,165,0,0.35)',
    'rgba(255,255,255,0.15)', 'rgba(200,160,0,0.3)'
  ];
  for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.classList.add('particle');
    const size = Math.random() * 4 + 2;
    p.style.width = size + 'px';
    p.style.height = size + 'px';
    p.style.left = Math.random() * 100 + '%';
    p.style.background = colors[Math.floor(Math.random() * colors.length)];
    p.style.animationDuration = (Math.random() * 12 + 8) + 's';
    p.style.animationDelay = (Math.random() * 10) + 's';
    p.style.boxShadow = '0 0 ' + (size * 2) + 'px ' + p.style.background;
    container.appendChild(p);
  }
})();

// ===== ANIMATED COUNTER =====
function animateCounter(el, target, suffix) {
  const duration = 2000;
  const start = performance.now();
  const isPlus = suffix === '+';

  function update(now) {
    const elapsed = now - start;
    const progress = Math.min(elapsed / duration, 1);
    // Ease out cubic
    const ease = 1 - Math.pow(1 - progress, 3);
    const current = Math.floor(ease * target);
    el.textContent = current + (isPlus ? '+' : suffix);
    if (progress < 1) requestAnimationFrame(update);
    else el.textContent = target + suffix;
  }
  requestAnimationFrame(update);
}

// ===== INTERSECTION OBSERVER (PREMIUM) =====
(function() {
  const animClasses = ['.fade-up', '.fade-left', '.fade-right', '.fade-scale', '.fade-rotate'];
  
  // Auto-stagger: assign stagger-N to cards within grid containers
  const gridSelectors = [
    '.features-grid',
    '.schedule-grid',
    '.galeri-grid',
    '.poster-grid',
    '.atlet-grid',
    '.about-stats'
  ];
  gridSelectors.forEach(sel => {
    const grid = document.querySelector(sel);
    if (!grid) return;
    const children = grid.querySelectorAll('.fade-up, .fade-left, .fade-right, .fade-scale, .fade-rotate');
    children.forEach((child, i) => {
      child.classList.add('stagger-' + (i + 1));
    });
  });
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');

        // Counter animation for stat numbers
        if (entry.target.classList.contains('stat-card')) {
          const numEl = entry.target.querySelector('.number');
          if (numEl && !numEl.dataset.animated) {
            numEl.dataset.animated = '1';
            const text = numEl.textContent.trim();
            const num = parseInt(text.replace(/[^0-9]/g, ''));
            const suffix = text.replace(/[0-9]/g, '');
            if (!isNaN(num)) animateCounter(numEl, num, suffix);
          }
        }
        
        // Clean up will-change after animation for performance
        setTimeout(() => {
          entry.target.style.willChange = 'auto';
        }, 1200);
        
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

  animClasses.forEach(cls => {
    document.querySelectorAll(cls).forEach(el => observer.observe(el));
  });

  // Also observe stat cards
  document.querySelectorAll('.stat-card').forEach(el => {
    if (!el.classList.contains('fade-up') && !el.classList.contains('fade-scale')) {
      el.classList.add('fade-scale');
    }
    observer.observe(el);
  });
})();

// ===== FEATURE CARD TILT EFFECT =====
(function() {
  if (window.innerWidth < 768) return; // skip on mobile
  document.querySelectorAll('.feature-card').forEach(card => {
    card.addEventListener('mousemove', function(e) {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      const rotateX = ((y - centerY) / centerY) * -4;
      const rotateY = ((x - centerX) / centerX) * 4;
      card.style.transform = 'perspective(800px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-6px) scale(1.02)';
    });
    card.addEventListener('mouseleave', function() {
      card.style.transform = '';
    });
  });
})();

// ===== THEME TOGGLE =====
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

// ===== LIGHTBOX =====
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
    const vid = overlay.querySelector('video');
    if (vid) vid.pause();
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

// ===== GALLERY FILTER WITH ANIMATION =====
function filterGaleri(tipe, btn) {
  document.querySelectorAll('.galeri-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.galeri-item').forEach((item, i) => {
    if (tipe === 'semua' || item.getAttribute('data-tipe') === tipe) {
      item.style.display = '';
      item.style.opacity = '0';
      item.style.transform = 'scale(0.9)';
      setTimeout(() => {
        item.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
        item.style.opacity = '1';
        item.style.transform = 'scale(1)';
      }, i * 50);
    } else {
      item.style.display = 'none';
    }
  });
}
</script>

</body>
</html>
<?php } ?>
