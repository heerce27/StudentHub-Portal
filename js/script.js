document.addEventListener("DOMContentLoaded", function () {

    const registerForm=document.getElementById("registerForm");
    if(registerForm){
        registerForm.addEventListener("submit", function (event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmPassword").value;
        if (password !== confirmPassword) {
            event.preventDefault();
            alert("Passwords do not match.");
        } } );

    } 
    // Side bar logic
    const sidebar = document.getElementById("sidebar");
    const menuButton = document.getElementById("menuButton");
    const overlay = document.getElementById("overlay");

    // Stop if this page doesn't contain the sidebar
    if (sidebar && menuButton) {

    // RESTORE SIDEBAR AFTER PAGE CHANGE

    const sidebarState = localStorage.getItem("studentHubSidebar");
    if (sidebarState === "open") {
        sidebar.classList.add("active");
        if (overlay) {
            overlay.classList.add("active");
        }
    }
    // ==========================================
    // OPEN / CLOSE WITH MENU BUTTON
    // ==========================================

    menuButton.addEventListener("click", function (event) {
        event.stopPropagation();

        sidebar.classList.toggle("active");
        if (overlay) {
            overlay.classList.toggle("active");
        }

        // Remember current state
        if (sidebar.classList.contains("active")) {
            document.documentElement.classList.add("sidebar-should-open");
            localStorage.setItem("studentHubSidebar", "open");
    } else {
            document.documentElement.classList.remove("sidebar-should-open");
            localStorage.setItem("studentHubSidebar", "closed");
        }
    });
    // ==========================================
    // CLICK OUTSIDE → CLOSE SIDEBAR
    // ==========================================
    if (overlay) {
    overlay.addEventListener("click", function () {

        sidebar.classList.remove("active");
        overlay.classList.remove("active");

        // IMPORTANT: remove the class that was forcing it open
        document.documentElement.classList.remove("sidebar-should-open");

        localStorage.setItem("studentHubSidebar", "closed");

    });
}
    // ==========================================
// HIGHLIGHT CURRENT SIDEBAR PAGE
// ==========================================

const currentPage = window.location.pathname.split("/").pop();
const sidebarLinks = document.querySelectorAll(".sidebar-links a");
sidebarLinks.forEach(function (link) {
    const linkPage = link.getAttribute("href").split("/").pop();
    if (linkPage === currentPage) {
        link.classList.add("active");
    }
});
}

const loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
        const password = document.getElementById("LoginPassword").value;
        const studentId = document.getElementById("studentid").value;
        if(!studentId || !password){    
        event.preventDefault();
        alert("Please fill in both Student ID and Password.");
        }
    });
}

// ==========================================
// DARK MODE
// ==========================================

const themeToggle = document.getElementById("themeToggle");

if (themeToggle) {

    // Restore saved theme
    const savedTheme = localStorage.getItem("studentHubTheme");

    if (savedTheme === "dark") {
        document.body.classList.add("dark");
        themeToggle.textContent = "☀️";
    }

    // Toggle dark mode
    themeToggle.addEventListener("click", function () {

        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark")) {
            localStorage.setItem("studentHubTheme", "dark");
            themeToggle.textContent = "☀️";
        } else {
            localStorage.setItem("studentHubTheme", "light");
            themeToggle.textContent = "🌙";
        }

    });
}

});