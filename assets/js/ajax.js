function Ajax(url) {
    this.url = url;
    
    this.getXMLHttpRequest = () => {
        let xhr = null;
        if (window.XMLHttpRequest || window.ActiveXObject) {
            if (window.ActiveXObject) {
                try {
                    xhr = new ActiveXObject("Msxm12.XMLHTTP");
                } catch (e) {
                    xhr = new ActiveXObject("Microsoft.XMLHTTP");
                }
            } else {
                xhr = new XMLHttpRequest();
            }
        } else {
            alert("Votre navigateur ne supporte pas l'objet XMLHttpRequest");
            return null;
        }
        return xhr;
    }
    
    this.ajaxGet = () => {
        let oXhr = this.getXMLHttpRequest();

        oXhr.onerror = function (data) {
            console.log('Erreur... ');
        };

        oXhr.open("GET", this.url, true);
        oXhr.send();

        oXhr.onreadystatechange = function() { alert("Commentaire signalé, il sera vérifié par l'administration") };
    }
    
    this.ajaxPost = () => {
        let oXhr = this.getXMLHttpRequest();
        
        oXhr.onerror = function (data) {
            console.log('Erreur... ');
        };

        oXhr.open("POST", this.url, true);
        oXhr.send();
        
        oXhr.onreadystatechange = () => {
            let DONE = 4;
            let OK = 200;
            
            if(oXhr.readyState === DONE){
                if(oXhr.status === OK){
                    console.log('okay');
                }
                else{
                    console.log('Error: '+ oXhr.status);
                }
            }
        }
        oXhr.success = (data) => {
            console.log(data);
        }
    }   
}