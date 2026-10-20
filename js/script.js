document.addEventListener("DOMContentLoaded", function () {
    /* =========================================================
       1. SIDEBAR / HAMBURGER MENU
       ========================================================= */
    const sidebar = document.getElementById("sidebar");
    const menuButton = document.getElementById("menuButton");
    const overlay = document.getElementById("overlay");
    if (sidebar && menuButton) {
        // Restore sidebar state
        const sidebarState = localStorage.getItem("studentHubSidebar");
        if (sidebarState === "open") {
            sidebar.classList.add("active");
            if (overlay) {
                overlay.classList.add("active");
            }
        }
        // Open / close sidebar
        menuButton.addEventListener("click", function (event) {
            event.stopPropagation();
            sidebar.classList.toggle("active");
            if (overlay) {
                overlay.classList.toggle("active");
            }
            if (sidebar.classList.contains("active")) {
                document.documentElement.classList.add("sidebar-should-open");
                localStorage.setItem("studentHubSidebar","open");
            } else {
                document.documentElement.classList.remove("sidebar-should-open");
                localStorage.setItem("studentHubSidebar","closed");
            }
        });
        // Click outside sidebar
        if (overlay) {
            overlay.addEventListener("click", function () {
                sidebar.classList.remove("active");
                overlay.classList.remove("active");
                document.documentElement.classList.remove("sidebar-should-open");
                localStorage.setItem("studentHubSidebar","closed");
            });
        }
        // Highlight current page
        const currentPage = window.location.pathname.split("/").pop();
        const sidebarLinks = document.querySelectorAll(".sidebar-links a");
        sidebarLinks.forEach(function (link) {
            const href = link.getAttribute("href");
            if (!href) return;
            const linkPage = href.split("/").pop();
            if (linkPage === currentPage) {
                link.classList.add("active");
            }
        });
    }
    /* =========================================================
       2. DARK / LIGHT THEME
       ========================================================= */
    const themeToggle =  document.getElementById("themeToggle");
    if (themeToggle) {
        // Restore saved theme
        const savedTheme = localStorage.getItem("studentHubTheme");
        if (savedTheme === "dark") {
            document.body.classList.add("dark");
            themeToggle.textContent = "☀️";
            themeToggle.setAttribute("aria-label", "Switch to light mode"
            );
        } else {
            themeToggle.textContent = "🌙";
            themeToggle.setAttribute( "aria-label", "Switch to dark mode");
        }
        // Toggle theme
        themeToggle.addEventListener("click", function () {
            document.body.classList.toggle("dark");
            if (document.body.classList.contains("dark")) {
                localStorage.setItem(
                    "studentHubTheme",
                    "dark"
                );
                themeToggle.textContent = "☀️";
                themeToggle.setAttribute(
                    "aria-label",
                    "Switch to light mode"
                );
            } else {
                localStorage.setItem(
                    "studentHubTheme",
                    "light"
                );
                themeToggle.textContent = "🌙";
                themeToggle.setAttribute(
                    "aria-label",
                    "Switch to dark mode"
                );
            }
        });
    }
    /* =========================================================
       3. REGISTRATION FORM VALIDATION
       ========================================================= */
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        const fullname =  document.getElementById("fullname");
        const studentid =  document.getElementById("studentid");
        const email = document.getElementById("email");
        const mobile = document.getElementById("mobile");
        const department = document.getElementById("department");
        const year = document.getElementById("year");
        const registerpassword = document.getElementById("registerpassword");
        const confirmPassword =  document.getElementById("confirmPassword");
        const genderInputs = document.querySelectorAll( 'input[name="gender"]');
        const terms =  document.getElementById("terms");
        const strengthText =  document.getElementById("passwordStrength");
        const strengthBar =  document.getElementById("strengthBar");
        /* ---------- Error helper ---------- */
        function showError(input, message) {
            if (!input) return;
            const group = input.closest(".form-group");
            if (!group) return;
            let error = group.querySelector(".error-message");
            if (!error) {
                error =
                    document.createElement("small");
                error.className =
                    "error-message";
                group.appendChild(error);
            }
            error.textContent = message;
            input.classList.add("input-error");
            input.setAttribute(
                "aria-invalid",
                "true"
            );
        }
        /* ---------- Clear error ---------- */
        function clearError(input) {
            if (!input) return;
            const group = input.closest(".form-group");
            if (!group) return;
            const error =
                group.querySelector(".error-message");
            if (error) {
                error.textContent = "";
            }
            input.classList.remove("input-error");
            input.removeAttribute(
                "aria-invalid"
            );
        }
        /* ---------- Name validation ---------- */
        function validateName() {
            const value =
                fullname.value.trim();
            const namePattern =
                /^[A-Za-z ]{2,50}$/;
            if (value === "") {
                showError(
                    fullname,
                    "Full name is required."
                );
                return false;
            }
            if (!namePattern.test(value)) {
                showError(
                    fullname,
                    "Name should contain only letters and spaces."
                );
                return false;
            }
            clearError(fullname);
            return true;
        }
        /* ---------- Student ID validation ---------- */
        function validateStudentId() {
            const value =
                studentid.value.trim().toUpperCase();
            studentid.value = value;
            const studentIdPattern =
                /^(23|24|25|26)(CE|IT|CS|EC|ME)\d{3}$/;
            if (value === "") {
                showError(
                    studentid,
                    "Student ID is required."
                );
                return false;
            }
            if (!studentIdPattern.test(value)) {
                showError(
                    studentid,
                    "Example: 25CE111 or 24CS045."
                );
                return false;
            }
            clearError(studentid);
            return true;
        }
        /* ---------- Email validation ---------- */
        function validateEmail() {
            const value = email.value.trim().toLowerCase();
            email.value = value;
            const emailPattern = /^(23|24|25|26)(ce|it|cs|ec|me)\d{3}@studenthub\.edu\.in$/i;
            if (value === "") {
                showError(
                    email, "Email is required.");
                return false;
            }
            if (!emailPattern.test(value)) {
                showError(
                    email, "Use your StudentHub email, e.g. 25ce111@studenthub.edu.in"
                );
                return false;
            }
            if (studentid.value.trim() !== "") {
                const requiredEmail =
                    studentid.value.trim().toLowerCase()
                    + "@studenthub.edu.in";
                if (value !== requiredEmail) {
                    showError(
                        email,
                        "Email must match your Student ID."
                    );
                    return false;
                }
            }
            clearError(email);
            return true;
        }
        /* ---------- Mobile validation ---------- */
        function validateMobile() {
            const value =
                mobile.value.trim();
            const mobilePattern =
                /^[6-9]\d{9}$/;
            if (value === "") {
                showError(
                    mobile,
                    "Mobile number is required."
                );
                return false;
            }
            if (!mobilePattern.test(value)) {
                showError(
                    mobile,
                    "Enter a valid 10-digit Indian mobile number."
                );
                return false;
            }
            clearError(mobile);
            return true;
        }
        /* ---------- Department ---------- */
        function validateDepartment() {
            if (department.value === "") {
                showError(
                    department,
                    "Please select your department."
                );
                return false;
            }
            clearError(department);
            return true;
        }
        /* ---------- Year ---------- */
        function validateYear() {
            if (year.value === "") {
                showError(
                    year,
                    "Please select your year."
                );
                return false;
            }
            clearError(year);
            return true;
        }
        /* ---------- Gender ---------- */
        function validateGender() {
            let selected = false;
            genderInputs.forEach(function (radio) {
                if (radio.checked) {
                    selected = true;
                }
            });
            const genderError =
                document.getElementById(
                    "genderError"
                );
            if (!selected) {
                if (genderError) {
                    genderError.textContent =
                        "Please select your gender.";
                }
                return false;
            }
            if (genderError) {
                genderError.textContent = "";
            }
            return true;
        }
        /* ---------- Password strength ---------- */
        function checkPasswordStrength() {
            const password =
                registerpassword.value;
            let strength = 0;
            if (password.length >= 8) {
                strength++;
            }
            if (/[A-Z]/.test(password)) {
                strength++;
            }
            if (/[a-z]/.test(password)) {
                strength++;
            }
            if (/\d/.test(password)) {
                strength++;
            }
            if (/[@$!%*?&]/.test(password)) {
                strength++;
            }
            if (password.length === 0) {
                strengthText.textContent =
                    "Password Strength: Not entered";
                strengthBar.style.width = "0%";
                strengthBar.className =
                    "strength-bar";
                return 0;
            }
            if (strength <= 2) {
                strengthText.textContent =
                    "Password Strength: Weak";
                strengthBar.style.width =
                    "33%";
                strengthBar.className =
                    "strength-bar weak";
            } else if (strength <= 4) {
                strengthText.textContent =
                    "Password Strength: Medium";
                strengthBar.style.width =
                    "66%";
                strengthBar.className =
                    "strength-bar medium";
            } else {
                strengthText.textContent =
                    "Password Strength: Strong";
                strengthBar.style.width =
                    "100%";
                strengthBar.className =
                    "strength-bar strong";
            }
            return strength;
        }
        /* ---------- Password validation ---------- */
        function validatePassword() {
            const password =
                registerpassword.value;
            const passwordPattern =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
            if (password === "") {
                showError(
                    registerpassword,
                    "Password is required."
                );
                return false;
            }
            if (!passwordPattern.test(password)) {
                showError(
                    registerpassword,
                    "Use 8+ characters with uppercase, lowercase, number and special character."
                );
                return false;
            }
            clearError(registerpassword);
            return true;
        }
        /* ---------- Confirm password ---------- */
        function validateConfirmPassword() {
            if (confirmPassword.value === "") {
                showError(
                    confirmPassword,
                    "Please confirm your password."
                );
                return false;
            }
            if (
                confirmPassword.value !==
                registerpassword.value
            ) {
                showError(
                    confirmPassword,
                    "Passwords do not match."
                );
                return false;
            }
            clearError(confirmPassword);
            return true;
        }
        /* ---------- Terms ---------- */
        function validateTerms() {
            const termsError =
                document.getElementById(
                    "termsError"
                );
            if (!terms.checked) {
                if (termsError) {
                    termsError.textContent =
                        "You must accept the terms and conditions.";
                }
                return false;
            }
            if (termsError) {
                termsError.textContent = "";
            }
            return true;
        }
        /* ---------- Real-time validation ---------- */
        fullname.addEventListener(
            "input",
            validateName
        );
        studentid.addEventListener(
            "input",
            validateStudentId
        );
        studentid.addEventListener(
            "blur",
            validateEmail
        );
        email.addEventListener(
            "input",
            validateEmail
        );
        mobile.addEventListener(
            "input",
            validateMobile
        );
        department.addEventListener(
            "change",
            validateDepartment
        );
        year.addEventListener(
            "change",
            validateYear
        );
        registerpassword.addEventListener(
            "input",
            function () {
                checkPasswordStrength();
                if (registerpassword.value !== "") {
                    validatePassword();
                }
                if (confirmPassword.value !== "") {
                    validateConfirmPassword();
                }
            }
        );
        confirmPassword.addEventListener(
            "input",
            validateConfirmPassword
        );
        genderInputs.forEach(function (radio) {
            radio.addEventListener(
                "change",
                validateGender
            );
        });
        terms.addEventListener(
            "change",
            validateTerms
        );
        /* ---------- Submit ---------- */
        registerForm.addEventListener(
    "submit",
    function (event) {

        const valid =
            validateName() &&
            validateStudentId() &&
            validateEmail() &&
            validateMobile() &&
            validateDepartment() &&
            validateYear() &&
            validateGender() &&
            validatePassword() &&
            validateConfirmPassword() &&
            validateTerms();

        if (!valid) {

            event.preventDefault();

            const firstError =
                registerForm.querySelector(
                    ".input-error"
                );

            if (firstError) {
                firstError.focus();
            }

            return;
        }
/*         * The browser will now submit
         * the form to PHP using POST.
         */

    }
);
        /* ---------- Reset ---------- */
        registerForm.addEventListener(
            "reset",
            function () {
                setTimeout(function () {
                    document
                        .querySelectorAll(
                            ".error-message"
                        )
                        .forEach(function (error) {
                            error.textContent = "";
                        });
                    document
                        .querySelectorAll(
                            ".input-error"
                        )
                        .forEach(function (input) {
                            input.classList.remove(
                                "input-error"
                            );
                            input.removeAttribute(
                                "aria-invalid"
                            );
                        });
                    checkPasswordStrength();
                }, 0);
            }
        );
    }
    
/* =========================================================
   4. LOGIN FORM
   ========================================================= */

const loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
        const loginId = document.getElementById("login_id");
        const password = document.getElementById("LoginPassword");

        if (
            !loginId ||
            !password ||
            !loginId.value.trim() ||
            !password.value
        ) {
            event.preventDefault();
            alert("Please enter your login ID and password.");
        }
    });
}

    /* =========================================================
       5. NOTIFICATION BANNER
       ========================================================= */
    const notification =
        document.getElementById(
            "notificationBanner"
        );
    const notificationClose =
        document.getElementById(
            "notificationClose"
        );
    if (notification && notificationClose) {
        notificationClose.addEventListener(
            "click",
            function () {
                notification.classList.add(
                    "hidden"
                );
                notification.setAttribute(
                    "aria-hidden",
                    "true"
                );
            }
        );
    }
    /* =========================================================
       6. COLLAPSIBLE FAQ
       ========================================================= */
    const faqButtons =
        document.querySelectorAll(
            ".faq-question"
        );
    faqButtons.forEach(function (button) {
        button.addEventListener(
            "click",
            function () {
                const answerId =
                    button.getAttribute(
                        "aria-controls"
                    );
                const answer =
                    document.getElementById(
                        answerId
                    );
                const isOpen =
                    button.getAttribute(
                        "aria-expanded"
                    ) === "true";
                // Close all FAQ answers
                faqButtons.forEach(
                    function (otherButton) {
                        otherButton.setAttribute(
                            "aria-expanded",
                            "false"
                        );
                        const otherId =
                            otherButton.getAttribute(
                                "aria-controls"
                            );
                        const otherAnswer =
                            document.getElementById(
                                otherId
                            );
                        if (otherAnswer) {
                            otherAnswer.classList.remove(
                                "open"
                            );
                        }
                    }
                );
                // Open clicked answer
                if (!isOpen) {
                    button.setAttribute(
                        "aria-expanded",
                        "true"
                    );
                    if (answer) {
                        answer.classList.add(
                            "open"
                        );
                    }
                }
            }
        );
    });
    /* =========================================================
       7. MODAL POPUP
       ========================================================= */
    const modal =
        document.getElementById(
            "eventModal"
        );
    const modalOpenButtons =
        document.querySelectorAll(
            ".open-modal"
        );
    const modalClose =
        document.getElementById(
            "modalClose"
        );
    const modalOverlay =
        document.getElementById(
            "modalOverlay"
        );
    function openModal() {
        if (!modal) return;
        modal.classList.add("show");
        modal.setAttribute(
            "aria-hidden",
            "false"
        );
        document.body.classList.add(
            "modal-open"
        );
        if (modalClose) {
            modalClose.focus();
        }
    }
    function closeModal() {
        if (!modal) return;
        modal.classList.remove("show");
        modal.setAttribute(
            "aria-hidden",
            "true"
        );
        document.body.classList.remove(
            "modal-open"
        );
    }
    modalOpenButtons.forEach(
        function (button) {
            button.addEventListener(
                "click",
                openModal
            );
        }
    );
    if (modalClose) {
        modalClose.addEventListener(
            "click",
            closeModal
        );
    }
    if (modalOverlay) {
        modalOverlay.addEventListener(
            "click",
            closeModal
        );
    }
    // ESC key closes modal
    document.addEventListener(
        "keydown",
        function (event) {
            if (
                event.key === "Escape" &&
                modal &&
                modal.classList.contains("show")
            ) {
                closeModal();
            }
        }
    );
    /* =========================================================
       8. IMAGE / CONTENT SLIDER
       ========================================================= */
    const slides =
        document.querySelectorAll(
            ".slider-slide"
        );
    const previousButton =
        document.getElementById(
            "sliderPrev"
        );
    const nextButton =
        document.getElementById(
            "sliderNext"
        );
    const sliderCounter =
        document.getElementById(
            "sliderCounter"
        );
    let currentSlide = 0;
    function showSlide(index) {
        if (slides.length === 0) {
            return;
        }
        if (index >= slides.length) {
            currentSlide = 0;
        }
        if (index < 0) {
            currentSlide =
                slides.length - 1;
        }
        slides.forEach(
            function (slide, i) {
                slide.classList.toggle(
                    "active",
                    i === currentSlide
                );
            }
        );
        if (sliderCounter) {
            sliderCounter.textContent =
                (currentSlide + 1)
                + " / "
                + slides.length;
        }
    }
    if (slides.length > 0) {
        showSlide(currentSlide);
        if (nextButton) {
            nextButton.addEventListener(
                "click",
                function () {
                    currentSlide++;
                    showSlide(currentSlide);
                }
            );
        }
        if (previousButton) {
            previousButton.addEventListener(
                "click",
                function () {
                    currentSlide--;
                    showSlide(currentSlide);
                }
            );
        }
        // Keyboard support for slider
        document.addEventListener(
            "keydown",
            function (event) {
                if (
                    event.key === "ArrowRight"
                ) {
                    currentSlide++;
                    showSlide(currentSlide);
                }
                if (
                    event.key === "ArrowLeft"
                ) {
                    currentSlide--;
                    showSlide(currentSlide);
                }
            }
        );
    }
    /* =========================================================
       9. AUTO SLIDER
       ========================================================= */
    if (slides.length > 1) {
        setInterval(
            function () {
                currentSlide++;
                showSlide(currentSlide);
            },
            5000
        );
    }

    /* =========================================================
   PRACTICAL 6 - FETCH API, SEARCH, FILTER, SORT & PAGINATION
   ========================================================= */

const eventsContainer =
    document.getElementById("eventsContainer");

const eventSearch =
    document.getElementById("eventSearch");

const eventCategory =
    document.getElementById("eventCategory");

const eventSort =
    document.getElementById("eventSort");

const eventLoading =
    document.getElementById("eventLoading");

const eventError =
    document.getElementById("eventError");

const prevPage =
    document.getElementById("prevPage");

const nextPage =
    document.getElementById("nextPage");

const pageInfo =
    document.getElementById("pageInfo");

if (eventsContainer) {
    let events = [];
    let filteredEvents = [];
    let currentPage = 1;
    const eventsPerPage = 6;
    /* ---------- FETCH JSON ---------- */
    async function loadEvents() {
        try {
            eventLoading.textContent = "Loading events...";
            eventError.textContent = "";
            const response = await fetch("../data/events.json");

            if (!response.ok) {
                throw new Error(
                    "Unable to load events.json"
                );

            }
            events = await response.json();
            filteredEvents = [...events];


            /* Create category options */

            const categories =
                [...new Set(
                    events.map(event => event.category)
                )];


            categories.forEach(category => {

                const option =
                    document.createElement("option");

                option.value = category;

                option.textContent = category;

                eventCategory.appendChild(option);

            });


            eventLoading.textContent = "";

            displayEvents();

        }
        catch (error) {

            eventLoading.textContent = "";

            eventError.textContent =
                "Error loading events. Please try again.";

            console.error(error);

        }

    }


    /* ---------- DISPLAY EVENTS ---------- */

    function displayEvents() {

        eventsContainer.innerHTML = "";


        const start =
            (currentPage - 1) * eventsPerPage;

        const end =
            start + eventsPerPage;


        const pageEvents =
            filteredEvents.slice(start, end);


        if (pageEvents.length === 0) {

            eventsContainer.innerHTML =
                "<p>No events found.</p>";

            updatePagination();

            return;

        }


        pageEvents.map(event => {

            const card =
                document.createElement("div");

            card.className = "card";


            card.innerHTML = `

                <h3>${event.title}</h3>

                <p>
                    <strong>Category:</strong>
                    ${event.category}
                </p>

                <p>
                    <strong>Date:</strong>
                    ${event.date}
                </p>

                <p>
                    <strong>Location:</strong>
                    ${event.location}
                </p>

                <p>
                    ${event.description}
                </p>

                <span class="badge badge-blue">
                    ${event.category}
                </span>

            `;


            eventsContainer.appendChild(card);

        });


        updatePagination();

    }


    /* ---------- SEARCH + FILTER ---------- */

    function applyFilters() {

        const searchText =
            eventSearch.value
                .toLowerCase()
                .trim();


        const category =
            eventCategory.value;


        filteredEvents =
            events.filter(event => {

                const matchesSearch =
                    event.title
                        .toLowerCase()
                        .includes(searchText) ||

                    event.description
                        .toLowerCase()
                        .includes(searchText);


                const matchesCategory =
                    category === "all" ||
                    event.category === category;


                return (
                    matchesSearch &&
                    matchesCategory
                );

            });


        currentPage = 1;

        applySorting();

    }


    /* ---------- SORTING ---------- */

    function applySorting() {

        const sortValue =
            eventSort.value;


        if (sortValue === "title-asc") {

            filteredEvents.sort(
                (a, b) =>
                    a.title.localeCompare(b.title)
            );

        }


        else if (sortValue === "title-desc") {

            filteredEvents.sort(
                (a, b) =>
                    b.title.localeCompare(a.title)
            );

        }


        else if (sortValue === "date-asc") {

            filteredEvents.sort(
                (a, b) =>
                    new Date(a.date) -
                    new Date(b.date)
            );
        }


        else if (sortValue === "date-desc") {

            filteredEvents.sort(
                (a, b) =>
                    new Date(b.date) -
                    new Date(a.date)
            );
        }
        displayEvents();

    }


    /* ---------- PAGINATION ---------- */

    function updatePagination() {

        const totalPages =
            Math.ceil(
                filteredEvents.length /
                eventsPerPage
            );


        pageInfo.textContent =
            `Page ${currentPage} of ${Math.max(
                totalPages,
                1
            )}`;

        prevPage.disabled =
            currentPage === 1;

        nextPage.disabled =
            currentPage >= totalPages;

    }


    /* ---------- EVENT LISTENERS ---------- */

    eventSearch.addEventListener(
        "input",
        applyFilters
    );

    eventCategory.addEventListener(
        "change",
        applyFilters
    );


    eventSort.addEventListener(
        "change",
        applySorting
    );


    prevPage.addEventListener(
        "click",
        function () {

            if (currentPage > 1) {

                currentPage--;

                displayEvents();

            }

        }
    );


    nextPage.addEventListener(
        "click",
        function () {
            const totalPages =
                Math.ceil(
                    filteredEvents.length /
                    eventsPerPage
                );

            if (currentPage < totalPages) {
                currentPage++;
                displayEvents();
            }
        }
    );
    /* Start */
    loadEvents();

}
});