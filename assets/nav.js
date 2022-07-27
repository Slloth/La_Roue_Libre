window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    let navbar = document.querySelector("#navbar");
    if(window.scrollY < 1000  && window.scrollY > 0 && document.querySelector("video")){
        navbar.classList.remove("bg-light");
    }
    else{
        navbar.classList.add("bg-light");
    }
}