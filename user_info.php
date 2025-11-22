<?php
session_start();
if(($_SESSION["user"]["access_rights"] !== 'Admin')){
    header ('Location: login.php');
}

// Database connection for navigation badges
require_once 'php/connection.php';
try {
    $pdo = new PDO(DSN, DB_USR, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Silently fail
}

// Set current page
$current_page = basename($_SERVER['PHP_SELF']);

// Include navigation
include 'design/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - LFHS Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #3b82f6, #2563eb);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
        }

        /* Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .animate-slide-down {
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-slide-up {
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-scale-in {
            animation: scaleIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* Hover effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Gradient backgrounds */
        .gradient-blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .gradient-purple {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
        }

        .gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        /* Table enhancements */
        .table-row-hover {
            transition: all 0.2s ease;
        }

        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 197, 253, 0.05) 100%);
            transform: scale(1.01);
        }

        /* Button pulse */
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);
            }
            100% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
        }

        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Badge animations */
        .badge-animate {
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: loading 1.5s ease-in-out infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
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
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 min-h-screen">

    <!-- Main Content - Adjust for sidebar -->
    <div class="md:ml-64 transition-all duration-300 ease-in-out" id="mainContent">

        <!-- Modern Header -->
        <header class="sticky top-0 z-40 glass-card shadow-lg border-b border-white/20 animate-slide-down">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Page Title -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg">
                            <i class="fas fa-users-cog text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                                User Management
                            </h1>
                            <p class="text-sm text-gray-500 mt-0.5 flex items-center gap-2">
                                <i class="fas fa-circle text-green-500 text-xs animate-pulse"></i>
                                Manage system users and access rights
                            </p>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="relative">
                        <button id="profileBtn" class="group flex items-center space-x-3 rounded-xl px-4 py-2 hover:bg-white/50 transition-all duration-300 border border-transparent hover:border-blue-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-900"><?php echo $_SESSION["user"]["full_name"]; ?></p>
                                <p class="text-xs text-blue-600 font-medium"><?php echo $_SESSION["user"]["access_rights"]; ?></p>
                            </div>
                            <div class="relative">
                                <img src="uploads_profile/<?php echo $_SESSION['user']['profile_image'] ?? 'default.png'; ?>"
                                     class="h-11 w-11 rounded-xl border-2 border-blue-500 object-cover shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:scale-105"
                                     alt="Profile">
                                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs group-hover:text-blue-600 transition-colors"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileDropdown" class="absolute right-0 mt-3 w-56 hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5 z-50 animate-scale-in border border-gray-100">
                            <div class="p-2">
                                <a href="#" id="editProfileBtn" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 rounded-lg transition-all group">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                    <span class="font-medium">Edit Profile</span>
                                </a>
                                <div class="my-2 border-t border-gray-100"></div>
                                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-all group">
                                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    <span class="font-medium">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8 animate-fade-in">

            <!-- Stats Cards -->
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Users -->
                <div class="group hover-lift rounded-2xl gradient-blue p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-white/80">Total Users</p>
                                <p class="mt-2 text-4xl font-bold" id="totalUsers">0</p>
                            </div>
                            <div class="rounded-2xl bg-white/20 p-4 group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-users text-3xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-white/80">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>All registered users</span>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="group hover-lift rounded-2xl gradient-green p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-white/80">Active Users</p>
                                <p class="mt-2 text-4xl font-bold" id="activeUsers">0</p>
                            </div>
                            <div class="rounded-2xl bg-white/20 p-4 group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-user-check text-3xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-white/80">
                            <i class="fas fa-circle text-xs mr-2 animate-pulse"></i>
                            <span>Currently active</span>
                        </div>
                    </div>
                </div>

                <!-- Admin Users -->
                <div class="group hover-lift rounded-2xl gradient-purple p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-white/80">Administrators</p>
                                <p class="mt-2 text-4xl font-bold" id="adminUsers">0</p>
                            </div>
                            <div class="rounded-2xl bg-white/20 p-4 group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-user-shield text-3xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-white/80">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Admin access</span>
                        </div>
                    </div>
                </div>

                <!-- Regular Users -->
                <div class="group hover-lift rounded-2xl gradient-orange p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-white/80">Regular Users</p>
                                <p class="mt-2 text-4xl font-bold" id="regularUsers">0</p>
                            </div>
                            <div class="rounded-2xl bg-white/20 p-4 group-hover:bg-white/30 transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-user text-3xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-white/80">
                            <i class="fas fa-users mr-2"></i>
                            <span>Standard access</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="mb-6 rounded-2xl glass-card p-6 shadow-lg border border-white/20 animate-slide-up">
                <div class="grid grid-cols-1 gap-4 lg:gap-6 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <label class="mb-3 block text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-search text-blue-500"></i>
                            Search Users
                        </label>
                        <div class="relative group">
                            <input type="text"
                                   id="keyword"
                                   placeholder="Search by name, email, or access rights..."
                                   class="w-full rounded-xl border-2 border-gray-200 py-3.5 pl-12 pr-4 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 bg-white/50 group-hover:bg-white">
                            <i class="fas fa-search absolute left-4 top-4 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                        </div>
                    </div>

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i class="fas fa-list text-blue-500"></i>
                            Records Per Page
                        </label>
                        <select id="perpage" class="w-full rounded-xl border-2 border-gray-200 py-3.5 px-4 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 bg-white/50 hover:bg-white cursor-pointer">
                            <option value="10">10 records</option>
                            <option value="25">25 records</option>
                            <option value="50">50 records</option>
                            <option value="100">100 records</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Users Table Container -->
            <div id="user_control" class="animate-slide-up">
                <!-- Loading State -->
                <div class="rounded-2xl glass-card shadow-lg border border-white/20 p-12">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-4">
                            <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Loading Users</h3>
                        <p class="text-gray-500">Please wait while we fetch the data...</p>
                        <div class="mt-6 flex justify-center">
                            <div class="skeleton h-2 w-64 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modern Footer -->
        <footer class="mt-12 glass-card border-t border-white/20 px-6 py-8 shadow-inner">
            <div class="text-center space-y-2">
                <div class="flex items-center justify-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-copyright"></i>
                        2024 LFHS. All Rights Reserved
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-blue-600"></i>
                        School Information Management System
                    </span>
                </div>
                <p class="text-xs text-gray-400">Version 2.0 - Built with modern technology</p>
            </div>
        </footer>
    </div>

    <!-- Modern Modal -->
    <div id="customModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-all duration-300">
        <div class="animate-scale-in w-full max-w-3xl rounded-2xl glass-card shadow-2xl border border-white/20 overflow-hidden">
            <div class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                <h3 class="text-xl font-bold text-white flex items-center gap-3" id="modalTitle">
                    <i class="fas fa-user-edit"></i>
                    Modal Title
                </h3>
                <button id="closeModal" class="text-white/80 hover:text-white transition-all duration-300 hover:bg-white/20 rounded-lg w-9 h-9 flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="max-h-[75vh] overflow-y-auto p-6 bg-gradient-to-br from-gray-50 to-blue-50" id="modalBody">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Adjust content margin based on sidebar state
            function adjustContentMargin() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');

                if (sidebar && mainContent) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'class') {
                                if (sidebar.classList.contains('sidebar-collapsed')) {
                                    mainContent.classList.remove('md:ml-64');
                                    mainContent.classList.add('md:ml-20');
                                } else {
                                    mainContent.classList.remove('md:ml-20');
                                    mainContent.classList.add('md:ml-64');
                                }
                            }
                        });
                    });

                    observer.observe(sidebar, { attributes: true });
                }
            }

            adjustContentMargin();

            // Profile dropdown with smooth animation
            $('#profileBtn').click(function(e) {
                e.stopPropagation();
                $('#profileDropdown').toggleClass('hidden');
            });

            $(document).click(function() {
                if (!$('#profileDropdown').hasClass('hidden')) {
                    $('#profileDropdown').addClass('hidden');
                }
            });

            $('#profileDropdown').click(function(e) {
                e.stopPropagation();
            });

            // Edit profile
            $('#editProfileBtn').click(function(e) {
                e.preventDefault();
                $('#modalTitle').html('<i class="fas fa-user-edit mr-2"></i>Edit Your Profile');
                $('#modalBody').load('php/edit_profile.php');
                $('#customModal').removeClass('hidden').addClass('flex');
            });

            // Close modal
            $('#closeModal').click(function() {
                $('#customModal').addClass('hidden').removeClass('flex');
            });

            // Close modal on outside click
            $('#customModal').click(function(e) {
                if (e.target === this) {
                    $(this).addClass('hidden').removeClass('flex');
                }
            });

            // ESC key to close modal
            $(document).keydown(function(e) {
                if (e.key === 'Escape' && !$('#customModal').hasClass('hidden')) {
                    $('#customModal').addClass('hidden').removeClass('flex');
                }
            });

            // Load users function
            function load(keyword, perpage, page) {
                $.ajax({
                    type: 'post',
                    url: 'php/read_user.php',
                    data: {
                        keyword: keyword,
                        perpage: perpage,
                        page: page
                    },
                    beforeSend: function() {
                        $("#user_control").html(`
                            <div class="rounded-2xl glass-card shadow-lg border border-white/20 p-12">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-4 animate-pulse">
                                        <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Loading Users</h3>
                                    <p class="text-gray-500">Fetching latest data...</p>
                                    <div class="mt-6 flex justify-center gap-2">
                                        <div class="skeleton h-2 w-16 rounded-full"></div>
                                        <div class="skeleton h-2 w-24 rounded-full"></div>
                                        <div class="skeleton h-2 w-20 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                }).done(function(data) {
                    $("#user_control").fadeOut(200, function() {
                        $(this).html(data).fadeIn(400);
                    });
                    updateStats();
                }).fail(function() {
                    $("#user_control").html(`
                        <div class="rounded-2xl glass-card shadow-lg border border-white/20 p-12">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 mb-4">
                                    <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Failed to Load</h3>
                                <p class="text-gray-500 mb-6">Unable to fetch user data. Please try again.</p>
                                <button onclick="location.reload()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                    <i class="fas fa-redo"></i>
                                    Retry
                                </button>
                            </div>
                        </div>
                    `);
                });
            }

            // Update statistics with animation
            function updateStats() {
                const total = parseInt($('#cnt').text()) || 0;

                // Animate counter
                $({countNum: $('#totalUsers').text()}).animate({countNum: total}, {
                    duration: 1000,
                    easing: 'swing',
                    step: function() {
                        $('#totalUsers').text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $('#totalUsers').text(total);
                    }
                });
            }

            // Initial load
            load($("#keyword").val(), $("#perpage").val(), 1);

            // Search with debounce
            let searchTimeout;
            $(document).on('keyup', '#keyword', function() {
                clearTimeout(searchTimeout);
                const $this = $(this);
                searchTimeout = setTimeout(() => {
                    load($this.val(), $("#perpage").val(), 1);
                }, 500);
            });

            // Change per page
            $(document).on('change', '#perpage', function() {
                load($("#keyword").val(), $(this).val(), 1);
            });

            // Pagination
            $(document).on('click', '.page', function() {
                load($("#keyword").val(), $("#perpage").val(), $(this).text());
                $('html, body').animate({ scrollTop: 0 }, 600);
            });

            // Edit user
            $(document).on('click', '.edit_user', function() {
                const userId = $(this).attr('id');
                $.ajax({
                    type: 'post',
                    url: 'php/edit_user.php',
                    data: { id: userId },
                    beforeSend: function() {
                        $('#modalBody').html(`
                            <div class="text-center py-12">
                                <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
                                <p class="text-gray-600">Loading user data...</p>
                            </div>
                        `);
                    }
                }).done(function(data) {
                    $('#modalTitle').html('<i class="fas fa-user-edit mr-2"></i>Edit User Information');
                    $('#modalBody').html(data);
                    $('#customModal').removeClass('hidden').addClass('flex');
                });
            });

            // Delete user with modern SweetAlert
            $(document).on('click', '.del_user', function() {
                const userId = $(this).attr('id');
                Swal.fire({
                    title: 'Are you absolutely sure?',
                    text: "This action cannot be undone! The user will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-trash mr-2"></i>Yes, delete permanently',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 font-semibold shadow-lg hover:shadow-xl',
                        cancelButton: 'rounded-xl px-6 font-semibold'
                    },
                    buttonsStyling: true,
                    showClass: {
                        popup: 'animate-scale-in'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            url: 'php/delete_user.php',
                            data: { id: userId }
                        }).done(function(data) {
                            Swal.fire({
                                title: 'Deleted Successfully!',
                                text: 'The user has been permanently removed from the system.',
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                confirmButtonText: '<i class="fas fa-check mr-2"></i>Got it',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-xl px-6 font-semibold shadow-lg'
                                },
                                showClass: {
                                    popup: 'animate-scale-in'
                                }
                            }).then(() => {
                                load($("#keyword").val(), $("#perpage").val(), 1);
                            });
                        }).fail(function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete user. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#3b82f6',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-xl px-6 font-semibold'
                                }
                            });
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
