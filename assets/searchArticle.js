function toggleMenu(){
    const navbar = document.querySelector('#search_article');
    const burger = document.querySelector('.navbar-toggler');
    const burger_icon = document.querySelector('.navbar-toggler-icon');
    burger.addEventListener('click', () =>{
        navbar.classList.toggle('show-nav');
        burger_icon.classList.toggle('navbar-toggler-icon-click')
    });
}
toggleMenu();