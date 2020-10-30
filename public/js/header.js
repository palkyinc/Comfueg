let btnEnviarLogin = document.querySelector('#btnEnviarLogin');
  	btnEnviarLogin.addEventListener('click', e => {
  		e.preventDefault();
  		let user = document.querySelector('#user');
  		let inputPassword1 = document.querySelector('#inputPassword1');
  		fetch('serverRest/control.php', {
  			method:'POST',
  			body: JSON.stringify({
  				usuario: user.value, contrasena: inputPassword1.value
  			}),
  			header: {
  			"Content-type": "application/json; charset=UTF-8"
  			}
  			})
  			.then(valor=>valor.json())
  			.then(valor=>{
  				switch (valor) {
  					case 1:
              usuarioLogueado('#modalLogin');
  						break;
  					case 2:
              document.querySelector('#alert1').innerHTML = `<div class="alert alert-danger show" role="alert">Error en Usuario o Contraseña o ambas.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`
              break;
  					case 3:
  						document.querySelector('#alert1').innerHTML = `<div class="alert alert-danger show" role="alert">Usuario Bloqueado. Consulte un Supervisor o Administrador.<button type="button" class="close" data-dismiss="alert" aria-label="Close" id="myAlert465"><span aria-hidden="true">&times;</span></button></div>`;
              document.querySelector('#myAlert465').addEventListener('click', e=>{
                  $('#modalLogin').modal('hide');
                })
              //swal('Atención', 'Usuario Bloqueado. Consulte un Supervisor o Administrador', 'warning');
  						//$('#modalLogin').modal('hide');
  						break;
  					case 4: //contraseña caducada
    						$('#modalLogin').modal('hide');
                $('#modalChangePass').modal('show');
                document.querySelector('#alert2').innerHTML = `<div class="alert alert-danger show" role="alert">Contraseña Caducada.<button type="button" class="close" data-dismiss="alert" aria-label="Close" id="myAlert465"><span aria-hidden="true">&times;</span></button></div>`;
                changePass(user.value);
              break;
  					case 5:
  						swal('Error', 'En Base de Datos.', 'error')
  						break;
  					default:
  						swal("Error", "En Comunicacion.", "error");
  						break;
  				}
          })
        .catch(valor => {
          swal('ERROR', 'En página ' + valor, 'error')
        });
    })

let btnPerfilLogout = document.querySelector('#btnPerfilLogout');
    btnPerfilLogout.addEventListener('click', e => {
      fetch ('serverRest/salir.php', {method: 'GET'})
        .then(valor=>swal('OK', 'Cerraste Sesión', 'success'))
        .catch(valor=>swal('Error', 'Al comunicarse con el Servidor.', 'error'));
      $('#modalPerfil').modal('hide');
      document.querySelector('#liMiLogin').classList.remove("ocultar");
        document.querySelector('#liMiPerfil').classList.add("ocultar");
    })

let btnLogin = document.querySelector('#miLogin');
  btnLogin.addEventListener('click', e => {
    $('#modalLogin').modal('show');
  })

let btnPerfil = document.querySelector('#miPerfil');
  btnPerfil.addEventListener('click', e => {
    $('#modalPerfil').modal('show');
  })

let btnChangePassButton2  = document.querySelector('#changePassButton2');
btnChangePassButton2.addEventListener('click', e=> {
  e.preventDefault();
  if(btnPerfil.text != "") {
    $('#modalPerfil').modal('hide');
    $('#modalChangePass').modal('show');
    //alert (btnPerfil.text)
    changePass(btnPerfil.text);
  }else swal("Atención","Usuario no logueado", "warning");
  /*si pedir datos de perfil
      abrir modal de cambio de password
  sino error de usuario logueado y cerra modal*/
})

function changePass (user) {
    const oldPass = document.querySelector('#inputOldPassword');
    const newPass = document.querySelector('#inputNewPassword');
    const repeatNewPass = document.querySelector('#repeatNewPassword');
    const btnNewPass = document.querySelector('#changePassButton');
    btnNewPass.addEventListener('click', e=>{
      e.preventDefault();
      fetch('serverRest/control.php', {
          method:'POST',
          body: JSON.stringify({
            usuario: user, contrasena: oldPass.value, contrasenaNew: newPass.value, contrasenaNew2: repeatNewPassword.value
          }),
          header: {
          "Content-type": "application/json; charset=UTF-8"
          }
          })

      .then(valor=>valor.json())
      .then(valor=>{
            switch (valor) {
            // valor =1 -> ok, =2 -> password ant wrong, 
              case 1:
                swal("OK","Constraseña cambiada","success");
                usuarioLogueado('#modalChangePass');
                break;
              case 2:
                swal("Atención","Password anterior incorrecto","warning");
                break;
              case 3:
                swal("Atención","Usuario Bloqueado","warning");
                break;
              case 6:
                swal("Atención","Passwords Nuevos no coinciden","warning");
                break;
              case 7:
                swal("Atención","Password Nuevo repetido, Se recuerdan los seis ultimos, mas el provisorio y el actual","warning");
                break;
              case 8:
                swal("Atención","Password Nuevo no cumple con complejidad","warning");
                break;
              case 9:
                swal("ERROR","Al grabar en Base de Datos","error");
                break;
              default:
                swal("ERROR","Salió por defalut el Switch","error");
                break;
            }

      })
      .catch(()=>{swal('Error', 'Al intentar comunicarse con el servidor.', 'error')})
    });
    console.log('Termine el fetch')
}

function usuarioLogueado (modalACerrar) {
  $(modalACerrar).modal('hide');
  let liMiLogin = document.querySelector('#liMiLogin');
  let liMiPerfil = document.querySelector('#liMiPerfil');
  liMiLogin.classList.add("ocultar");
  liMiPerfil.classList.remove("ocultar");
  fetch ('serverRest/perfil.php', {
    method: 'GET'})
    .then(valor=>valor.json())
    .then(valor=>{
      btnPerfil.innerText = valor.usuario;
      document.querySelector('#ModalMiPerfil').innerText = valor.usuario;
      document.querySelector('#nom_ape').innerText = valor.nom_ape;
      document.querySelector('#nivel').innerText = 'Nivel: ' + valor.nivel;
      document.querySelector('#venceEn').innerText = 'Pass vence en: ' + valor.vence + ' dias.';
      document.querySelector('#ultLogin').innerText = 'Ultimo Login: ' + valor.ultLogin;
    })
    .catch(()=>{swal('Error', 'Al intentar comunicarse con el servidor.', 'error')})
}