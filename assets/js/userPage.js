$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   

    function rankUser(action, userId){
        if(action == 'promote'){
            let url = "index.php?action=manageuser&user="+userId+"&rankaction=promote";
            $.post(url,null,function(data){ 
                alert('Utilisateur promu');
            });
        }
        else{
            let url = "index.php?action=manageuser&user="+userId+"&rankaction=demote";
            $.post(url,null,function(data){ 
                alert('Utilisateur retrogradé');
            });
        }
    }
    
    function manageUser(action, userId){
        let url = "index.php?action=deleteuser&user="+userId;
        $.post(url,null,function(data){ 
            alert('Utilisateur supprimé');
        });
    }
    
});