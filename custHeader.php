<?php // Prevents "headers already sent" when included ?>
<header class="nav-container">
    <div class="logo">
        <a href="bronze_member.php">Miraé</a>
    </div>

    <nav class="nav-menu">
        <a href="bronze_member.php">Home</a>
        <a href="bronzeService.php">Services</a>
        <a href="bronzeAppointment.php">Appointment</a>
    </nav>

    <div class="nav-icons">
        <div class="nav-dropdown">
            <button class="nav-icon dropdown-btn" onclick="toggleDropdown(event)">👤</button>
            <div class="dropdown-content" id="userDropdown">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleDropdown(event) {
        event.stopPropagation();
        document.getElementById('userDropdown').classList.toggle('show');
    }

    // Optional: close dropdown when clicking outside
    document.addEventListener('click', function () {
        document.getElementById('userDropdown')?.classList.remove('show');
    });
</script>
