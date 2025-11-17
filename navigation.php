<?php
// Set current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        /* Modern Scrollbar */
        .custom-scrollbar {
            overflow-y: auto;
            overflow-x: hidden;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Nav Item Hover Effect */
        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(180deg, #60a5fa, #3b82f6);
            transform: scaleY(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            transform: scaleY(1);
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: rgba(59, 130, 246, 0.15);
            font-weight: 600;
        }

        .nav-item .nav-icon {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover .nav-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .nav-item.active .nav-icon {
            color: #60a5fa;
        }

        /* Badge Animation */
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            50% {
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }
        }

        .badge-glow {
            animation: pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Sidebar Transition */
        .sidebar-expanded {
            width: 16rem;
        }

        .sidebar-collapsed {
            width: 5rem;
        }

        /* Tooltip */
        .tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%) translateX(10px);
            background: rgba(17, 24, 39, 0.95);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        .nav-item:hover .tooltip {
            opacity: 1;
            transform: translateY(-50%) translateX(15px);
        }

        /* Mobile Menu Animation */
        .mobile-menu-enter {
            animation: slideInLeft 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        /* Logo Glow Effect */
        .logo-glow {
            box-shadow: 0 0 20px rgba(96, 165, 250, 0.3);
            transition: box-shadow 0.3s ease;
        }

        .logo-glow:hover {
            box-shadow: 0 0 30px rgba(96, 165, 250, 0.5);
        }

        /* Group Header */
        .group-header {
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
        }

        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(180deg,
                #1e3a8a 0%,
                #1e40af 50%,
                #1e3a8a 100%
            );
        }

        /* Modern Toggle Button */
        .toggle-btn {
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>

<!-- Desktop Sidebar Navigation -->
<aside id="sidebar" class="hidden md:flex md:flex-col sidebar-expanded gradient-bg shadow-2xl fixed left-0 top-0 h-screen z-50 transition-all duration-300 ease-in-out">
    <!-- Logo/Header -->
    <div class="flex items-center justify-between px-4 py-5 border-b border-white/10">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="logo-glow w-11 h-11 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 transform hover:rotate-12 transition-all duration-300">
                <i class="fas fa-graduation-cap text-white text-xl"></i>
            </div>
            <div id="logoText" class="flex flex-col transition-opacity duration-300">
                <span class="text-white font-bold text-base leading-tight">LFHS</span>
                <span class="text-blue-200 text-xs">Admin Panel</span>
            </div>
        </div>

        <!-- Toggle Button (Desktop) -->
        <button id="sidebarToggle" class="toggle-btn w-8 h-8 rounded-lg flex items-center justify-center text-white/80 hover:text-white">
            <i class="fas fa-angles-left text-sm transition-transform duration-300" id="toggleIcon"></i>
        </button>
    </div>

    <!-- Navigation Items with Scroll -->
    <nav class="custom-scrollbar flex-1 py-4">
        <!-- Main Section -->
        <div class="group-header" id="mainHeader">Main</div>
        <ul class="space-y-1 px-3">
            <!-- Dashboard -->
            <li>
                <a href="index.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'index.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-home w-5 text-center text-base"></i>
                    <span class="nav-text">Dashboard</span>
                    <span class="tooltip">Dashboard</span>
                </a>
            </li>

            <!-- Messages with Badge -->
            <li>
                <a href="admin_contact_messages.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'admin_contact_messages.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-envelope w-5 text-center text-base"></i>
                    <span class="nav-text">Messages</span>
                    <?php
                    if (isset($pdo)) {
                        try {
                            $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'");
                            $unread = $stmt->fetchColumn();
                            if ($unread > 0) {
                                echo '<span class="badge-glow ml-auto inline-flex items-center justify-center min-w-[22px] h-5 px-1.5 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-pink-500 rounded-full shadow-lg">' . $unread . '</span>';
                            }
                        } catch (PDOException $e) {
                            // Silently fail
                        }
                    }
                    ?>
                    <span class="tooltip">Messages</span>
                </a>
            </li>
        </ul>

        <!-- Content Management -->
        <div class="group-header" id="contentHeader">Content</div>
        <ul class="space-y-1 px-3">
            <!-- Announcements -->
            <li>
                <a href="post.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'post.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-bullhorn w-5 text-center text-base"></i>
                    <span class="nav-text">Announcements</span>
                    <span class="tooltip">Announcements</span>
                </a>
            </li>

            <!-- Activities -->
            <li>
                <a href="activities.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'activities.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-theater-masks w-5 text-center text-base"></i>
                    <span class="nav-text">Activities</span>
                    <span class="tooltip">Activities</span>
                </a>
            </li>

            <!-- Events -->
            <li>
                <a href="cultural_event.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'cultural_event.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-calendar-alt w-5 text-center text-base"></i>
                    <span class="nav-text">Events</span>
                    <span class="tooltip">Events</span>
                </a>
            </li>

            <!-- Stories -->
            <li>
                <a href="admin_story.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'admin_story.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-book-reader w-5 text-center text-base"></i>
                    <span class="nav-text">Stories</span>
                    <span class="tooltip">Stories</span>
                </a>
            </li>
        </ul>

        <!-- Academic -->
        <div class="group-header" id="academicHeader">Academic</div>
        <ul class="space-y-1 px-3">
            <!-- Courses -->
            <li>
                <a href="course.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'course.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-book-open w-5 text-center text-base"></i>
                    <span class="nav-text">Courses</span>
                    <span class="tooltip">Courses</span>
                </a>
            </li>

            <!-- Programs -->
            <li>
                <a href="exprograms.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'exprograms.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-project-diagram w-5 text-center text-base"></i>
                    <span class="nav-text">Programs</span>
                    <span class="tooltip">Programs</span>
                </a>
            </li>

            <!-- Achievements -->
            <li>
                <a href="student_acc.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'student_acc.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-trophy w-5 text-center text-base"></i>
                    <span class="nav-text">Achievements</span>
                    <span class="tooltip">Achievements</span>
                </a>
            </li>

            <!-- Admission -->
            <li>
                <a href="admission_admin.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'admission_admin.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-user-graduate w-5 text-center text-base"></i>
                    <span class="nav-text">Admission</span>
                    <span class="tooltip">Admission</span>
                </a>
            </li>
        </ul>

        <!-- School Info -->
        <div class="group-header" id="schoolHeader">School Info</div>
        <ul class="space-y-1 px-3">
            <!-- Facilities -->
            <li>
                <a href="facilities.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'facilities.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-building w-5 text-center text-base"></i>
                    <span class="nav-text">Facilities</span>
                    <span class="tooltip">Facilities</span>
                </a>
            </li>

            <!-- Org Chart -->
            <li>
                <a href="org_chart.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'org_chart.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-sitemap w-5 text-center text-base"></i>
                    <span class="nav-text">Org Chart</span>
                    <span class="tooltip">Organization Chart</span>
                </a>
            </li>

            <!-- Community -->
            <li>
                <a href="community_involvement.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'community_involvement.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-hands-helping w-5 text-center text-base"></i>
                    <span class="nav-text">Community</span>
                    <span class="tooltip">Community Involvement</span>
                </a>
            </li>

            <!-- Feedback -->
            <li>
                <a href="feedback.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'feedback.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-comments w-5 text-center text-base"></i>
                    <span class="nav-text">Feedback</span>
                    <span class="tooltip">Feedback</span>
                </a>
            </li>
        </ul>

        <!-- Management -->
        <div class="group-header" id="mgmtHeader">Management</div>
        <ul class="space-y-1 px-3">
            <!-- Admins -->
            <li>
                <a href="administrator.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'administrator.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-user-shield w-5 text-center text-base"></i>
                    <span class="nav-text">Administrators</span>
                    <span class="tooltip">Administrators</span>
                </a>
            </li>

            <!-- Users -->
            <li>
                <a href="user_info.php"
                   class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm relative <?php if ($current_page == 'user_info.php') echo 'active'; ?>">
                    <i class="nav-icon fas fa-users-cog w-5 text-center text-base"></i>
                    <span class="nav-text">Users</span>
                    <span class="tooltip">User Management</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-white/10">
        <a href="logout.php"
           class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm hover:bg-red-500/20 group relative">
            <i class="nav-icon fas fa-sign-out-alt w-5 text-center text-base group-hover:text-red-400"></i>
            <span class="nav-text">Logout</span>
            <span class="tooltip">Logout</span>
        </a>
    </div>
</aside>

<!-- Mobile Navigation -->
<div class="md:hidden fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-blue-900 to-blue-800 shadow-xl border-b border-white/10">
    <!-- Mobile Header -->
    <div class="flex items-center justify-between px-4 py-3.5">
        <div class="flex items-center gap-3">
            <button id="mobileMenuToggle" class="text-white hover:bg-white/10 w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 active:scale-95">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="flex items-center gap-2.5">
                <div class="logo-glow w-9 h-9 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-base"></i>
                </div>
                <span class="text-white font-bold text-base">LFHS Admin</span>
            </div>
        </div>

        <!-- Unread messages indicator -->
        <?php
        if (isset($pdo)) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'");
                $unread = $stmt->fetchColumn();
                if ($unread > 0) {
                    echo '<div class="badge-glow flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full shadow-lg">';
                    echo '<i class="fas fa-envelope text-xs"></i>';
                    echo '<span>' . $unread . '</span>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                // Silently fail
            }
        }
        ?>
    </div>

    <!-- Mobile Slide-out Menu -->
    <div id="mobileMenu" class="fixed top-0 left-0 h-full w-72 gradient-bg shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out z-50">
        <!-- Mobile Menu Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-white/10 bg-black/10">
            <div class="flex items-center gap-2.5">
                <div class="logo-glow w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-bold text-sm">LFHS</span>
                    <span class="text-blue-200 text-xs">Admin Panel</span>
                </div>
            </div>
            <button id="closeMobileMenu" class="text-white hover:bg-white/10 w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300 active:scale-95">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Mobile Menu Items -->
        <nav class="custom-scrollbar h-[calc(100vh-140px)] py-4">
            <!-- Main -->
            <div class="group-header text-white/50">Main</div>
            <ul class="space-y-1 px-3">
                <li>
                    <a href="index.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'index.php') echo 'active'; ?>">
                        <i class="nav-icon fas fa-home w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="admin_contact_messages.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'admin_contact_messages.php') echo 'active'; ?>">
                        <i class="nav-icon fas fa-envelope w-5 text-center"></i>
                        <span>Messages</span>
                        <?php
                        if (isset($pdo)) {
                            try {
                                $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'");
                                $unread = $stmt->fetchColumn();
                                if ($unread > 0) {
                                    echo '<span class="badge-glow ml-auto inline-flex items-center justify-center min-w-[22px] h-5 px-1.5 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-pink-500 rounded-full">' . $unread . '</span>';
                                }
                            } catch (PDOException $e) {}
                        }
                        ?>
                    </a>
                </li>
            </ul>

            <!-- Content -->
            <div class="group-header text-white/50">Content</div>
            <ul class="space-y-1 px-3">
                <li><a href="post.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'post.php') echo 'active'; ?>"><i class="nav-icon fas fa-bullhorn w-5 text-center"></i><span>Announcements</span></a></li>
                <li><a href="activities.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'activities.php') echo 'active'; ?>"><i class="nav-icon fas fa-theater-masks w-5 text-center"></i><span>Activities</span></a></li>
                <li><a href="cultural_event.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'cultural_event.php') echo 'active'; ?>"><i class="nav-icon fas fa-calendar-alt w-5 text-center"></i><span>Events</span></a></li>
                <li><a href="admin_story.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'admin_story.php') echo 'active'; ?>"><i class="nav-icon fas fa-book-reader w-5 text-center"></i><span>Stories</span></a></li>
            </ul>

            <!-- Academic -->
            <div class="group-header text-white/50">Academic</div>
            <ul class="space-y-1 px-3">
                <li><a href="course.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'course.php') echo 'active'; ?>"><i class="nav-icon fas fa-book-open w-5 text-center"></i><span>Courses</span></a></li>
                <li><a href="exprograms.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'exprograms.php') echo 'active'; ?>"><i class="nav-icon fas fa-project-diagram w-5 text-center"></i><span>Programs</span></a></li>
                <li><a href="student_acc.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'student_acc.php') echo 'active'; ?>"><i class="nav-icon fas fa-trophy w-5 text-center"></i><span>Achievements</span></a></li>
                <li><a href="admission_admin.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'admission_admin.php') echo 'active'; ?>"><i class="nav-icon fas fa-user-graduate w-5 text-center"></i><span>Admission</span></a></li>
            </ul>

            <!-- School Info -->
            <div class="group-header text-white/50">School Info</div>
            <ul class="space-y-1 px-3">
                <li><a href="facilities.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'facilities.php') echo 'active'; ?>"><i class="nav-icon fas fa-building w-5 text-center"></i><span>Facilities</span></a></li>
                <li><a href="org_chart.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'org_chart.php') echo 'active'; ?>"><i class="nav-icon fas fa-sitemap w-5 text-center"></i><span>Org Chart</span></a></li>
                <li><a href="community_involvement.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'community_involvement.php') echo 'active'; ?>"><i class="nav-icon fas fa-hands-helping w-5 text-center"></i><span>Community</span></a></li>
                <li><a href="feedback.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'feedback.php') echo 'active'; ?>"><i class="nav-icon fas fa-comments w-5 text-center"></i><span>Feedback</span></a></li>
            </ul>

            <!-- Management -->
            <div class="group-header text-white/50">Management</div>
            <ul class="space-y-1 px-3">
                <li><a href="administrator.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'administrator.php') echo 'active'; ?>"><i class="nav-icon fas fa-user-shield w-5 text-center"></i><span>Administrators</span></a></li>
                <li><a href="user_info.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm <?php if ($current_page == 'user_info.php') echo 'active'; ?>"><i class="nav-icon fas fa-users-cog w-5 text-center"></i><span>Users</span></a></li>
            </ul>
        </nav>

        <!-- Mobile Logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-black/10">
            <a href="logout.php" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl text-white/90 text-sm hover:bg-red-500/20 group">
                <i class="nav-icon fas fa-sign-out-alt w-5 text-center group-hover:text-red-400"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40 transition-opacity duration-300"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle (Desktop)
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('toggleIcon');
    const logoText = document.getElementById('logoText');
    const navTexts = document.querySelectorAll('.nav-text');
    const groupHeaders = document.querySelectorAll('.group-header');

    let isCollapsed = false;

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                toggleIcon.classList.add('rotate-180');
                logoText.style.opacity = '0';
                navTexts.forEach(text => text.style.opacity = '0');
                groupHeaders.forEach(header => header.style.opacity = '0');

                setTimeout(() => {
                    logoText.style.display = 'none';
                    navTexts.forEach(text => text.style.display = 'none');
                    groupHeaders.forEach(header => header.style.display = 'none');
                }, 150);
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                toggleIcon.classList.remove('rotate-180');

                logoText.style.display = 'flex';
                navTexts.forEach(text => text.style.display = 'block');
                groupHeaders.forEach(header => header.style.display = 'block');

                setTimeout(() => {
                    logoText.style.opacity = '1';
                    navTexts.forEach(text => text.style.opacity = '1');
                    groupHeaders.forEach(header => header.style.opacity = '1');
                }, 50);
            }
        });
    }

    // Mobile Menu
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

    function openMobileMenu() {
        mobileMenu.style.transform = 'translateX(0)';
        mobileMenu.classList.add('mobile-menu-enter');
        mobileMenuOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenuFunc() {
        mobileMenu.style.transform = 'translateX(-100%)';
        mobileMenuOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', openMobileMenu);
    }

    if (closeMobileMenu) {
        closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
    }

    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenuFunc);
    }

    // Close mobile menu when clicking a link
    const mobileLinks = mobileMenu?.querySelectorAll('a');
    mobileLinks?.forEach(link => {
        link.addEventListener('click', closeMobileMenuFunc);
    });

    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu.style.transform === 'translateX(0px)') {
            closeMobileMenuFunc();
        }
    });
});
</script>

</body>
</html>
