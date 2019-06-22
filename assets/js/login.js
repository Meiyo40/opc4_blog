function loginFail(state) {
   if(state == 'loginFail'){
        let form = document.getElementById('login-form');
        let alert = document.createElement('p');
        alert.textContent = "Identifiant ou mot de passe incorrect !";
        alert.id = 'alert-login';
        alert.setAttribute('style', 'color:red');
        form.appendChild(alert);       
    }
}