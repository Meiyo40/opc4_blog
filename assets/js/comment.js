let commentArea = document.getElementById('comment-form');
commentArea.style.display = "none";

let btnPost = document.getElementById('btnPost');
btnPost.addEventListener('click', function(){
    commentArea.style.display = "block";
    commentArea.classList.add('post');
    let pageId = commentArea.getAttribute('value');
    let depth = document.getElementById('node-depth-form');
    depth.setAttribute('value', '0');
    commentArea.action = "index.php?action=post&id=" + pageId + "&comment=primary";
});

function displayForm(commentId, node_depth){
    commentArea.style.display = "block";
    commentArea.classList.remove("post");
    commentArea.setAttribute('data-comment-id', commentId);
    let pageId = commentArea.getAttribute('value');
    let depth = document.getElementById('node-depth-form');
    if(node_depth < 2){
        node_depth++;
    }
    else{
        node_depth = 2;
    }
    depth.setAttribute('value', node_depth);
    commentArea.action = "index.php?action=post&id=" + pageId + "&comment=" + commentId; 
}

