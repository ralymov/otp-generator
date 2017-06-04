window.onload = function () {
    document.getElementById("password").onchange = validatePassword;
    document.getElementById("password-conf").onchange = validatePassword;
}
function validatePassword(){
var pass2=document.getElementById("password-conf").value;
var pass1=document.getElementById("password").value;
if(pass1!=pass2)
    document.getElementById("password-conf").setCustomValidity("Passwords Don't Match");
else
    document.getElementById("password-conf").setCustomValidity('');
//empty string means no validation error
}