<?php
$current_page = basename($_SERVER['PHP_SELF']);
$active_home = ($current_page == 'dashboard.php') ? 'active' : '';
$active_transaksi = in_array($current_page, ['transaksi.php', 'proses_pembayaran.php', 'struk.php', 'struk_banyak.php']) ? 'active' : '';
$active_kelola = in_array($current_page, ['kelola_produk.php', 'tambah.php', 'edit.php', 'import.php']) ? 'active' : '';
?>

<!-- Navbar CSS Styling (Self-Contained) -->
<style>
/* Navbar Container */
.modern-navbar {
    background: linear-gradient(135deg, #1E104E 0%, #2A1A68 100%);
    padding: 15px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(30, 16, 78, 0.18);
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
    z-index: 1000;
    transition: all 0.3s ease;
}

/* Logo Styling */
.modern-navbar .navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #ffffff;
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 0.5px;
    transition: transform 0.3s ease;
}

.modern-navbar .navbar-brand:hover {
    transform: scale(1.02);
}

.modern-navbar .navbar-brand .logo-icon {
    font-size: 24px;
    animation: cartBounce 2s infinite ease-in-out;
    display: inline-block;
}

@keyframes cartBounce {
    0%, 100% { transform: translateY(0) rotate(0); }
    50% { transform: translateY(-3px) rotate(-4deg); }
}

/* Nav Menu Group */
.modern-navbar .nav-menu {
    display: flex;
    align-items: center;
    gap: 15px;
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Nav Items */
.modern-navbar .nav-item {
    position: relative;
}

.modern-navbar .nav-link {
    color: #cbd5e1;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    padding: 10px 18px;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: block;
    position: relative;
}

/* Underline Animation Effect on Hover */
.modern-navbar .nav-link::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 50%;
    width: 0;
    height: 3px;
    background: #00d2ff;
    border-radius: 2px;
    transition: all 0.3s ease;
    transform: translateX(-50%);
    box-shadow: 0 0 8px rgba(0, 210, 255, 0.5);
}

.modern-navbar .nav-link:hover {
    color: #ffffff;
}

.modern-navbar .nav-link:hover::after {
    width: 60%;
}

/* Active State */
.modern-navbar .nav-link.active {
    color: #ffffff;
    background-color: rgba(255, 255, 255, 0.08);
}

.modern-navbar .nav-link.active::after {
    width: 60%;
    background: #00d2ff;
}

/* Logout Button (Special Styling) */
.modern-navbar .nav-link.logout-btn {
    color: #ff6b6b;
    border: 1.5px solid rgba(255, 107, 107, 0.4);
    background-color: transparent;
    padding: 8px 20px;
    border-radius: 30px;
}

.modern-navbar .nav-link.logout-btn::after {
    display: none;
}

.modern-navbar .nav-link.logout-btn:hover {
    background-color: #ff6b6b;
    color: #ffffff;
    border-color: #ff6b6b;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    transform: translateY(-1px);
}

/* Hamburger Button */
.modern-navbar .menu-toggle {
    display: none;
    flex-direction: column;
    justify-content: space-between;
    width: 24px;
    height: 18px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    z-index: 1001;
}

.modern-navbar .menu-toggle span {
    width: 100%;
    height: 3px;
    background-color: #ffffff;
    border-radius: 2px;
    transition: all 0.3s ease;
}

/* Hamburger Active/Open Animation */
.modern-navbar .menu-toggle.open span:nth-child(1) {
    transform: translateY(7.5px) rotate(45deg);
}

.modern-navbar .menu-toggle.open span:nth-child(2) {
    opacity: 0;
}

.modern-navbar .menu-toggle.open span:nth-child(3) {
    transform: translateY(-7.5px) rotate(-45deg);
}

/* Responsive CSS */
@media (max-width: 991px) {
    .modern-navbar {
        padding: 15px 20px;
        border-radius: 0;
        margin: -30px -30px 30px -30px; /* Offset the body margins for mobile header to go full-width */
    }

    .modern-navbar .menu-toggle {
        display: flex;
    }

    .modern-navbar .nav-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #1E104E 0%, #2A1A68 100%);
        flex-direction: column;
        gap: 0;
        padding: 15px 20px;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }

    .modern-navbar .nav-menu.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .modern-navbar .nav-item {
        width: 100%;
        text-align: left;
    }

    .modern-navbar .nav-link {
        padding: 12px 15px;
        width: 100%;
        border-radius: 8px;
    }

    .modern-navbar .nav-link::after {
        display: none;
    }

    .modern-navbar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-left: 4px solid #00d2ff;
        border-radius: 0 8px 8px 0;
    }

    .modern-navbar .nav-link.logout-btn {
        margin-top: 15px;
        text-align: center;
        border-radius: 30px;
    }
}
</style>

<!-- Navbar HTML -->
<header class="modern-navbar">
    <a href="dashboard.php" class="navbar-brand">
        <span class="logo-icon">🛒</span>
        <span class="logo-text">KASIR MINIMARKET</span>
    </a>

    <!-- Hamburger menu toggle -->
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Nav Menu Links -->
    <ul class="nav-menu" id="navMenu">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= $active_home; ?>">Home</a>
        </li>
        <li class="nav-item">
            <a href="transaksi.php" class="nav-link <?= $active_transaksi; ?>">Transaksi Kasir</a>
        </li>
        <li class="nav-item">
            <a href="kelola_produk.php" class="nav-link <?= $active_kelola; ?>">Kelola Stok</a>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="nav-link logout-btn">Logout</a>
        </li>
    </ul>
</header>

<!-- Navbar Mobile Menu JavaScript Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            // Toggle hamburger icon animation
            menuToggle.classList.toggle('open');
            // Toggle dropdown navigation open class
            navMenu.classList.toggle('open');
        });

        // Close menu if user clicks outside of navbar area
        document.addEventListener('click', function(event) {
            const isClickInsideNavbar = menuToggle.contains(event.target) || navMenu.contains(event.target);
            if (!isClickInsideNavbar && navMenu.classList.contains('open')) {
                menuToggle.classList.remove('open');
                navMenu.classList.remove('open');
            }
        });
    }
});
</script>