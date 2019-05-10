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
        var oXhr = this.getXMLHttpRequest();

        oXhr.onerror = function (data) {
            console.log('Erreur... ');
        };

        oXhr.open("GET", this.url, true);
        oXhr.send();

        oXhr.onreadystatechange = function() { alert("Commentaire signalé, il sera vérifié par l'administration") };
    }
}