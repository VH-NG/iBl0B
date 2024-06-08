document.addEventListener('DOMContentLoaded', function () {
    const burgerMenu = document.querySelector('.burger-menu');
    const burger = document.querySelector('.burger ul');

    burgerMenu.addEventListener('click', function () {
        burger.classList.toggle('active');
    });
});
