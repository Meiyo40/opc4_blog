function toggleContent(postId){
    let article = "article-" + postId;
    let content = document.getElementById(article);
    
    if(content.style.display == 'block'){
        content.style.display = "none";
    }
    else{
        content.style.display = "block";
    }
}

let $_GET = [];
let parts = window.location.search.substr(1).split("&");
for (let i = 0; i < parts.length; i++) {
    let temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

if($_GET.addPost === 'success'){
    alert('Votre article a bien été posté !');
}