document.getElementById("btn_login").addEventListener("click", iniciarSesion);
document.getElementById("btn_registro").addEventListener("click", register);
window.addEventListener("resize", anchoPagina);

var contenedor_login_registro = document.querySelector(".contenedor_login-registro");
var form_login = document.querySelector(".form_login");
var form_registro = document.querySelector(".form_registro");
var caja_back_login = document.querySelector(".caja_back_login");
var caja_back_registro = document.querySelector(".caja_back_registro");


function anchoPagina(){
    if(window.innerWidth > 850){
        caja_back_login.style.display = "block";
        caja_back_registro.style.display = "block";
    }else{
        caja_back_registro.style.display = "block";
        caja_back_registro.style.opacity = "1";
        caja_back_login.style.display ="none";
        form_login.style.display ="block";
        form_registro.style.display = "none";
        contenedor_login_registro.style.left = "0px";
    }
}

anchoPagina();

function iniciarSesion(){

    if(window.innerWidth > 850){
        form_registro.style.display = "none";
        contenedor_login_registro.style.left = "10px";
        form_login.style.display = "block";
        caja_back_registro.style.opacity ="1";
        caja_back_login.style.opacity = "0";
    }else{
        form_registro.style.display = "none";
        contenedor_login_registro.style.left = "0px";
        form_login.style.display = "block";
        caja_back_registro.style.display ="block";
        caja_back_login.style.display = "none";
    }

}

function register(){

    if(window.innerWidth > 850){
        form_registro.style.display = "block";
        contenedor_login_registro.style.left = "410px";
        form_login.style.display = "none";
        caja_back_registro.style.opacity ="0";
        caja_back_login.style.opacity = "1";
    }else{
        form_registro.style.display = "block";
        contenedor_login_registro.style.left = "0px";
        form_login.style.display = "none";
        caja_back_registro.style.display ="none";
        caja_back_login.style.display = "block";
        caja_back_login.style.opacity = "1";
    }
    

}