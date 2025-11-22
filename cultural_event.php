<?php
session_start();
if(!isset($_SESSION['user'])){ header('Location: login.php'); exit(); }
$current_page = basename($_SERVER['PHP_SELF']);
require_once 'navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cultural Events - LFHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .gradient-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-green { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .gradient-purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .gradient-orange { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .hover-lift { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-in { animation: slideIn 0.3s ease-out; }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .animate-scale-in { animation: scaleIn 0.2s ease-out; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="transition-all duration-300 md:ml-64" id="mainContent">
        <header class="sticky top-0 z-40 glass-card shadow-lg border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent flex items-center gap-3">
                        <i class="fas fa-masks-theater text-blue-600"></i>
                        Cultural Events
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Manage school announcements and posts</p>
                </div>
                <div class="relative">
                    <button id="profileBtn" class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100 transition-all">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-700"><?php echo htmlspecialchars($_SESSION["user"]["full_name"] ?? 'User'); ?></p>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($_SESSION["user"]["access_rights"] ?? 'User'); ?></p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold">
                            <?php echo strtoupper(substr($_SESSION["user"]["full_name"] ?? 'U', 0, 1)); ?>
                        </div>
                    </button>
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border hidden animate-slide-in">
                        <a href="profile.php" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl"><i class="fas fa-user mr-2"></i>My Profile</a>
                        <a href="logout.php" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="hover-lift rounded-2xl p-6 text-white shadow-xl gradient-blue">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium opacity-90 mb-1">Total Events</p><p class="text-4xl font-bold" id="totalEvents">0</p></div>
                        <div class="bg-white/20 p-4 rounded-xl"><i class="fas fa-masks-theater text-3xl"></i></div>
                    </div>
                </div>
                <div class="hover-lift rounded-2xl p-6 text-white shadow-xl gradient-green">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium opacity-90 mb-1">Published</p><p class="text-4xl font-bold" id="publishedPosts">0</p></div>
                        <div class="bg-white/20 p-4 rounded-xl"><i class="fas fa-check-circle text-3xl"></i></div>
                    </div>
                </div>
                <div class="hover-lift rounded-2xl p-6 text-white shadow-xl gradient-purple">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium opacity-90 mb-1">Categories</p><p class="text-4xl font-bold" id="categories">0</p></div>
                        <div class="bg-white/20 p-4 rounded-xl"><i class="fas fa-layer-group text-3xl"></i></div>
                    </div>
                </div>
                <div class="hover-lift rounded-2xl p-6 text-white shadow-xl gradient-orange">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium opacity-90 mb-1">This Month</p><p class="text-4xl font-bold" id="monthPosts">0</p></div>
                        <div class="bg-white/20 p-4 rounded-xl"><i class="fas fa-clock text-3xl"></i></div>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-2xl p-6 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-search text-gray-400 mr-2"></i>Search</label>
                        <input type="text" id="keyword" placeholder="Search posts..." class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-filter text-gray-400 mr-2"></i>Per Page</label>
                        <select id="perpage" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none transition-all">
                            <option value="50">50</option><option value="100">100</option><option value="500">500</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-calendar text-gray-400 mr-2"></i>Date</label>
                        <input type="text" id="date_filter" placeholder="YYYY-MM-DD" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                    <?php if ($_SESSION["user"]["access_rights"] == 'Admin') { ?>
                    <div class="md:col-span-2 flex items-end">
                        <button id="add_cultural" class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition-all shadow-lg"><i class="fas fa-plus mr-2"></i>Add Post</button>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
                <div id="cultural_page">
                    <div class="flex items-center justify-center py-20">
                        <div class="text-center"><i class="fas fa-spinner fa-spin text-5xl text-blue-500 mb-4"></i><p class="text-gray-600 font-medium">Loading...</p></div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="mt-8 py-6 px-6 glass-card border-t border-gray-200">
            <div class="text-center text-sm text-gray-600"><p>&copy; <?php echo date('Y'); ?> Little Flower High School. All rights reserved.</p></div>
        </footer>
    </div>
    <div id="customModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl animate-scale-in">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl">
                <h3 class="text-xl font-bold text-white" id="modalTitle">Modal</h3>
                <button id="closeModal" class="text-white hover:bg-white/20 rounded-lg p-2"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto" id="modalBody"></div>
        </div>
    </div>
    <script>
    $(function() {
        const sidebar = document.getElementById('sidebar'), mainContent = document.getElementById('mainContent');
        function adjustContentMargin() { if (sidebar && sidebar.classList.contains('sidebar-collapsed')) { mainContent.classList.remove('md:ml-64'); mainContent.classList.add('md:ml-20'); } else { mainContent.classList.remove('md:ml-20'); mainContent.classList.add('md:ml-64'); } }
        adjustContentMargin(); if (sidebar) { new MutationObserver(adjustContentMargin).observe(sidebar, { attributes: true, attributeFilter: ['class'] }); }
        $('#profileBtn').click(function(e) { e.stopPropagation(); $('#profileDropdown').toggleClass('hidden'); });
        $(document).click(function() { $('#profileDropdown').addClass('hidden'); });
        $("#date_filter").datepicker({ format: 'yyyy-mm-dd', autoclose: true });
        function load(keyword, perpage, page, date_filter) {
            $.ajax({ type: 'post', url: 'php/read_cultural.php', data: { keyword, perpage, page, date_filter }, beforeSend: () => $("#cultural_page").html('<div class="flex items-center justify-center py-20"><div class="text-center"><i class="fas fa-spinner fa-spin text-5xl text-blue-500 mb-4"></i><p class="text-gray-600">Loading...</p></div></div>')
            }).done(function(data) { $("#cultural_page").html(data); var total = parseInt($('#cnt').text()) || 0; $({countNum: 0}).animate({countNum: total}, { duration: 1000, step: function() { $('#totalEvents').text(Math.floor(this.countNum)); }}); });
        }
        load($("#keyword").val(), $("#perpage").val(), 1, $("#date_filter").val());
        let timeout; $(document).on('keyup', '#keyword', function() { clearTimeout(timeout); timeout = setTimeout(() => load($(this).val(), $("#perpage").val(), 1, $("#date_filter").val()), 500); });
        $(document).on('change', '#perpage', function() { load($("#keyword").val(), $(this).val(), 1, $("#date_filter").val()); });
        $(document).on('change', '#date_filter', function() { load($("#keyword").val(), $("#perpage").val(), 1, $(this).val()); });
        $(document).on('click', '.page-link', function(e) { e.preventDefault(); const page = $(this).data('page'); if (page) { load($("#keyword").val(), $("#perpage").val(), page, $("#date_filter").val()); $('html, body').animate({ scrollTop: 0 }, 'smooth'); } });
        $(document).on('click', '#add_cultural', function() { $("#modalTitle").html('<i class="fas fa-plus mr-2"></i>Add Post'); $("#modalBody").load('php/add_cultural.php'); $("#customModal").removeClass('hidden').addClass('flex'); });
        $('#closeModal, #customModal').click(function(e) { if (e.target === this) { $('#customModal').addClass('hidden').removeClass('flex'); } });
    });
    </script>
</body>
</html>
