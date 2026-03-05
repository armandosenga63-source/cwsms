<nav class="glass-navbar">
    <div class="nav-left">
        <h5 class="mb-0 fw-bold text-dark">
            <?php echo isset($page_title) ? $page_title : "CWSMS Dashboard"; ?>
        </h5>
    </div>

    <div class="nav-right d-flex align-items-center gap-3">
        <div class="d-none d-md-block position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" placeholder="Search..." class="search-bar ps-5">
        </div>
        
        <div class="dropdown">
            <button class="user-btn dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:24px; height:24px; font-size:12px; font-weight:bold;">
                    <?= isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'U' ?>
                </div>
                <span><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'User' ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3">
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger" href="/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a></li>
            </ul>
        </div>
    </div>
</nav>