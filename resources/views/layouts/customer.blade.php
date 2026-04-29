<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelApp</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

        :root,
        [data-theme="light"] {
            --bg-body:              #f8f9fa;
            --bg-navbar:            #ffffff;
            --bg-dropdown:          #ffffff;
            --bg-mobile-menu:       #ffffff;
            --border-color:         #eeeeee;
            --border-light:         #f0f0f0;
            --border-item:          #f9f9f9;
            --border-head:          #f4f4f4;
            --text-primary:         #000000;
            --text-muted:           #64748b;
            --text-time:            #b0b7c3;
            --text-title:           #1a1a2e;
            --btn-bg:               #ffffff;
            --btn-border:           #efefef;
            --btn-hover-bg:         #f5f0ff;
            --btn-hover-border:     #d8cbff;
            --btn-color:            #555555;
            --notif-unread:         #f8f5ff;
            --notif-unread-hover:   #f1eaff;
            --notif-hover:          #fafafa;
            --mobile-hover:         #f5f0ff;
            --dd-item-hover:        rgba(0,0,0,0.04);
        }

        [data-theme="dark"] {
            --bg-body:              #0f1117;
            --bg-navbar:            #1a1d27;
            --bg-dropdown:          #1e2130;
            --bg-mobile-menu:       #1a1d27;
            --border-color:         #2a2d3e;
            --border-light:         #252836;
            --border-item:          #1e2130;
            --border-head:          #252836;
            --text-primary:         #e8eaf0;
            --text-muted:           #8b93a7;
            --text-time:            #5a6070;
            --text-title:           #e8eaf0;
            --btn-bg:               #252836;
            --btn-border:           #2e3248;
            --btn-hover-bg:         #2a2545;
            --btn-hover-border:     #5a4d8a;
            --btn-color:            #a0a8bc;
            --notif-unread:         #1e1a35;
            --notif-unread-hover:   #241e42;
            --notif-hover:          #1e2130;
            --mobile-hover:         #1e1a35;
            --dd-item-hover:        rgba(255,255,255,0.06);
        }

        html { overflow-y: scroll; }
        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            margin: 0;
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            background: var(--bg-navbar);
            padding: 0 40px;
            border-bottom: 1px solid var(--border-color);
            height: 80px;
            position: sticky; top: 0; z-index: 1000;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: 200px 1fr auto;
            align-items: center;
            height: 80px; width: 100%;
        }

        .logo-container {
            display: flex; align-items: center;
            width: 150px; text-decoration: none;
        }

        .customer-logo {
            height: 70px !important; width: auto;
            object-fit: contain; transition: transform 0.3s ease;
        }
        .customer-logo:hover { transform: scale(1.05); }

        .nav-center-wrapper {
            display: flex; justify-content: center;
            align-items: center; height: 100%;
        }

        .navbar-nav {
            display: flex; flex-direction: row;
            gap: 35px; margin: 0; padding: 0;
            list-style: none; height: 100%;
        }

        .nav-link {
            color: var(--text-primary) !important;
            font-size: 0.95rem; font-weight: 500;
            padding: 0 !important; line-height: 80px; position: relative;
            transition: color 0.2s ease; white-space: nowrap;
        }
        .nav-link:hover { color: #6f42c1 !important; }
        .nav-link.active::after {
            content: ''; position: absolute;
            bottom: 0; left: 0; width: 100%; height: 4px;
            background: linear-gradient(90deg, #007bff, #6f42c1);
            border-radius: 4px 4px 0 0;
        }

        .nav-right {
            display: flex; justify-content: flex-end;
            align-items: center; gap: 10px;
        }

        .icon-btn {
            width: 42px; height: 42px; border-radius: 50%;
            border: 1px solid var(--btn-border);
            background: var(--btn-bg); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s, border-color 0.2s, color 0.2s, transform 0.2s;
            outline: none !important; box-shadow: none !important;
            color: var(--btn-color); font-size: 1rem;
            flex-shrink: 0;
        }
        .icon-btn:hover {
            background: var(--btn-hover-bg);
            border-color: var(--btn-hover-border);
            color: #6f42c1;
        }
        .icon-btn:active { transform: scale(0.94); }

        [data-theme="light"] .theme-btn .icon-moon { display: none; }
        [data-theme="light"] .theme-btn .icon-sun  { display: block; }
        [data-theme="dark"]  .theme-btn .icon-sun  { display: none; }
        [data-theme="dark"]  .theme-btn .icon-moon { display: block; }

        [data-theme="light"] .theme-btn:hover { color: #f59e0b !important; border-color: #fcd34d !important; background: #fffbeb !important; }
        [data-theme="dark"]  .theme-btn:hover { color: #a78bfa !important; border-color: #7c3aed !important; background: #1e1a35 !important; }

        .hamburger-btn { display: none; }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 80px; left: 0; right: 0;
            background: var(--bg-mobile-menu);
            border-bottom: 1px solid var(--border-color);
            z-index: 999;
            padding: 12px 0;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            animation: slideDown 0.2s ease both;
            transition: background 0.3s ease;
        }
        .mobile-menu.open { display: block; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .mobile-menu .mobile-nav-link {
            display: block; padding: 14px 28px;
            color: var(--text-primary) !important;
            font-size: 0.95rem; font-weight: 500;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
        }
        .mobile-menu .mobile-nav-link:hover,
        .mobile-menu .mobile-nav-link.active {
            background: var(--mobile-hover);
            color: #6f42c1 !important;
            border-left-color: #6f42c1;
        }

        .bell-wrapper { position: relative; }

        .bell-badge {
            position: absolute; top: -3px; right: -3px;
            min-width: 18px; height: 18px;
            background: #ef4444; color: #fff;
            font-size: 0.6rem; font-weight: 700;
            border-radius: 100px; border: 2px solid var(--bg-navbar);
            display: flex; align-items: center; justify-content: center;
            padding: 0 3px; line-height: 1;
            animation: badgePop 0.3s cubic-bezier(0.68,-0.55,0.27,1.55) both;
            transition: border-color 0.3s ease;
        }
        .bell-badge.hidden { display: none; }

        @keyframes badgePop {
            from { transform: scale(0); }
            to   { transform: scale(1); }
        }

        .notif-dropdown {
            position: absolute; top: calc(100% + 14px); right: 0;
            width: 360px; background: var(--bg-dropdown);
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            border: 1px solid var(--border-light);
            z-index: 2000; display: none; overflow: hidden;
            animation: dropFade 0.2s ease both;
            transition: background 0.3s ease;
        }
        .notif-dropdown.open { display: block; }

        @keyframes dropFade {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .notif-head {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 16px 18px 12px;
            border-bottom: 1px solid var(--border-head);
        }
        .notif-head .notif-title { font-size: 0.88rem; font-weight: 700; color: var(--text-title); }
        .notif-mark-all {
            font-size: 0.72rem; color: #6f42c1; font-weight: 600;
            cursor: pointer; border: none; background: none; padding: 0;
            transition: opacity 0.2s;
        }
        .notif-mark-all:hover { opacity: 0.7; }

        .notif-list { max-height: 420px; overflow-y: auto; }
        .notif-list::-webkit-scrollbar { width: 4px; }
        .notif-list::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        .notif-item {
            display: flex; align-items: flex-start;
            gap: 12px; padding: 13px 18px;
            border-bottom: 1px solid var(--border-item);
            cursor: pointer; transition: background 0.15s;
            text-decoration: none; color: inherit;
        }
        .notif-item:hover { background: var(--notif-hover); }
        .notif-item.unread { background: var(--notif-unread); }
        .notif-item.unread:hover { background: var(--notif-unread-hover); }

        .notif-icon-wrap {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; flex-shrink: 0;
        }

        .notif-body { flex: 1; min-width: 0; }
        .notif-body .n-title {
            font-size: 0.82rem; font-weight: 600; color: var(--text-title);
            margin-bottom: 2px;
            white-space: normal; overflow: visible; word-break: break-word;
        }
        .notif-body .n-msg {
            font-size: 0.76rem; color: var(--text-muted);
            white-space: normal; overflow: visible; word-break: break-word;
        }
        .notif-body .n-time { font-size: 0.68rem; color: var(--text-time); margin-top: 4px; }

        .unread-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #6f42c1; flex-shrink: 0; margin-top: 6px;
        }

        .notif-empty {
            padding: 40px 20px; text-align: center;
            color: var(--text-time); font-size: 0.82rem;
        }
        .notif-empty i { font-size: 2rem; margin-bottom: 10px; display: block; }

        .profile-btn {
            background: none; border: none; padding: 0;
            cursor: pointer; outline: none !important;
            box-shadow: none !important; width: 42px; height: 42px;
        }
        .dropdown-toggle::after { display: none !important; }
        .profile-img {
            width: 42px; height: 42px; object-fit: cover;
            border-radius: 50%; border: 1px solid var(--btn-border);
            background-color: var(--btn-bg); display: block;
            transition: border-color 0.3s ease;
        }

        .dropdown-menu {
            background: var(--bg-dropdown) !important;
            border: 1px solid var(--border-light) !important;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
            margin-top: 15px !important; padding: 8px 0;
            animation: dropdownFade 0.2s ease-out;
        }
        .dropdown-menu .dropdown-item { color: var(--text-primary) !important; }
        .dropdown-menu .dropdown-item:hover { background: var(--dd-item-hover) !important; }
        .dropdown-menu .dropdown-divider { border-color: var(--border-color) !important; }
        .dropdown-menu .text-muted { color: var(--text-muted) !important; }
        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .n-booking  { background: #eff6ff; color: #3b82f6; }
        .n-payment  { background: #f0fdf4; color: #22c55e; }
        .n-cancel   { background: #fff7ed; color: #f97316; }
        .n-alert    { background: #fef2f2; color: #ef4444; }
        .n-default  { background: #f5f0ff; color: #6f42c1; }

        @media (max-width: 992px) {
            .navbar { padding: 0 24px; }
            .navbar-nav { gap: 22px; }
            .nav-link { font-size: 0.88rem; }
        }

        @media (max-width: 768px) {
            .navbar { padding: 0 16px; height: 64px; }
            .nav-grid { grid-template-columns: 1fr auto; height: 64px; }
            .nav-center-wrapper { display: none; }
            .hamburger-btn { display: flex; }
            .customer-logo { height: 52px !important; }
            .notif-dropdown { width: calc(100vw - 32px); right: -8px; }
            .mobile-menu { top: 64px; }
            .nav-link.active::after { display: none; }
        }

        @media (max-width: 480px) {
            .navbar { padding: 0 12px; }
            .icon-btn { width: 38px; height: 38px; }
            .profile-btn, .profile-img { width: 38px; height: 38px; }
            .nav-right { gap: 8px; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-grid">

        <div class="nav-left">
            <a class="logo-container" href="{{ route('customer.landing') }}">
                <img src="{{ asset('images/bytetrip_logo-Picsart-BackgroundRemover-removebg-preview.png') }}" alt="Logo" class="customer-logo">
            </a>
        </div>

        {{-- Desktop center nav --}}
        <div class="nav-center-wrapper">
            <ul class="navbar-nav">
                <li>
                    <a class="nav-link {{ Route::is('customer.landing') ? 'active' : '' }}"
                       href="{{ route('customer.landing') }}">Home</a>
                </li>
                <li>
                    <a class="nav-link {{ Route::is('customer.destinations') || Route::is('customer.destination.packages') || Route::is('customer.package_details') ? 'active' : '' }}"
                       href="{{ route('customer.destinations') }}">Tour Packages</a>
                </li>
                <li>
                    <a class="nav-link {{ Route::is('customer.bookings') ? 'active' : '' }}"
                       href="{{ route('customer.bookings') }}">My Bookings</a>
                </li>
            </ul>
        </div>

        {{-- Right side: theme → bell → profile → hamburger --}}
        <div class="nav-right">

            {{-- Theme toggle --}}
            <button class="icon-btn theme-btn" id="themeBtn" title="Toggle appearance">
                <i class="fas fa-sun  icon-sun"></i>
                <i class="fas fa-moon icon-moon"></i>
            </button>

            {{-- Bell --}}
            <div class="bell-wrapper" id="bellWrapper">
                <button class="icon-btn" id="bellBtn" title="Notifications">
                    <i class="fas fa-bell"></i>
                </button>
                <span class="bell-badge hidden" id="bellBadge">0</span>

                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-head">
                        <span class="notif-title">Notifications</span>
                        <button class="notif-mark-all" id="markAllBtn">Mark all as read</button>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="notif-empty">
                            <i class="fas fa-bell-slash"></i>
                            No notifications yet
                        </div>
                    </div>
                </div>
            </div>

            {{-- Profile --}}
            <div class="dropdown">
                <button class="profile-btn dropdown-toggle" type="button" id="profileDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->fullname) . '&background=random&color=fff' }}"
                         class="profile-img">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li class="px-4 py-2">
                        <span class="d-block" style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Customer</span>
                        <span class="fw-bold" style="color:var(--text-primary);">{{ Auth::user()->fullname }}</span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('account.settings') }}"><i class="fa fa-cog me-2" style="color:var(--text-muted);"></i> Account Settings</a></li>
                    <li><a class="dropdown-item" href="{{ route('customer.user-guide') }}"><i class="fa fa-book me-2" style="color:var(--text-muted);"></i> User Guide</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fa fa-sign-out-alt me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

            {{-- Hamburger --}}
            <button class="icon-btn hamburger-btn" id="hamburgerBtn" title="Menu">
                <i class="fas fa-bars" id="hamburgerIcon"></i>
            </button>

        </div>
    </div>
</nav>

{{-- Mobile slide-down menu --}}
<div class="mobile-menu" id="mobileMenu">
    <a class="mobile-nav-link {{ Route::is('customer.landing') ? 'active' : '' }}"
       href="{{ route('customer.landing') }}">
        <i class="fas fa-home me-2"></i> Home
    </a>
    <a class="mobile-nav-link {{ Route::is('customer.destinations') || Route::is('customer.destination.packages') || Route::is('customer.package_details') ? 'active' : '' }}"
       href="{{ route('customer.destinations') }}">
        <i class="fas fa-map-marked-alt me-2"></i> Tour Packages
    </a>
    <a class="mobile-nav-link {{ Route::is('customer.bookings') ? 'active' : '' }}"
       href="{{ route('customer.bookings') }}">
        <i class="fas fa-calendar-check me-2"></i> My Bookings
    </a>
</div>

<main>
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {

    /* ── Theme ── */
    const themeBtn = document.getElementById('themeBtn');
    const html     = document.documentElement;
    const saved    = localStorage.getItem('bytetrip-theme') || 'light';
    html.setAttribute('data-theme', saved);

    themeBtn.addEventListener('click', function() {
        const next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        html.setAttribute('data-theme', next);
        localStorage.setItem('bytetrip-theme', next);
    });

    /* ── Hamburger ── */
    const hamburgerBtn  = document.getElementById('hamburgerBtn');
    const hamburgerIcon = document.getElementById('hamburgerIcon');
    const mobileMenu    = document.getElementById('mobileMenu');

    hamburgerBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const open = mobileMenu.classList.toggle('open');
        hamburgerIcon.className = open ? 'fas fa-times' : 'fas fa-bars';
    });

    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && !hamburgerBtn.contains(e.target)) {
            mobileMenu.classList.remove('open');
            hamburgerIcon.className = 'fas fa-bars';
        }
    });

    /* ── Notifications ── */
    const bellBtn    = document.getElementById('bellBtn');
    const bellBadge  = document.getElementById('bellBadge');
    const notifDrop  = document.getElementById('notifDropdown');
    const notifList  = document.getElementById('notifList');
    const markAllBtn = document.getElementById('markAllBtn');

    const typeMap = {
        booking_submitted:      { cls: 'n-booking', icon: 'fa-calendar-check'       },
        booking_approved:       { cls: 'n-booking', icon: 'fa-calendar-check'       },
        booking_rejected:       { cls: 'n-alert',   icon: 'fa-calendar-xmark'       },
        new_booking:            { cls: 'n-booking', icon: 'fa-calendar-plus'        },
        payment_submitted:      { cls: 'n-payment', icon: 'fa-coins'                },
        payment_verified:       { cls: 'n-payment', icon: 'fa-circle-check'         },
        payment_rejected:       { cls: 'n-alert',   icon: 'fa-triangle-exclamation' },
        cancellation_requested: { cls: 'n-cancel',  icon: 'fa-xmark-circle'         },
        cancellation_approved:  { cls: 'n-cancel',  icon: 'fa-xmark-circle'         },
        cancellation_rejected:  { cls: 'n-alert',   icon: 'fa-triangle-exclamation' },
        cancellation_request:   { cls: 'n-cancel',  icon: 'fa-xmark-circle'         },
        default:                { cls: 'n-default', icon: 'fa-bell'                 },
    };

    function timeAgo(dateStr) {
        const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
        if (diff < 60)    return 'Just now';
        if (diff < 3600)  return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        return Math.floor(diff / 86400) + 'd ago';
    }

    function attachNotifHandlers() {
        notifList.querySelectorAll('.notif-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const id   = this.dataset.id;
                const link = this.dataset.link;
                fetch('/notifications/' + id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(function() {
                    fetchNotifications();
                    if (link && link !== '') {
                        window.location.href = link;
                    }
                }).catch(function(err) {
                    console.error('Mark read error:', err);
                });
            });
        });
    }

    function renderNotifications(data) {
        if (!data.length) {
            notifList.innerHTML = '<div class="notif-empty"><i class="fas fa-bell-slash"></i>No notifications yet</div>';
            return;
        }
        notifList.innerHTML = data.map(function(n) {
            const t = typeMap[n.type] || typeMap.default;
            return '<a class="notif-item ' + (n.is_read ? '' : 'unread') + '" href="#"'
                 + ' data-id="' + n.id + '"'
                 + ' data-link="' + (n.link ? n.link.replace(/"/g, '&quot;') : '') + '">'
                 + '<div class="notif-icon-wrap ' + t.cls + '"><i class="fas ' + t.icon + '"></i></div>'
                 + '<div class="notif-body">'
                 + '<div class="n-title">' + n.title + '</div>'
                 + '<div class="n-msg">' + n.message + '</div>'
                 + '<div class="n-time">' + timeAgo(n.created_at) + '</div>'
                 + '</div>'
                 + (n.is_read ? '' : '<div class="unread-dot"></div>')
                 + '</a>';
        }).join('');

        attachNotifHandlers();
    }

    function updateBadge(count) {
        if (count > 0) {
            bellBadge.textContent = count > 99 ? '99+' : count;
            bellBadge.classList.remove('hidden');
        } else {
            bellBadge.classList.add('hidden');
        }
    }

    function fetchNotifications() {
        fetch('/notifications/feed')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                renderNotifications(data);
                updateBadge(data.filter(function(n) { return !n.is_read; }).length);
            })
            .catch(function(err) { console.error('Notification fetch error:', err); });
    }

    markAllBtn.addEventListener('click', function() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(function() { fetchNotifications(); });
    });

    bellBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = notifDrop.classList.contains('open');
        notifDrop.classList.toggle('open', !isOpen);
        if (!isOpen) fetchNotifications();
    });

    document.addEventListener('click', function(e) {
        if (!document.getElementById('bellWrapper').contains(e.target)) {
            notifDrop.classList.remove('open');
        }
    });

    fetchNotifications();
    setInterval(fetchNotifications, 30000);

})();
</script>
</body>
</html>