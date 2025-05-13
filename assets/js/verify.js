const BASE_URL = "http://localhost:8000"; // Adjust for your setup

document.addEventListener("DOMContentLoaded", async function () {
    const userData = localStorage.getItem("user");
    const user = userData ? JSON.parse(userData) : null; // ✅ Prevent errors if user data is missing
    const loggedIn = document.getElementById("loggedinuser");

    try {
        if (loggedIn) {
            loggedIn.textContent = user.name;
        }
    } catch (error) {
        console.error("Error fetching dashboard data:", error);
    }
});

async function checkLogin() {
    const token = localStorage.getItem("token");
    const refreshToken = localStorage.getItem("refreshToken");
    const userData = localStorage.getItem("user");
    const user = userData ? JSON.parse(userData) : null;
    const usertab = document.getElementById("userstab");

    if (!token) {
        window.location.href = "../login.html";
        return;
    }

    // If user is not admin, hide the admin-specific elements
    if (user && user.role !== "Admin" && usertab) {
        usertab.style.display = "none";
    }

    try {
        // Try fetching data with the current access token
        const response = await fetch(`${BASE_URL}/backend/auth/check_login.php`, {
            method: "GET",
            headers: { Authorization: `Bearer ${token}` },
        });

        const data = await response.json();

        if (!data.loggedIn) {
            // If the token is invalid or expired, attempt to refresh using the refresh token
            if (refreshToken) {
                const refreshResponse = await fetch(`${BASE_URL}/backend/auth/refresh-token.php`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ refreshToken: refreshToken }),
                });

                const refreshData = await refreshResponse.json();

                if (refreshData.success) {
                    localStorage.setItem("token", refreshData.accessToken);
                    localStorage.setItem("refreshToken", refreshData.refreshToken);
                    localStorage.setItem("user", JSON.stringify(refreshData.user));
                } else {
                    localStorage.removeItem("token");
                    localStorage.removeItem("refreshToken");
                    localStorage.removeItem("user");
                    window.location.href = "../login.html";
                }
            } else {
                localStorage.removeItem("token");
                localStorage.removeItem("refreshToken");
                localStorage.removeItem("user");
                window.location.href = "../login.html";
            }
        }
    } catch (error) {
        console.error("Error checking login:", error);
        // Logout the user on error
        localStorage.removeItem("token");
        localStorage.removeItem("refreshToken");
        localStorage.removeItem("user");
        window.location.href = "../login.html";
    }
}

// ✅ Navigation & Active Link Handling
function setupNavigation() {
    const nav = document.querySelector(".nav");
    const menuIcon = document.querySelector(".fa-bars");
    const navLinks = document.querySelectorAll(".nav-links");
    const currentPath = window.location.pathname;

    // Check localStorage for collapsed state
    if (localStorage.getItem("navCollapsed") === "true") {
        nav.classList.add("collapsed");
    }

    // Toggle nav collapse and store state
    if (menuIcon) {
        menuIcon.addEventListener("click", function () {
            nav.classList.toggle("collapsed");
            localStorage.setItem("navCollapsed", nav.classList.contains("collapsed"));
        });
    }

    // Highlight active link
    navLinks.forEach((link) => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const logoutLink = document.querySelector(".nav-links[href='../login.html']");
    if (logoutLink) {
        logoutLink.addEventListener("click", function (event) {
            event.preventDefault();
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            window.location.href = "../login.html";
        });
    } else {
        console.log("Logout link not found!");
    }
});


document.addEventListener("DOMContentLoaded", async function () {
    await checkLogin()
    setupNavigation(); // Setup navigation events
});