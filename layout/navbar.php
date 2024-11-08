<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <div class="topbar-divider d-none d-sm-block"></div>
        <form method="POST">
            <button class="logout btn btn-danger btn-sm" name="logout">
                <i class="fa fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </ul>

</nav>
<!-- End of Topbar -->


<?php
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
}

?>