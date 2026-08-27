document.addEventListener("DOMContentLoaded", function () {

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

const registerForm= document.getElementById("registerForm");
if(registerForm){
    registerForm.addEventListener("submit",function(event){
        const fullname=document.getElementById("fullname").value;
        const studentid=document.getElementById("studentid").value;
        const registerpassword=document.getElementById("registerpassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;
        const email = document.getElementById("email").value.trim();
        const department = document.getElementById("department").value;
         if (
                fullname === "" ||
                studentid === "" ||
                email === "" ||
                department === "" ||
                registerpassword === "" ||
                confirmPassword === ""
            ) {
                event.preventDefault();
                alert("Please fill in all the fields.");
                return;
            }

           const studentIdPattern = /^(23|24|25|26)(CE|IT|CS|EC|ME)\d{3}$/;
            if (!studentIdPattern.test(studentid)) {
                event.preventDefault();
                alert(
                    "Invalid Student ID.\n\n" +
                    "Format should be like:\n" +
                    "25CE111\n" +
                    "24CS045\n" 
                );
                return;
            }

            // Create the required email from Student ID
            const emailPattern = /^(23|24|25|26)(ce|it|cs|ec|me)\d{3}@studenthub\.edu\.in$/i;
        if (!emailPattern.test(email)) {
            event.preventDefault();
            alert(
                "Invalid email address.\n\n" +
                "Email must be like:\n" +
                "25ce111@studenthub.edu.in"
            );
            return;
        }

        // ==========================================
        // CHECK EMAIL MATCHES STUDENT ID
        // ==========================================

        const requiredEmail = studentid.toLowerCase() + "@studenthub.edu.in";
        if (email !== requiredEmail) {
            event.preventDefault();
            alert(
                "Email does not match the Student ID.\n\n");
            return;
        }

            // Check password length
            if (password.length < 6) {
                event.preventDefault();
                alert("Password must be at least 6 characters long.");
                return;
            }

            // Check password matching
            if (registerpassword !== confirmPassword) {
                event.preventDefault();
                alert("Passwords do not match.");
                return;
            }

            // Successful registration
            event.preventDefault();
            alert("Registration successful! Welcome to StudentHub.");
            registerForm.reset();
    })
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