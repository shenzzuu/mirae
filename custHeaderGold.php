<?php // Ensure nothing is sent before this tag ?>
<header class="nav-container">
    <div class="logo">
        <a href="gold.php">Miraé</a>
    </div>

    <nav class="nav-menu">
        <a href="gold.php">Home</a>
        <a href="gold_services.php">Services</a>
        <a href="goldAppointment.php">Appointment</a>
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
</script>
