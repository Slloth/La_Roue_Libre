window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    let header = document.querySelector("header");
    if(window.scrollY > 1000){
        console.log(window.scrollY);
        document.querySelector("#navbar").classList.add("bg-light");
    }
    else{
        document.querySelector("#navbar").classList.remove("bg-light");
    }
}