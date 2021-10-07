window.onload = () =>{
    document.querySelectorAll("[data-reply]").forEach(element => {
        element.addEventListener("click", function(){
            document.querySelector("#comment_parentId").value = this.dataset.id;
            var commentContent = document.querySelector(`#commentContent_${this.dataset.id}`).innerHTML;
            document.querySelector("#replyAt").innerHTML = "Vous répondez au commentaire:<br/>" + commentContent.substring(0,50) + "...";
        });
    });
}