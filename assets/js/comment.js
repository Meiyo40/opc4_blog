let commentArea = document.getElementById('comment-form');
if(commentArea){
    commentArea.style.display = "none";
}

let btnPost = document.getElementById('btnPost');
if(btnPost){
    btnPost.addEventListener('click', function(){
        commentArea.style.display = "block";
        commentArea.classList.add('post');
        let pageId = commentArea.getAttribute('value');
        let depth = document.getElementById('node-depth-form');
        depth.setAttribute('value', '0');
        commentArea.action = "index.php?action=post&id=" + pageId + "&comment=primary";
    });
}

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

function reportComment(commentId, pageId){
    let url = "index.php?action=post&id" + pageId + "&report=" + commentId;
    let ajax = new Ajax(url);
    ajax.ajaxGet();
}

//TODO OR NOT DYNAMIC FORM
function positionForm(element){
    let form = document.createElement("form");
    form.setAttribute('method',"post");
    let formAction = "index.php?action=post&id=";
    form.setAttribute('action',"submit.php");
    form.setAttribute('id', 'comment-form');
    
    let name = document.createElement("input");
    name.setAttribute('type',"text");
    name.setAttribute('name',"name");
    name.setAttribute('placeholder', 'nom/pseudo');
    
    let textarea = document.createElement("textarea");
    textarea.setAttribute('form',"comment-form");
    textarea.setAttribute('name',"commentContent");

    let submit = document.createElement("input");
    submit.setAttribute('type',"submit");
    submit.setAttribute('value',"Envoyer");
    
    form.appendChild(name);
    form.appendChild(textarea);
    form.appendChild(submit);

    element.appendChild(form);
}