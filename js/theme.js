// Function to switch themes and store preference
function toggleTheme() {
    const htmlElement = document.documentElement;
    const currentTheme = htmlElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    htmlElement.setAttribute('data-bs-theme', newTheme);
    sessionStorage.setItem('theme', newTheme);
}

// Check and set the theme from session storage on page load
window.onload = function() {
    const savedTheme = sessionStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    }

    const themeSwitch = document.getElementById('themeSwitch');
    if(savedTheme == 'dark') {
        themeSwitch.setAttribute('checked', '');
    }
};