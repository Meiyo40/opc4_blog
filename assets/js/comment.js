let commentArea = document.getElementById('comment-form');
commentArea.style.display = "none";

let btnPost = document.getElementById('btnPost');
btnPost.addEventListener('click', function(){
    commentArea.style.display = "block";
    commentArea.classList.add('post');
    let pageId = commentArea.getAttribute('value');
    commentArea.action = "index.php?action=post&id=" + pageId + "&comment=primary";
});

function displayForm(commentId){
    commentArea.style.display = "block";
    commentArea.classList.remove("post");
    commentArea.setAttribute('data-comment-id', commentId);
    let pageId = commentArea.getAttribute('value');
    commentArea.action = "index.php?action=post&id=" + pageId + "&comment=" + commentId; 
}

