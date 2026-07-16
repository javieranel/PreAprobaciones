document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});



function inicio_de_sesion() {
  // Obtener los datos del formulario
  var formData = {
    user: $('#username').val(),
    pass: $('#password').val(),
  };

  // Limpiar los mensajes de error previamente mostrados
  $('#usernameError').hide();
  $('#passwordError').hide();
  $('#error-message').hide(); // Limpiar el mensaje de error general

  // Validación de campos vacíos
  if (formData.user === "") {
    $('#usernameError').show(); // Mostrar error para el usuario
    return; // Detener la ejecución si el usuario está vacío
  }

  if (formData.pass === "") {
    $('#passwordError').show(); // Mostrar error para la contraseña
    return; // Detener la ejecución si la contraseña está vacía
  }

  // Llamada AJAX para guardar los datos si la validación es exitosa
  $.ajax({
    //url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/login.php',
    url: './admin_users/validar_login.php',
    type: 'POST',
    data: {
      username: formData.user,  // Enviando los datos como URL codificados
      password: formData.pass,
    },
    success: function (response) {
      // Si el login es exitoso, redirigir al dashboard
      if (response.trim() === "Login exitoso") {
        window.location.href = 'https://apps.melonesoilterminal.com/cumplimiento/permisos/examples/dashboard.php';
        //window.location.href = 'http://localhost/cumplimiento/permisos/examples/dashboard.php';
      } else {
        // Mostrar el mensaje de error general si la respuesta es de fallo
        $('#error-message').text(response).show(); // Mostrar el mensaje de error
      }
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error); // Mostrar errores en consola
    }
  });
}


function cerrarSesion() {

  $.ajax({
    url: '../admin_users/logout.php',
    type: 'POST',
    success: function (response) {

      if (response.trim() === 'Sesion cerrada') {

        window.location.href = '../login.php';

      } else {

        Swal.fire(
          'Error',
          'No se pudo cerrar la sesión',
          'error'
        );

      }
    },
    error: function () {

      Swal.fire(
        'Error',
        'Error de comunicación con el servidor',
        'error'
      );

    }
  });

}








function crear_usuario() {
  var formData = {
    username: $('#username').val().trim(),
    password: $('#password').val().trim(),
    confirmPassword: $('#confirmPassword').val().trim(),
    rol: $('#rol').val()
  };

  console.log("Usuario:", formData.username);
  console.log("Contraseña:", formData.password);
  console.log("Confirmación:", formData.confirmPassword);
  console.log("Rol:", formData.rol);

  // Limpiar mensajes de error
  $('#usernameError, #passwordError, #confirmPasswordError, #rolError, #error-message').hide();

  // Validación de campos vacíos
  if (!formData.username) {
    $('#usernameError').text('El usuario es obligatorio').show();
    return;
  }

  if (!formData.password) {
    $('#passwordError').text('La contraseña es obligatoria').show();
    return;
  }

  if (!formData.rol) {
    $('#rolError').text('Debe seleccionar un rol').show();
    return;
  }

  if (formData.password !== formData.confirmPassword) {
    $('#confirmPasswordError').text('Las contraseñas no coinciden').show();
    return;
  }

  // Validar que la contraseña contenga al menos una mayúscula y un número
  var passwordRegex = /^(?=.*[A-Z])(?=.*\d).+$/;
  if (!passwordRegex.test(formData.password)) {
    $('#passwordError').text('La contraseña debe contener al menos una mayúscula y un número').show();
    return;
  }

  console.log("Validación de contraseña pasada.");

  // Enviar datos con AJAX
  $.ajax({
    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/login/create_user.php',
    type: 'POST',
    data: formData,
    success: function (response) {
      console.log("Respuesta del servidor:", response);
      if (response.trim() === "Usuario creado exitosamente") {
        Swal.fire({
          icon: 'success',
          title: 'Usuario creado exitosamente',
          showConfirmButton: false,
          timer: 1500
        });
        setTimeout(() => window.location.href = 'https://apps.melonesoilterminal.com/cumplimiento/permisos/examples/dashboard.php', 1500);
      } else {
        $('#error-message').text(response).show();
      }
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX:', error);
    }
  });
}







function guardarBarcaza() {

  var formData = {
    nombre: $('#nombre_new').val(),
    tipo_barcaza: $('#tipo_barcaza_new').val(),
    empresa: $('#compania_new').val(),
    categoria: "Barcazas",
  };


  if (formData.nombre === "" || formData.tipo_barcaza === "" || formData.empresa === "") {

    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",
    });

    return;

  }


  // Mostrar cargando
  Swal.fire({
    title: 'Guardando información...',
    text: 'Por favor espere mientras se registra la barcaza y se envía la notificación.',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });


  $.ajax({

    //url: 'http://localhost/cumplimiento/permisos/php_functions/barcaza/nueva_barcaza.php',
    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/barcaza/nueva_barcaza.php',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(formData),


    success: function (response) {

      console.log("Respuesta del servidor:", response);


      // cerrar loading
      Swal.close();


      Swal.fire({

        title: "Nueva Barcaza agregada con éxito",
        text: "La notificación fue creada correctamente.",
        icon: "success",
        timer: 2500,
        showConfirmButton: false

      });


      $('#formularioAgregarBarcaza')[0].reset();

      $('#modalAgregarBarcaza').modal('hide');


      setTimeout(function () {

        location.reload();

      }, 3000);


    },


    error: function (error) {

      Swal.close();


      Swal.fire({

        icon: "error",
        title: "Error",
        text: "No se pudo guardar la información."

      });


      console.error(error);

    }


  });

}

function eliminarBarcaza(id) {

  // Confirmar antes de realizar la eliminación
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¡No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí, bórralo!",
    cancelButtonText: "Cancelar"

  }).then((result) => {


    if (result.isConfirmed) {


      // Mostrar cargando
      Swal.fire({
        title: 'Eliminando...',
        text: 'Por favor espere mientras se procesa la eliminación y se envía la notificación.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });


      // Solicitud AJAX para eliminar
      $.ajax({

        //url: 'http://localhost/cumplimiento/permisos/php_functions/barcaza/eliminar_barcaza.php',
        url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/barcaza/eliminar_barcaza.php',

        type: 'POST',

        data: {
          action: 'eliminar',
          id: id
        },


        success: function (response) {


          console.log("ID eliminado:", id);
          console.log("Respuesta servidor:", response);


          // Cerrar loading
          Swal.close();


          // Mensaje éxito
          Swal.fire({

            title: "¡Eliminado!",
            text: "Documento eliminado con éxito y notificación creada.",
            icon: "success",
            timer: 2500,
            showConfirmButton: false

          });


          // Recargar después del mensaje
          setTimeout(function () {

            location.reload();

          }, 3000);



        },


        error: function (error) {


          Swal.close();


          Swal.fire({

            title: "Error",
            text: "No se pudo eliminar el documento.",
            icon: "error"

          });


          console.error('Error en la solicitud AJAX:', error);


        }


      });


    }


  });


}


// Función para guardar cambios (puedes implementar según tus necesidades)
function guardarCambios() {

  var formData = {
    id: $('#id_barcaza').val(),
    barcaza: $('#barcaza').val(),
    tipo_barcaza: $('#tipo_barcaza').val(),
    empresa_barcaza: $('#empresa_barcaza').val(),
    permiso_sne: $('#permisoSNE').val(),
    exencion_acp: $('#exencion_acp').val(),
    licencia_amp: $('#licencia_amp').val(),
    patente_navegacion: $('#patente_navegacion').val(),
    ITC: $('#ITC').val(),
    PYI: $('#PYI').val(),
    CLC: $('#CLC').val(),
    CLBC: $('#CLBC').val(),
    COC: $('#COC').val(),
    safety_equipment: $('#safety_equipment').val(),
    safety_radio: $('#safety_radio').val(),
    safety_construction: $('#safety_construction').val(),
    loadline: $('#loadline').val(),
    SMC: $('#SMC').val(),
    DOC: $('#DOC').val(),
    ISPPC: $('#ISPPC').val(),
    IAPP: $('#IAPP').val(),
    IOPP: $('#IOPP').val()
  };

  console.log(formData);

  // Hacer una solicitud AJAX para guardar los cambios en el servidor
  $.ajax({

    //url: 'http://localhost/cumplimiento/permisos/php_functions/barcaza/guardar_cambios_barcaza.php',
    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/barcaza/guardar_cambios_barcaza.php',
    type: 'POST',
    data: formData,
    success: function (response) {
      // Mostrar SweetAlert2 con un pequeño retraso
      Swal.fire({
        title: "Datos actualizado con exito",
        icon: "success",
        draggable: true
      });
      // Opcional: Recargar después de la alerta
      setTimeout(function () {
        location.reload();
      }, 1500);  // Recarga después de 1.5 segundos (ajusta el tiempo según lo necesites)
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error);
    }
  });

}


//-----------> Agregar a nuevo Capitan--------->// 

function guardarCapitanes() {

  // Obtener datos del formulario
  var formData = {

    nombre: $('#nombre_new').val(),
    nombre_empresa: $('#nombre_empresa').val(),
    categoria: "Capitanes",

  };


  // Validación
  if (formData.nombre === "" || formData.nombre_empresa === "") {


    Swal.fire({

      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",

    });


    return;

  }



  // Mostrar carga
  Swal.fire({

    title: 'Guardando información...',
    text: 'Por favor espere mientras se registra el capitán y se envía la notificación.',
    allowOutsideClick: false,
    allowEscapeKey: false,

    didOpen: () => {

      Swal.showLoading();

    }

  });



  $.ajax({


    //url: 'http://localhost/cumplimiento/permisos/php_functions/capitanes/nuevos_capitanes.php',
    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/capitanes/nuevos_capitanes.php',


    type: 'POST',

    contentType: 'application/json',

    data: JSON.stringify(formData),



    success: function (response) {


      console.log("Respuesta servidor:", response);



      // Cerrar loading
      Swal.close();



      Swal.fire({

        title: "Nuevo capitán agregado con éxito",
        text: "La notificación fue creada correctamente.",
        icon: "success",
        timer: 2500,
        showConfirmButton: false

      });



      // Limpiar formulario
      $('#formularioAgregarCapitanes')[0].reset();


      // Ocultar modal
      $('#modalAgregarCapitanes').modal('hide');



      setTimeout(function () {

        location.reload();

      }, 3000);



    },


    error: function (error) {


      Swal.close();



      Swal.fire({

        icon: "error",
        title: "Error",
        text: "No se pudo guardar la información."

      });



      console.error('Error en la solicitud AJAX:', error);



    }


  });


}

// Guardar cambio de capitanes de barcazas

// Función para guardar cambios (puedes implementar según tus necesidades)
function guardarCambiosCapitanes() {

  var formData = {

    id: $('#id').val(),
    nombre: $('#nombre').val(),
    nombre_empresa: $('#nombre_empresa').val(),
    exencion_acp: $('#exencion_acp').val(),
    licencia_amp: $('#licencia_amp').val(),
    max_grt: $('#max_grt').val(),
    embarcaciones: $('#embarcaciones').val()

  };

  alert(formData.embarcaciones);

  // Hacer una solicitud AJAX para guardar los cambios en el servidor
  $.ajax({

    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/capitanes/guardar_cambios_capitanes.php',
    //url: 'http://localhost/cumplimiento/permisos/php_functions/capitanes/guardar_cambios_capitanes.php',
    type: 'POST',
    data: formData,
    success: function (response) {
      console.log(response);
      Swal.fire({
        title: "Datos actualizado con exito",
        icon: "success",
        draggable: true
      });

      setTimeout(function () {
        location.reload();
      }, 3500);
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error);
    }
  });

}


// ------->  Guadar datos de Tanqueros 
function guardarTanquero() {
  // Obtener los datos del formulario
  var formData = {
    nombre: $('#nombre_new').val(),
    empresa: $('#compania_new').val(),
    categoria: "Tanqueros",

  };

  if (formData.nombre === "" || formData.empresa === "") {

    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",
    });
  } else {

    $.ajax({
      url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/tanquero/nuevo_tanquero.php',
      type: 'POST',
      contentType: 'application/json', // Agregado para indicar que estás enviando JSON
      data: JSON.stringify(formData),
      success: function (response) {
        Swal.fire({
          title: "Nuevo Tanquero agregado con éxito y notificación creada.",
          icon: "success",
          draggable: true
        });

        $('#formularioAgregarTanquero')[0].reset();
        $('#modalAgregarTanquero').modal('hide');

        setTimeout(function () {
          location.reload();
        }, 3500);

      },
      error: function (error) {
        console.error('Error en la solicitud AJAX: ' + error);
      }
    });


  }




}

function guardarCambiosTanqueros() {

  var formData = {
    id: $('#id').val(),
    nombre: $('#nombre').val(),
    nombre_empresa: $('#nombre_empresa').val(),
    COC: $('#COC').val(),
    PYI: $('#PYI').val(),
    CLC: $('#CLC').val(),
    CLBC: $('#CLBC').val(),
    safety_equipment: $('#safety_equipment').val(),
    safety_radio: $('#safety_radio').val(),
    safety_construction: $('#safety_construction').val(),
    loadline: $('#loadline').val(),
    IOPP: $('#IOPP').val(),
    SMC: $('#SMC').val(),
    DOC: $('#DOC').val(),
    ISPPC: $('#ISPPC').val(),
    issc: $('#issc').val(),
    IAPP: $('#IAPP').val(),
  };

  console.log(formData);

  // Hacer una solicitud AJAX para guardar los cambios en el servidor
  $.ajax({

    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/tanquero/guardar_cambios_tanquero.php',
    type: 'POST',
    data: formData,
    success: function (response) {

      Swal.fire({
        title: "Datos actualizado con exito",
        icon: "success",
        draggable: true
      });

      setTimeout(function () {
        location.reload();
      }, 3500);
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error);
    }
  });

}

// -----------------> Agregar datos Inspectores <-----------------// 


function guardarInspectores() {

  // Obtener datos del formulario
  var formData = {

    nombre: $('#nombre_new').val(),
    empresa: $('#compania_new').val(),
    categoria: "Inspectores",

  };


  // Validación
  if (formData.nombre === "" || formData.empresa === "") {


    Swal.fire({

      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",

    });


    return;

  }



  // Mostrar carga
  Swal.fire({

    title: 'Guardando información...',
    text: 'Por favor espere mientras se registra el inspector y se envía la notificación.',
    allowOutsideClick: false,
    allowEscapeKey: false,

    didOpen: () => {

      Swal.showLoading();

    }

  });



  $.ajax({


    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/inspectores/nuevo_inspectores.php',

    type: 'POST',

    contentType: 'application/json',

    data: JSON.stringify(formData),



    success: function (response) {


      console.log("Respuesta servidor:", response);



      // Cerrar loading
      Swal.close();



      // Mensaje éxito
      Swal.fire({

        title: "Nuevo inspector agregado con éxito",
        text: "La notificación fue creada correctamente.",
        icon: "success",
        timer: 2500,
        showConfirmButton: false

      });



      // Limpiar formulario
      $('#formularioAgregarInspectores')[0].reset();



      // Ocultar modal
      $('#modalAgregarInspectores').modal('hide');



      setTimeout(function () {

        location.reload();

      }, 3000);



    },


    error: function (error) {


      Swal.close();



      Swal.fire({

        icon: "error",
        title: "Error",
        text: "No se pudo guardar la información."

      });



      console.error('Error en la solicitud AJAX:', error);



    }


  });



}


function guardarCambiosInspectores() {

  var formData = {
    id: $('#id_barcaza').val(),
    inspector: $('#inspector').val(),
    nombre_empresa: $('#nombre_empresa').val(),
    cedula_vencimiento: $('#cedula_vencimiento').val(),
    numero_cedula: $('#numero_cedula').val(),
    Inspector_P_A: $('#Inspector_P_A').val(),
    Aviso_Entrada_Seguro: $('#Aviso_Entrada_Seguro').val()
  };

  if (!formData.inspector || !formData.nombre_empresa || !formData.cedula_vencimiento || !formData.numero_cedula || !formData.Inspector_P_A || !formData.Aviso_Entrada_Seguro) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",
    })
  } else {
    // Hacer una solicitud AJAX para guardar los cambios en el servidor
    $.ajax({

      url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/inspectores/guardar_cambios_inspectores.php',
      type: 'POST',
      data: formData,
      success: function (response) {
        swal.fire({
          title: "Datos actualizado con exito",
          icon: "success",
          draggable: true
        })

        setTimeout(function () {
          location.reload();
        }, 3500);
      },
      error: function (error) {
        console.error('Error en la solicitud AJAX: ' + error);
      }
    });

  }





}



/// -------------------> CIA INSPECCIONES <-------------------///

function guardarCia_Inspeccion() {

  // Obtener datos del formulario
  var formData = {

    nombre: $('#nombre_new').val(),
    empresa: $('#compania_new').val(),
    categoria: "Cia_Inspeccion",

  };


  // Validación
  if (formData.nombre === "" || formData.empresa === "") {


    Swal.fire({

      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",

    });


    return;

  }



  // Mostrar carga
  Swal.fire({

    title: 'Guardando información...',
    text: 'Por favor espere mientras se registra la CIA de inspección y se envía la notificación.',
    allowOutsideClick: false,
    allowEscapeKey: false,

    didOpen: () => {

      Swal.showLoading();

    }

  });



  $.ajax({


    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/cia_inspeccion/nuevo_cia_inspeccion.php',

    type: 'POST',

    contentType: 'application/json',

    data: JSON.stringify(formData),



    success: function (response) {


      console.log("Respuesta servidor:", response);



      // Cerrar loading
      Swal.close();



      // Mensaje éxito
      Swal.fire({

        title: "Nueva CIA DE INSPECCIÓN agregada con éxito",
        text: "La notificación fue creada correctamente.",
        icon: "success",
        timer: 2500,
        showConfirmButton: false

      });



      // Limpiar formulario
      $('#formularioAgregarCiaInspeccion')[0].reset();



      // Ocultar modal
      $('#modalAgregarCiaInspeccion').modal('hide');



      // Recargar
      setTimeout(function () {

        location.reload();

      }, 3000);



    },


    error: function (error) {


      Swal.close();



      Swal.fire({

        icon: "error",
        title: "Error",
        text: "No se pudo guardar la información."

      });



      console.error('Error en la solicitud AJAX:', error);



    }


  });



}

function guardarCambiosCiaInspeccion() {

  var formData = {
    id: $('#id_barcaza').val(),
    nombre: $('#nombre').val(),
    nombre_empresa: $('#nombre_empresa').val(),
    CIA_AMP: $('#CIA_AMP').val(),
    CIA_CNA_1: $('#CIA_CNA_1').val(),
    CIA_CNA_2: $('#CIA_CNA_2').val(),
    CIA_SNE_1: $('#CIA_SNE_1').val(),
    CIA_SNE_2: $('#CIA_SNE_2').val(),
    CIA_POLIZA: $('#CIA_POLIZA').val()
  };

  console.log(formData);



  // Hacer una solicitud AJAX para guardar los cambios en el servidor
  $.ajax({

    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/cia_inspeccion/guardar_cambios_cia_inspeccion.php',
    type: 'POST',
    data: formData,
    success: function (response) {
      Swal.fire({
        title: "Datos actualizado con exito",
        icon: "success",
        draggable: true
      })

      setTimeout(function () {
        location.reload();
      }, 3500);
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error);
    }
  });

}



/// -----------------> REMOLCADORES <-------------------///

function guardarRemolcadores() {

  // Obtener datos del formulario
  var formData = {

    nombre: $('#nombre').val(),
    empresa: $('#empresa').val(),
    categoria: "Remolcadores",

  };


  // Validación
  if (formData.nombre === "" || formData.empresa === "") {


    Swal.fire({

      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",

    });

    return;

  }



  // Mostrar carga
  Swal.fire({

    title: 'Guardando información...',
    text: 'Por favor espere mientras se registra el remolcador y se envía la notificación.',
    allowOutsideClick: false,
    allowEscapeKey: false,

    didOpen: () => {

      Swal.showLoading();

    }

  });



  $.ajax({


    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/remolcadores/nuevo_remolcador.php',

    type: 'POST',

    contentType: 'application/json',

    data: JSON.stringify(formData),



    success: function (response) {


      console.log("Respuesta servidor:", response);



      // Cerrar loading
      Swal.close();



      // Mensaje éxito
      Swal.fire({

        title: "Nuevo remolcador agregado con éxito",
        text: "La notificación fue creada correctamente.",
        icon: "success",
        timer: 2500,
        showConfirmButton: false

      });



      // Limpiar formulario
      $('#formularioAgregarRemolcadores')[0].reset();



      // Ocultar modal
      $('#modalAgregarRemolcadores').modal('hide');



      setTimeout(function () {

        location.reload();

      }, 3000);



    },


    error: function (error) {


      Swal.close();



      Swal.fire({

        icon: "error",
        title: "Error",
        text: "No se pudo guardar la información."

      });



      console.error('Error en la solicitud AJAX:', error);



    }


  });


}


function guardarCambiosRemolcadores() {

  var formData = {
    id: $('#id').val(),
    nombre: $('#nombre').val(),
    nombre_empresa: $('#nombre_empresa').val(),
    licencia_amp: $('#licencia_amp').val(),
    COC: $('#COC').val(),
    REMOL_POLIZA: $('#REMOL_POLIZA').val()
  };

  // Hacer una solicitud AJAX para guardar los cambios en el servidor
  $.ajax({
    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/remolcadores/guardar_cambios_remolcador.php',
    type: 'POST',
    data: formData,
    success: function (response) {
      swal.fire({
        title: "Datos actualizado con exito",
        icon: "success",
        draggable: true
      })

      setTimeout(function () {
        location.reload();
      }, 3500);
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error);
    }
  });

}


/// -----------------> PILOTOS <-------------------/// 

function guardarPilotos() {

  // Obtener datos del formulario
  var formData = {

    nombre: $('#nombre').val(),
    empresa: $('#empresa').val(),
    categoria: "Pilotos",

  };


  // Validación
  if (formData.nombre === "" || formData.empresa === "") {

    Swal.fire({

      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",

    });

    return;

  }



  // Mostrar carga
  Swal.fire({

    title: 'Guardando información...',
    text: 'Por favor espere mientras se registra el piloto y se envía la notificación.',
    allowOutsideClick: false,
    allowEscapeKey: false,

    didOpen: () => {

      Swal.showLoading();

    }

  });



  $.ajax({


    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/pilotos/nuevo_pilotos.php',

    type: 'POST',

    contentType: 'application/json',

    data: JSON.stringify(formData),



    success: function (response) {


      console.log("Respuesta servidor:", response);



      // Cerrar loading
      Swal.close();



      // Mensaje éxito
      Swal.fire({

        title: "Nuevo piloto agregado con éxito",
        text: "La notificación fue creada correctamente.",
        icon: "success",
        timer: 2500,
        showConfirmButton: false

      });



      // Limpiar formulario
      $('#formularioAgregarPilotos')[0].reset();



      // Ocultar modal
      $('#modalAgregarPilotos').modal('hide');



      // Recargar
      setTimeout(function () {

        location.reload();

      }, 3000);



    },


    error: function (error) {


      Swal.close();



      Swal.fire({

        icon: "error",
        title: "Error",
        text: "No se pudo guardar la información."

      });



      console.error('Error en la solicitud AJAX:', error);



    }


  });


}

function guardarCambiosPilotos() {

  var formData = {
    id: $('#id').val(),
    nombre: $('#nombre').val(),
    nombre_empresa: $('#nombre_empresa').val(),
    PILOT_LIC_OPERACION: $('#PILOT_LIC_OPERACION').val(),
    PILOT_COMPETENCIA: $('#PILOT_COMPETENCIA').val(),
    PILOT_Reporte_maniobras: $('#PILOT_Reporte_maniobras').val(),
    PILOT_Hoja_Vida: $('#PILOT_Hoja_Vida').val(),
    PILOT_Informe_Maniobras: $('#PILOT_Informe_Maniobras').val()
  };

  console.log(formData);



  // Hacer una solicitud AJAX para guardar los cambios en el servidor
  $.ajax({

    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/pilotos/guardar_cambios_pilotos.php',
    type: 'POST',
    data: formData,
    success: function (response) {
      Swal.fire({
        title: "Datos actualizado con exito",
        icon: "success",
        draggable: true
      })

      setTimeout(function () {
        location.reload();
      }, 3500);
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error);
    }
  });

}


/// -----------------> Contracts Schedule <-------------------/// 

function guardarContracts_Schedule() {
  // Obtener los datos del formulario
  var formData = {
    tank: $('#tank ').val(),
    producto: $('#producto').val(),
    cliente: $('#cliente').val()

  };

  if (formData.tank === "" || formData.producto === "" || formData.cliente === "") {

    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Todos los campos son obligatorios",
    })

  } else {

    $.ajax({
      url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/Contracts_Schedule/nuevo_Contracts_Schedule.php',
      type: 'POST',
      contentType: 'application/json', // Agregado para indicar que estás enviando JSON
      data: JSON.stringify(formData),
      success: function (response) {
        Swal.fire({
          title: "Nuevo Contracts agregado con éxito y notificación creada.",
          icon: "success",
          draggable: true
        });

        $('#formularioAgregarContracts')[0].reset();
        $('#modalAgregarContracts').modal('hide');

        setTimeout(function () {
          location.reload();
        }, 3500);

      },
      error: function (error) {
        console.error('Error en la solicitud AJAX: ' + error);
      }
    });

  }
}

function guardarCambiosContracts_Schedule() {

  var formData = {
    id: $('#id').val(),
    tank: $('#tank').val(),
    producto: $('#producto').val(),
    cliente: $('#cliente').val(),
    volumen: $('#volumen').val(),
    amendment: $('#amendment').val(),
    expiration: $('#expiration').val(),
    SNE: $('#SNE').val()
  };

  // Hacer una solicitud AJAX para guardar los cambios en el servidor
  $.ajax({

    url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/Contracts_Schedule/guardar_cambios_Contracts_Schedule.php',
    type: 'POST',
    data: formData,
    success: function (response) {
      Swal.fire({
        title: "Datos actualizado con exito",
        icon: "success",
        draggable: true
      })

      setTimeout(function () {
        location.reload();
      }, 3500);
    },
    error: function (error) {
      console.error('Error en la solicitud AJAX: ' + error);
    }
  });

}

function Delete_Contracts_Schedule(id) {
  // Confirmar antes de realizar la eliminación

  Swal.fire({
    title: "Estas seguro?",
    text: "¡No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, borralo!"
  }).then((result) => {
    if (result.isConfirmed) {
      // Hacer una solicitud AJAX para eliminar la barcaza
      $.ajax({
        url: 'https://apps.melonesoilterminal.com/cumplimiento/permisos/php_functions/Contracts_Schedule/Delete_Contracts_Schedule.php',
        type: 'POST',
        data: {
          action: 'eliminar',
          id: id
        },
        success: function (response) {

          // Puedes recargar la página o actualizar la lista de barcazas después de eliminar
          setTimeout(function () {
            location.reload();
          }, 1500);
          console.log("ID de la Contracts_Schedule a eliminar:", id);
        },
        error: function (error) {
          console.error('Error en la solicitud AJAX: ' + error);
        }
      });

      Swal.fire({
        title: "¡Eliminado!",
        text: "ha sido eliminado.",
        icon: "success"
      });
    }
  });
}



// Función para cargar barcazas por ID


























