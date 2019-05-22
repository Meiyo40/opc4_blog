$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   

    
    
    function manageUser(action, userId){
        let url = "index.php?action=deleteuser&user="+userId;
        $.post(url,null,function(data){ 
            alert('Utilisateur supprimé');
        });
    }
    
     function submitUser(){
        let url = "index.php?action=users&newuser=true";
        $.post(url,null,function(data){ 
            alert('Utilisateur créé');
        });
    }
    
    let submit = document.getElementById('btn-submit');
    submit.addEventListener("click",() =>{
        
        let name = $('#orangeForm-name').val();
        let raw_pwd = $('#orangeForm-pass').val();
        let mail = $('#orangeForm-email').val();
        let rank = $('#orangeForm-rank').val();
        
        let user = {
            name: name,
            raw_pwd: raw_pwd,
            email: mail,
            rank: rank
        }
        
        let url = "index.php?action=users&newuser=true";
        $.post(url, {name: user.name, 
            raw_pwd: user.raw_pwd, 
            email: user.email, 
            rank: user.rank
                    },function(data){ 
            alert('Utilisateur créé');
        });
        
    });
    
});

function rankUser(action, userId){
    if(action){
        let url = "index.php?action=manageuser&rankaction=promote&user=" + userId;
        $.post(url,null,function(data){ 
            alert('Utilisateur promu');
        });
    }
    else{
        let url = "index.php?action=manageuser&rankaction=demote&user=" + userId;
        $.post(url,null,function(data){ 
            alert('Utilisateur retrogradé');
        });
    }
}