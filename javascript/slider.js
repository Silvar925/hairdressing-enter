const btnBurger = document.getElementById("btnBurger")
const mobileMenu = document.getElementById("mobile-menu-list")
btnBurger.addEventListener("click", function () {
    mobileMenu.classList.toggle("active")
})