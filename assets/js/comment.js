let commentArea = document.getElementById('comment-form');
commentArea.style.display = "none";

let btnPost = document.getElementById('btnPost');
btnPost.addEventListener('click', function(){
    commentArea.style.display = "block";
    commentArea.classList.add('post');
    commentArea.action = "index.php?action=post&id=" + commentArea.getAttribute('value');
});

function displayForm(commentId){
    commentArea.style.display = "block";
    commentArea.classList.remove("post");
    commentArea.setAttribute('data-comment-id', commentId);
    let pageId = commentArea.getAttribute('value');
    commentArea.action = "index.php?action=post&id=" + pageId + "&comment=" + commentId; 
}

function formAction(){
    if(commentArea.classList.contains('post')){
        commentArea.action = "index.php?action=post&id=" + commentArea.getAttribute('value');
    }
    else{
        let pageId = commentArea.getAttribute('value');
        let commentId = commentArea.getAttribute('data-comment-id');
        commentArea.action = "index.php?action=post&id=" + id + "&comment=" + commentId; 
    }
}

