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