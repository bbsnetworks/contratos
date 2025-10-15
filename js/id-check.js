let id_valido=true;
document.getElementById('ncontrato').addEventListener('input', function() {
    
    var ncontrato = this.value;
    var errorMessage = document.getElementById('error-message');
    
    if (ncontrato !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "./php/id-check.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText === 'exists') {
                    errorMessage.innerText = "El ID ya existe. Por favor, elija otro.";
                    id_valido=false;

                } else {
                    errorMessage.innerText = "";
                    id_valido=true;
                }
            }
        };

        xhr.send("ncontrato=" + ncontrato);
    } else {
        errorMessage.innerText = "";
    }
});
