function toggleContent(postId){
    let article = "article-" + postId;
    let content = document.getElementById(article);
    let container = document.getElementsByClassName('article');
    let subcontainer = document.getElementsByTagName('article');
    
    if(content.style.display == 'block'){
        content.style.display = "none";
        for(let i = 0; i < container.length; i++){
            container[i].style.height = "250px";
        }
        for(let i = 0; i < container.length; i++){
            subcontainer[i].style.height = "250px";
        }        
    }
    else{
        content.style.display = "block";
        for(let i = 0; i < container.length; i++){
            container[i].style.height = "auto";
        }
        for(let i = 0; i < container.length; i++){
            subcontainer[i].style.height = "auto";
        }        
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
if($_GET.edit === 'success'){
    alert('Votre article a bien été mis à jour !');
}

if($_GET.action === 'moderation'){
    if($_GET.page){
        let pageLinkId = "page-link-" + $_GET.page;
        let pageLink = document.getElementById(pageLinkId);

        pageLink.classList.add('active');
    }
    else{
        let pageLinkId = "page-link-1";
        let pageLink = document.getElementById(pageLinkId);

        pageLink.classList.add('active');
    }
}