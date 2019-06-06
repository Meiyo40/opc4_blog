function displayModal( commentId, author){
    $('#modalanswer').modal('toggle');
    $('#answerto').val(author);
    let action = "index.php?action=adminanswer&answered="+commentId;
    $('#modalanswer').attr('action', action);
}



let submit = document.getElementById('btn-submit');
submit.addEventListener('click', () => {
    
    let name = $('#name').val();
    let message = tinyMCE.activeEditor.getContent();
    
    let answer = {
        name: name,
        message: message
    };
    
    console.log(answer.name + ' ' + answer.message);
    
    let url = $('#modalanswer').attr('action');
    $.post(url,{
        author: answer.name,
        message: answer.message
    },function(data){ 
        alert('Réponse valide');
        location.reload();
    });
})