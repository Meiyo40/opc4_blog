$(document).ready(function(){

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
                let state = JSON.parse(data);
                if(state.SUCCESS){
                    alert('Utilisateur créé');
                }
                else if(state.ERROR === 'userexist'){
                    alert('Ce nom d\'utilisateur existe déjà');
                }
                else if(state.ERROR === 'mailexist'){
                    alert('Ce mail est déjà utilisé');
                }
                else{
                    alert('Erreur');
                }
        });
        
    });
    
});

function promoteUser(userId){
    let url = "index.php?action=promoteuser&userid=" + userId;
    $.post(url,null,function(data){ 
        alert(data);
        location.reload();
    });
}

function demoteUser(userId){
    let url = "index.php?action=demoteuser&userid=" + userId;
    $.post(url,null,function(data){ 
        alert(data);
        location.reload();
    });
}

function deleteUser(userId){
    let url = "index.php?action=deleteuser&user=" + userId;
        $.post(url,null,function(data){ 
            let state = JSON.parse(data);
            if(state.result === 'success'){
                deleteRow(userId);
                alert('Utilisateur supprimé !');
            }
            else{
                alert('Erreur lors de la suppression');
            }
        });
}

function deleteRow(userId){
    let row = document.getElementById('user-'+userId);
    row.style.display = 'none';
}