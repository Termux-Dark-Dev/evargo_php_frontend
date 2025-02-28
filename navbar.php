<?php
if (!isset($_SESSION['access_token']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
// Get current page filename for active tab highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
/* Professional Navbar Styling */
:root {
    --primary: #A945BB;
    --primary-light: #C76AD4;
    --primary-dark: #8A35A3;
    --accent: #FFD30F;
    --accent-light: #FFE04D;
    --dark: #393939;
    --dark-light: #5A5A5A;
    --white: #FFFFFF;
    --gray-bg: #F8F9FA;
    --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.main-navbar {
    background: var(--white);
    padding: 0.6rem 0;
    box-shadow: var(--shadow);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar-brand {
    display: flex;
    align-items: center;
    padding: 0;
}

.navbar-brand img {
    height: 32px;
    margin-right: 0.75rem;
    transition: transform 0.3s ease;
}

.navbar-brand:hover img {
    transform: scale(1.05);
}

.navbar-brand-text {
    color: var(--primary);
    font-weight: 700;
    font-size: 1.3rem;
    margin: 0;
    letter-spacing: 0.3px;
}

.navbar-nav {
    margin-left: 1rem;
}

.navbar-nav .nav-item {
    margin: 0 0.25rem;
}

.navbar-nav .nav-link {
    color: var(--dark);
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
}

.navbar-nav .nav-link:hover {
    color: var(--primary);
}

.navbar-nav .nav-link.active {
    color: var(--primary);
    background-color: rgba(169, 69, 187, 0.08);
}

.navbar-nav .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: var(--primary);
    border-radius: 1px;
}

.navbar-toggler {
    border: none;
    padding: 0.4rem;
    border-radius: 4px;
    color: var(--primary);
}

.navbar-toggler:focus {
    box-shadow: none;
}

.user-section {
    display: flex;
    align-items: center;
}

.user-profile {
    display: flex;
    align-items: center;
    color: var(--dark);
    padding: 0.4rem 0.75rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.user-profile:hover {
    background-color: rgba(169, 69, 187, 0.08);
    color: var(--primary);
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
}

.role-badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 50px;
    background: var(--accent);
    color: var(--dark);
    font-weight: 500;
    font-size: 0.7rem;
    margin-right: 12px;
    white-space: nowrap;
}

/* Simple Dropdown Menu */
.user-dropdown {
    position: relative;
}

.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    background-color: var(--white);
    border-radius: 4px;
    box-shadow: var(--shadow);
    padding: 0.5rem 0;
    min-width: 160px;
    display: none; /* Changed from visibility: hidden for more reliable toggling */
    z-index: 1001;
    border: 1px solid rgba(0,0,0,0.05);
    margin-top: 5px;
}

.user-dropdown.show .dropdown-menu {
    display: block; /* Changed from visibility: visible for more reliable toggling */
}

/* Enhanced dropdown item styling for better visibility */
.dropdown-item {
    padding: 0.6rem 1rem;
    font-weight: 500;
    color: var(--dark);
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
    text-decoration: none;
    background-color: transparent;
}

.dropdown-item i {
    margin-right: 0.5rem;
    font-size: 1rem;
    color: var(--primary);
}

.dropdown-item:hover {
    background-color: rgba(169, 69, 187, 0.08);
    color: var(--primary);
}

/* SVG Menu Icon */
.menu-icon {
    width: 24px;
    height: 24px;
}

.menu-icon line {
    stroke: var(--primary);
    stroke-width: 2;
    transition: all 0.3s ease;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .navbar-collapse {
        background: var(--white);
        padding: 1rem;
        border-radius: 8px;
        margin-top: 0.75rem;
        box-shadow: var(--shadow);
    }
    
    .navbar-nav .nav-item {
        margin: 0.25rem 0;
    }
    
    .user-section {
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
}
</style>

<!-- Professional Navbar Component -->
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="container">
        <!-- Logo & Branding -->
        <a class="navbar-brand" href="dashboard.php">
            <img src="assets/png/logo.png" alt="EVargo Logo">
            <h1 class="navbar-brand-text">Evargo Services</h1>
        </a>

        <!-- Navbar Toggler for Mobile with SVG Icon -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <svg class="menu-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto" id="navLinks">
                <!-- Navigation links will be populated dynamically -->
            </ul>

            <!-- User Profile & Role Badge -->
            <div class="d-flex align-items-center user-section">
                <div id="roleBadge" class="role-badge d-none"></div>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-profile" id="userProfileToggle">
                        <div class="user-avatar" id="userAvatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="user-info">
                            <span class="user-name" id="userName">Loading...</span>
                        </div>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </div>
                    <div class="dropdown-menu" style="border: 1px solid #ddd; box-shadow: 0 2px 10px rgba(0,0,0,0.15);">
                        <a class="dropdown-item logout-btn" href="javascript:void(0);" onclick="logout()" style="padding: 10px 15px; color: #e74c3c; display: flex; align-items: center; font-weight: 600;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px; color: #e74c3c;">
                                <path d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                <path d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const role = "<?php echo $role; ?>";
    console.log("Role:", role);
    
    const currentFile = "<?php echo $current_page; ?>";
    const roleBadge = document.getElementById('roleBadge');
    const navLinks = document.getElementById('navLinks');
    const userName = document.getElementById('userName');
    const userDropdown = document.getElementById('userDropdown');
    const userProfileToggle = document.getElementById('userProfileToggle');
    const userAvatar = document.getElementById('userAvatar');

    // Display role badge
    roleBadge.textContent = role;
    roleBadge.classList.remove('d-none');

    // Define navigation based on roles with file mappings
    const routes = {
        "Retailer": [
            { name: "Dashboard", url: "dashboard.php", key: "dashboard", icon: "bi-speedometer2" },
            { name: "View Projects", url: "view_projects.php", key: "projects", icon: "bi-kanban" },
            { name: "Create Project", url: "add_project.php", key: "add-project", icon: "bi-plus-circle" },
            { name: "Profile", url: "profile.php", key: "profile", icon: "bi-person" }
        ],
        "ChannelPartner": [
            { name: "Dashboard", url: "dashboard.php", key: "dashboard", icon: "bi-speedometer2" },
            { name: "Create Agent", url: "create_agent.php", key: "create-agent", icon: "bi-person-plus" },
            { name: "View Agents", url: "view_agents.php", key: "view-agents", icon: "bi-people" },
            { name: "Profile", url: "profile.php", key: "profile", icon: "bi-person" }
        ],
        "MasterDistributor": [
            { name: "Dashboard", url: "dashboard.php", key: "dashboard", icon: "bi-speedometer2" },
            { name: "Create Agent", url: "create_agent.php", key: "create-agent", icon: "bi-person-plus" },
            { name: "View Agents", url: "view_agents.php", key: "view-agents", icon: "bi-people" },
            { name: "Profile", url: "profile.php", key: "profile", icon: "bi-person" }
        ],
        "Distributor": [
            { name: "Dashboard", url: "dashboard.php", key: "dashboard", icon: "bi-speedometer2" },
            { name: "Create Agent", url: "create_agent.php", key: "create-agent", icon: "bi-person-plus" },
            { name: "View Agents", url: "view_agents.php", key: "view-agents", icon: "bi-people" },
            { name: "Profile", url: "profile.php", key: "profile", icon: "bi-person" }
        ]
    };

    // Load dynamic nav items based on role
    let navLinksHTML = "";
    const routesForRole = routes[role] || [];
    
    routesForRole.forEach(link => {
        // Check if this link matches the current page
        const isActive = link.url === currentFile;
        
        navLinksHTML += `<li class="nav-item">
            <a class="nav-link ${isActive ? 'active' : ''}" href="${link.url}" data-page="${link.key}">
                <i class="bi ${link.icon} me-1"></i> ${link.name}
            </a>
        </li>`;
    });
    
    navLinks.innerHTML = navLinksHTML;

    // Handle user dropdown toggle
    userProfileToggle.addEventListener('click', function() {
        userDropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!userDropdown.contains(event.target)) {
            userDropdown.classList.remove('show');
        }
    });

    // Fetch User Name from API
    axios.get('https://backend.evargo.solarportal.in/evargo/api/v1/agent/dashboard', {
        headers: { 'Authorization': 'Bearer ' + sessionStorage.getItem('access_token') }
    })
    .then(response => {
        if (response.data.status === "success") {
            const name = response.data.data.name;
            userName.textContent = name;
            
            // Set first letter of name as avatar text if available
            if (name && name.length > 0) {
                userAvatar.innerHTML = name.charAt(0).toUpperCase();
            }
        } else {
            userName.textContent = "User";
        }
    })
    .catch(error => {
        console.error("Error fetching user data:", error);
        userName.textContent = "User";
    });

    // Make username clickable to show logout options - improved version
    const makeUserNameClickable = function() {
        // Make sure the element exists and is loaded
        if (userName) {
            userName.style.cursor = "pointer";
            
            // Direct approach to force dropdown to work
            userName.onclick = function(event) {
                event.stopPropagation();
                
                // Get the dropdown menu element
                const dropdownMenu = userDropdown.querySelector('.dropdown-menu');
                
                // Direct DOM manipulation for visibility
                if (dropdownMenu.style.display === 'block') {
                    dropdownMenu.style.display = 'none';
                    userDropdown.classList.remove('show');
                } else {
                    dropdownMenu.style.display = 'block';
                    userDropdown.classList.add('show');
                }
                
                console.log("Username clicked, dropdown forced: ", dropdownMenu.style.display);
                return false;
            };
            
            console.log("Username click event attached with direct DOM manipulation");
        } else {
            console.error("Username element not found");
        }
    };
    
    // Call after a short delay to ensure DOM is fully processed
    setTimeout(makeUserNameClickable, 500);

    // Logout function
    window.logout = function() {
        // Clear session storage
        sessionStorage.removeItem('access_token');
        // Redirect to login page
        window.location.href = 'index.php';
    };
});
</script>