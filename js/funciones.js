var id_user  = 0;
var tokens   = 0;
var id_rol   = 0;
var name_user   = 0;
var name_commercial_premises  = 0;
var id_commercial_premises   = 0;

var consultar  = 0;
var actualizar = 0; 
var borrar     = 0; 
var registrar  = 0;

var name_rol   = 0;

$("#alertsDropdown").click(function (e) { 
  
  var url=document.getElementById('ruta').value;
    $.ajax({
      url:''+url+'/api/notifications/read',
      type:'POST',
      data: {
          "id_user": id_user,
          "token"  : tokens,
        },
      dataType:'JSON',
      async: false,
      beforeSend: function(){
      
      },
      error: function (data) {
            
      },
      success: function(data){
        console.log(data)
        $("#count_notification").text("0")
      }
    });
});


function number_format(amount, decimals) {   
  amount += ''; // por si pasan un numero en vez de un string
  amount = parseFloat(amount.replace(/[^0-9\.]/g, ''));
  // elimino cualquier cosa que no sea numero o punto 
  decimals = decimals || 0; // por si la variable no fue fue pasada  
  // si no es un numero o es igual a cero retorno el mismo cero 
  if (isNaN(amount) || amount === 0)      
      return parseFloat(0).toFixed(decimals);     
      // si es mayor o menor que cero retorno el valor formateado como numero   
      amount = '' + amount.toFixed(decimals);   
      var amount_parts = amount.split('.'),    
      regexp = /(\d+)(\d{3})/;       
      while (regexp.test(amount_parts[0]))  
      amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2'); 
      return amount_parts.join('.');  
}

$(".monto_formato_decimales").change(function() {   

  if($(this).val() != ""){  
    $(this).val(number_format($(this).val(), 2));   
  }       
});



function inNum(monto) {
	var cantidad           = monto;
	var myNumeral          = numeral(cantidad);
    return myNumeral.value();
}



$('.select2').select2({
  width: '100%'
});

/* ------------------------------------------------------------------------------- */
    /*
        Variable para el idioma del datatable.
    */
    var idioma_espanol = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
 /* 
        Funcion para mostrar y ocultar los cuadros (div).
    */
    function cuadros(cuadroOcultar, cuadroMostrar){
        $(cuadroOcultar).slideUp("slow"); //oculta el cuadro.
        $(cuadroMostrar).slideDown("slow"); //muestra el cuadro.
    }


    /* ------------------------------------------------------------------------------- */
    /* 
        Funcion para regresar al listado.
    */
    function prev(cuadroOcultar){
        $(cuadroOcultar).slideUp("slow"); //oculta el cuadro.
        $("#cuadro1").slideDown("slow"); //muestra el cuadro.
        list();
    }
/* ------------------------------------------------------------------------------- */
    /*
        Funcion que envia los datos de los formularios.
    */
    function enviarFormulario(form, controlador, cuadro, auth = false){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:''+url+'/'+controlador+'',
                type:method,
                dataType:'JSON',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                },
                error: function (repuesta) {
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
                },
                 success: function(respuesta){
                    if (respuesta.success == false) {
                         mensajes('danger', respuesta.message);
                         $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    }else{
                        $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                        mensajes('success', respuesta.mensagge);

                        if (auth) {
                           localStorage.setItem('token', respuesta.token);  
                           localStorage.setItem('email', respuesta.email);
                           localStorage.setItem('user_id', respuesta.user_id);  
                           window.location.href = url+"/dashboard";
                        }else{

                            list(cuadro);
                        }
                       
                    }

                }

            });
        });
    }





    /* ------------------------------------------------------------------------------- */
    /*
        Funcion que envia los datos de los formularios.
    */
   function enviarFormularioPut(form, controlador, cuadro, auth = false, inputFile){
    $(form).submit(function(e){

        e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
        var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
        
        var method = $(this).attr('method'); //obtiene el method del formulario
        console.log(''+url+'/'+controlador+'/'+$("#id_edit").val())


        $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
        $.ajax({
            url:''+url+'/'+controlador+'/'+$("#id_edit").val(),
            type:method,
            dataType:'JSON',
            data:formData,
            cache:false,
                contentType:false,
                processData:false,
            beforeSend: function(){
                mensajes('info', '<span>Espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
                $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                var errores=repuesta.responseText;
                if(errores!="")
                    mensajes('danger', errores);
                else
                    mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
            },
             success: function(respuesta){
                if (respuesta.success == false) {
                     mensajes('danger', respuesta.message);
                     $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                }else{
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    mensajes('success', respuesta.mensagge);

                    if (auth) {
                       localStorage.setItem('token', respuesta.token);  
                       localStorage.setItem('email', respuesta.email);
                       localStorage.setItem('user_id', respuesta.user_id);  
                       window.location.href = url+"/dashboard";
                    }else{

                        list(cuadro);
                    }
                   
                }

            }

        });
    });
}


/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga de cambiar el status de un registro seleccionado.
        status -> valor (1, 2, n...)
        confirmButton -> activar, desactivar
    */
   function statusConfirmacion(controlador,  title, confirmButton){

    var data = {
      "id_user": id_user,
      "token"  : tokens,
    };


    swal({
        title: title,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, "+confirmButton+"!",
        cancelButtonText: "No, Cancelar!",
        closeOnConfirm: true,
        closeOnCancel: false
    },
    function(isConfirm){
        if (isConfirm) {
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            $.ajax({
                url:''+url+'/'+controlador+'/',
                type: 'GET',
                dataType: 'JSON',
              //  data: data,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando cambios, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                },
                error: function (repuesta) {
                    var errores=repuesta.responseText;
                    mensajes('danger', errores);
                },
                success: function(respuesta){
                  mensajes('success', respuesta.mensagge);
                  list();
                }
            });
        } else {
            swal("Cancelado", "Proceso cancelado", "error");
        }
    });
}




    function print_r2(data) {
        $.each(data, function(i, item){
            console.log(i);
        });
        return data;
    }

      /*
        Funcion que muestra los mensajes al usuario.
        type = [default, primary, info, warning, success, danger]
    */
    function mensajes(type, msj){
        html='<div class="alert alert-'+type+'" role="alert">';
        html+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        html+=msj;
        html+='</div>';
        return $("#alertas").html(html).css("display", "block");
    }








    function validAuth(login = false, uri = "") {
        var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        

        var token   = localStorage.getItem('token');
        var user_id = localStorage.getItem('user_id');

        var data = {
            "token"   : token,
            "user_id" : user_id
        }

        //$('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
        $.ajax({
            url:''+url+'/api/verify-token',
            type:"POST",
            data: data,
            dataType:'JSON',
            async: false,
            beforeSend: function(){
                $('#page-loader').css("display", "block");
            },
            error: function (repuesta) {
                if (!login) {
                    window.location.href = url+"/";  
                }else{
                    $('#page-loader').css("display", "none"); 
                }
            },
             success: function(respuesta){
                if (login) {  
                    window.location.href = url+"/dashboard";   
                }else{
                    $('#page-loader').css("display", "none");

                    $("#userDropdown img").attr("src", "img/usuarios/profile/"+respuesta.data_user.img_profile)
                    $("#userDropdown span").text(respuesta.data_user.nombres+" "+respuesta.data_user.apellido_p)

                    showMenu(respuesta.modulos_disponibles, respuesta.funciones, uri)

                    name_rol = respuesta.data_user.nombre_rol
                }

                $(".id_user").val(respuesta.data.id_user);
                $(".token").val(respuesta.data.token);

                id_user   = respuesta.data.id_user;
                tokens    = respuesta.data.token;
                id_rol    = respuesta.data_user.id_rol;
                name_user = `${respuesta.data_user.nombres} ${respuesta.data_user.apellido_p}`
                name_commercial_premises = respuesta.data_user.name_comercial
                id_commercial_premises = respuesta.data_user.id_commercial_premises;



            }

        });
    }



    function showMenu(modulos_disponibles, funciones, uri){
      var url=document.getElementById('ruta').value;

      var accede = false;
      var html   = "";
      $.each(modulos_disponibles, function(i, item){

        html += '<li class="nav-item" id="modulo_'+item.nombre+'">';
          html += ' <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_'+item.nombre+'" aria-expanded="true" aria-controls="collapse_'+item.nombre+'">';
            html += '<i class="'+item.icon+'"></i>';
            html += '<span>'+item.nombre+'</span>';
          html += '</a>'; 

          html += '<div id="collapse_'+item.nombre+'" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">';

            html += '<div class="bg-white py-2 collapse-inner rounded">';
              html += '<h6 class="collapse-header">'+item.nombre+':</h6>';
              $.each(funciones, function(i2, item2){
                  if(item.id_modulo == item2.id_modulo)
                    html += '<a class="collapse-item" id="nav_'+item2.route+'" href="'+item2.route+'">'+item2.nombre+'</a>'

                  if (uri == item2.route) {
                    accede = true;
                  }
              });
            html += '</div>';
          html += '</div>'
        html += '</li>';
      });

      if (!accede && uri != "dashboard") {
        alert("No tiene Permiso para acceder a esta funcion");
        window.location.href = url+"/dashboard";  
      }

      $("#options").html(html);
    }





$("#send_usuario").click(function() {


    var comprobar_reg = false;
    var comprobar_img = false;
    var comprobar_reg_string = "";
    var comprobar_reg_tab = "";
    var comprobar_reg_campo = "";
    var obj1 = ["0", "1"];
    for (index = 0; index < obj1.length; ++index) {
      item1 = obj1[index];


      var ValidImgProfile = $("#avatar-1")[0].files.length;

      if (ValidImgProfile == 0) {
        if($("#avatar-1").prop('required')){
            comprobar_img = true;
        }
      }


      $('.tab_content'+item1+' div.valid-required').each(function(){
        if($(this).find(".form-control").prop('required')){
          if($(this).find(".form-control").val() == ""){
            if(!comprobar_reg){
              comprobar_reg = true;
            //  comprobar_reg_string = $(this).find(".control-label-left").html().replace('<span class="required"></span>', "").replace('<span class="required">*</span>', "");
              comprobar_reg_tab = item1;
              comprobar_reg_campo = $(this).find(".form-control").prop("id");
            }
          }
        }
      });
    }
    var obj2 = ["11", "2", "3", "4", "5", "6"];
    for (index = 0; index < obj2.length; ++index) {
      item1 = obj2[index];
      var contador_req = 0;
      var temp_comprobar_reg_string = "";
      var temp_comprobar_reg_campo = "";
      $('.tab_content'+item1+' div.valid-required').each(function(){
        if($(this).find(".form-control").prop('required')){
          if($(this).find(".form-control").val() == "" || $(this).find(".form-control").val() == "0" || $(this).find(".form-control").val() == "accio"){
            if(!comprobar_reg){
              if(comprobar_reg_campo == ""){
               // temp_comprobar_reg_string = $(this).find(".control-label-left").html().replace('<span class="required"></span>', "").replace('<span class="required">*</span>', "");
                comprobar_reg_campo = $(this).find(".form-control").prop("id");
              }
            }
          }
          else{
            contador_req++;
          }
        }
      });

      if(!comprobar_reg){
        if(contador_req > 0){
          if(comprobar_reg_campo != ""){
            comprobar_reg = true;
            comprobar_reg_string = temp_comprobar_reg_string;
            comprobar_reg_tab = item1;
            comprobar_reg_campo = comprobar_reg_campo;
          }
        }
        else{
          $('.tab_content'+item1+' div.valid-required').each(function(){
            $(this).find(".form-control").removeAttr("required");
          });
        }
      }
    }
    
    if (comprobar_img) {
        $('.tab_content_edit0').removeClass("active in");$('#edit_tab0').removeClass("active");
        $('.tab_content_edit1').removeClass("active in");$('#edit_tab1').removeClass("active");
        $('.tab_content_edit11').removeClass("active in");$('#tab11').removeClass("active");
        $('.tab_content_edit2').removeClass("active in");$('#tab2').removeClass("active");
        $('.tab_content_edit3').removeClass("active in");$('#tab3').removeClass("active");
        $('.tab_content_edit4').removeClass("active in");$('#tab4').removeClass("active");
        $('.tab_content_edit5').removeClass("active in");$('#tab5').removeClass("active");
        $('.tab_content_edit6').removeClass("active in");$('#tab6').removeClass("active");
        $('.tab_content80').removeClass("active in");$('#tab80').removeClass("active");
        
        $('.tab_content_edit0').addClass("active in show");$('#tab0').addClass("active");

    }else if(comprobar_reg){
        
      $('.tab_content0').removeClass("active in");$('#tab0').removeClass("active");
      $('.tab_content1').removeClass("active in");$('#tab1').removeClass("active");
      $('.tab_content11').removeClass("active in");$('#tab11').removeClass("active");
      $('.tab_content2').removeClass("active in");$('#tab2').removeClass("active");
      $('.tab_content3').removeClass("active in");$('#tab3').removeClass("active");
      $('.tab_content4').removeClass("active in");$('#tab4').removeClass("active");
      $('.tab_content5').removeClass("active in");$('#tab5').removeClass("active");
      $('.tab_content6').removeClass("active in");$('#tab6').removeClass("active");
      $('.tab_content'+comprobar_reg_tab).addClass("active in show");$('#tab'+comprobar_reg_tab).addClass("active");
      //$('#'+comprobar_reg_campo).focus();
      //alert("El campo "+comprobar_reg_string+" es obligatorio.");
    }
  });






/* ------------------------------------------------------------------------------- */
    /*
        Funcion para agregar options a los selects
    */
    function agregarOptions(select, value, text){
        $(select).append($('<option>', { 
            value: value,
            text : text
        }));
        $(select + ' :nth-child(2)').prop('selected', true);
    }

    function eliminarOptions(select){
        $('#' + select).children('option:not(:first)').remove();
    }

    
/* ------------------------------------------------------------------------------- */
  /*
    Funcion con codigo para generar un checkbox
  */
  function agregarCheckbox(id, campo, checked){

    
    if(checked == 0)
      return "<label class='container-check'><input type='checkbox' name='" + campo + "' class='checkitem chk-col-blue' id='" + campo + id + "' value='0'><span class='checkmark'></span><label for='" + campo + id + "'></label></label>";
    else if (checked == 1)
      return "<label class='container-check'><input type='checkbox' name='" + campo + "' class='checkitem chk-col-blue' id='" + campo + id + "' value='0' checked><span class='checkmark'></span><label for='" + campo + id + "'></label></label>";
  }







  function warning(title){
        swal({
            title: title,
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            closeOnConfirm: true
        });
    }


    /* ------------------------------------------------------------------------------- */
  /*
    Funcion que verificar checkbox
  */
  function verificarCheckbox(checkbox){
    if (document.getElementById(checkbox).checked)
      return 1
    else
      return 0
  }



  function verifyPersmisos(id_usuario, token, route){


    var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        

        var token   = token;
        var user_id = id_usuario;

        var data = {
            "token"   : token,
            "user_id" : user_id,
            "route"   : route
        }

        //$('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
        $.ajax({
            url:''+url+'/api/verify-permiso',
            type:"GET",
            data: data,
            dataType:'JSON',
            async: false,
            beforeSend: function(){
               
            },
            error: function (repuesta) {
                
            },
            success: function(respuesta){
              consultar  = respuesta.detallada
              actualizar = respuesta.actualizar
              borrar     = respuesta.eliminar
              registrar  = respuesta.registrar

              if(registrar == 0){
                $("#btn-new").css("display" ,"none")
              }
            }

        });
  }


  function calcularEdad(fecha) {
      var hoy = new Date();
      var cumpleanos = new Date(fecha);
      var edad = hoy.getFullYear() - cumpleanos.getFullYear();
      var m = hoy.getMonth() - cumpleanos.getMonth();

      if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
          edad--;
      }

      return edad;
 }




 function GetCity(select){
				
  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/city',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
      },
    dataType:'JSON',
    async: false,
    beforeSend: function(){
    // mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
    },
    error: function (data) {
      //mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
    },
    success: function(data){
      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "null",
        text : "Seleccione"
      }));
      $.each(data, function(i, item){
        if (item.status == 1) {
          $(select).append($('<option>',
          {
            value: item.id_city,
            text : item.nombre
          }));
        }
      });

    }
  });
}



function GetClinic(select){

  console.log("adasd")
  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/clinic',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
      },
    dataType:'JSON',
    async: false,
    beforeSend: function(){
    // mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
    },
    error: function (data) {
      //mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
    },
    success: function(data){
      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "",
        text : "Seleccione"
      }));
      $.each(data, function(i, item){
        if (item.status == 1) {
          $(select).append($('<option>',
          {
            value: item.id_clinic,
            text : item.nombre
          }));
        }
      });

    }
  });
}





function GetClinicByCity(select, id_city){
  
    var url=document.getElementById('ruta').value;
    $.ajax({
      url:''+url+'/api/clinic',
      type:'GET',
      data: {
          "id_user": id_user,
          "token"  : tokens,
        },
      dataType:'JSON',
      async: false,
      beforeSend: function(){
      // mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
      },
      error: function (data) {
        //mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
      },
      success: function(data){
        $(select+" option").remove();
        $(select).append($('<option>',
        {
          value: "",
          text : "Seleccione"
        }));
        $.each(data, function(i, item){
          if (item.status == 1) {

            //if(id_city == item.id_city){
                $(select).append($('<option>',
                {
                  value: item.id_clinic,
                  text : item.nombre
                }));
           // }
            
          }
        });

        $(select).removeAttr("disabled")
      }
    });
}




function GetAsesoras(select){
  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/get-asesoras',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
      },
    dataType:'JSON',
    async: false,
    beforeSend: function(){
    // mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
    },
    error: function (data) {
      //mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
    },
    success: function(data){
      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "",
        text : "Seleccione"
      }));
   
      $.each(data, function(i, item){
        if (item.status == 1) {
          
          $(select).append($('<option>',
          {
            value: item.id,
            text : item.nombres+" "+item.apellido_p+" "+item.apellido_m,
            selected: item.id == id_user ? true : false
          }));

          if(item.id == id_user){
            $(select+" option").not(':selected').remove();
              return false;
          }

        }
      });

    }
  });
}

function GetAsesorasValoracion(select){
  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/get-asesoras',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
      },
    dataType:'JSON',
    async: false,
    beforeSend: function(){
    // mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
    },
    error: function (data) {
      //mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
    },
    success: function(data){
      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "",
        text : "Seleccione"
      }));
   
      $.each(data, function(i, item){
        if (item.status == 1) {
          
          $(select).append($('<option>',
          {
            value: item.id,
            text : item.nombres+" "+item.apellido_p+" "+item.apellido_m,
          }));

        }
      });

    }
  });
}

function GetAsesorasbyBusisnessLine(line_business, asesoras){

  $(line_business).unbind().change(function (e) { 
   
    var id_line_business = $(this).val()
    var url=document.getElementById('ruta').value;
    $.ajax({
      url:''+url+'/api/get-asesoras-business-line/'+id_line_business,
      type:'GET',
      data: {
          "id_user": id_user,
          "token"  : tokens,
        },
      dataType:'JSON',
      async: false,
      beforeSend: function(){
      // mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
      },
      error: function (data) {
        //mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
      },
      success: function(data){
        $(asesoras+" option").remove();
        $(asesoras).append($('<option>',
        {
          value: "",
          text : "Seleccione"
        }));
     
        $.each(data, function(i, item){
          if (item.status == 1) {
            
            $(asesoras).append($('<option>',
            {
              value: item.id,
              text : item.nombres+" "+item.apellido_p+" "+item.apellido_m,
              selected: item.id == id_user ? true : false
            }));
  
            if(item.id == id_user){
              //$(asesoras+" option").not(':selected').remove();
                //return false;
            }
  
          }
        });
  
      }
    });
  });
  
}












function GetBusinessLine(select){
  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/line-business',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
      },
    dataType:'JSON',
    async: false,
    beforeSend: function(){
    
    },
    error: function (data) {
           
    },
    success: function(data){
      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "",
        text : "Seleccione"
      }));
      $.each(data, function(i, item){
        if (item.status == 1) {
          $(select).append($('<option>',
          {
            value: item.id_line,
            text : item.nombre_line
          }));
        }
      });

    }
  });
}







function getPacientes(select, select_default = false){
 console.log(select_default)

 
 $(select+" option").remove();

 
  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/clients',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
        "rol"    : name_rol
      },
    dataType:'JSON',
  //  async: false,
    beforeSend: function(){

      mensajes('info', '<span>Cargando Pacientes, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');


      $(select).attr("disabled", "disabled");
    },
    error: function (data) {
    //  $(select).attr("disabled", "disabled");
    },
    success: function(data){
      $(".alert").css("display", "none")
      $(select).removeAttr("disabled");

      $(select).focus()

      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "",
        text : "Seleccione"
      }));
      $.each(data, function(i, item){
        if (item.status == 1) {
          
            $(select).append($('<option>',
            {
              value: item.id,
              text : item.name,
              selected : select_default == item.id ? true : false
            }));
         
        }
      });

    }
  });
}




function GetUsers(select, select_default){

  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/user',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
      },
    dataType:'JSON',
   // async: false,
    beforeSend: function(){
      
    },
    error: function (data) {
           
    },
    success: function(data){
      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "",
        text : "Seleccione"
      }));
      $.each(data, function(i, item){
        if (item.status == 1) {
          $(select).append($('<option>',
          {
            value: item.id,
            text : item.nombres+" "+item.apellido_p+" "+item.apellido_m,
            selected : select_default == item.id ? true : false
          }));
        }
      });

    }
  });
}













function GetCategories(select, select_default){

  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/categories',
    type:'GET',
    data: {
        "id_user": id_user,
        "token"  : tokens,
      },
    dataType:'JSON',
   // async: false,
    beforeSend: function(){
      
    },
    error: function (data) {
           
    },
    success: function(data){
      $(select+" option").remove();
      $(select).append($('<option>',
      {
        value: "",
        text : "Seleccione"
      }));
      $.each(data, function(i, item){
        if (item.status == 1) {
          $(select).append($('<option>',
          {
            value: item.id,
            text : item.name,
            selected : select_default == item.id ? true : false
          }));
        }
      });

    }
  });
}


function GetComercialPremises(select){
				
  var url=document.getElementById('ruta').value;
  $.ajax({
    url:''+url+'/api/comercial/premises',
    type:'GET',
    data: {
      "id_user": id_user,
      "token"  : tokens,
    },
    dataType:'JSON',
    async: false,
    beforeSend: function(){
    // mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
    },
    error: function (data) {
    //mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
    },
    success: function(data){
    $(select+" option").remove();
    $(select).append($('<option>',
    {
      value: "null",
      text : "Seleccione"
    }));
    $.each(data, function(i, item){
      
      $(select).append($('<option>',
      {
        value: item.id,
        text : item.name
      }));
     
    });
  
    }
  });
}


  


function eliminarTr(tr){
  $(tr).remove(); 
}








function GetNotifications(){
    
    var url=document.getElementById('ruta').value;
    $.ajax({
      url:''+url+'/api/notifications/get',
      type:'GET',
      data: {
          "id_user": id_user,
          "token"  : tokens,
        },
      dataType:'JSON',
      async: false,
      beforeSend: function(){
      
      },
      error: function (data) {
            
      },
      success: function(data){
        
        data.length > 0 ? $("#count_notification").text(data.length+"+") : ''
        
        var html = ''
        $.each(data, function (key, item) { 

          if(item.type == "task"){ var background = "#4e73df"; var icon = "fas fa-file-alt" }
          if(item.type == "queries"){ var background = "#E5F4FD"; var icon = "fas fa-file-alt" }
          if(item.type == "valuations"){ var background = "#FFAAD4"; var icon = "fas fa-file-alt" }
          if(item.type == "preanesthesias"){ var background = "#FF7F00"; var icon = "fas fa-file-alt" }
          if(item.type == "revision"){ var background = "#FF7F7F"; var icon = "fas fa-file-alt" }
          if(item.type == "surgeries"){ var background = "#7F55FF"; var icon = "fas fa-file-alt" }
          if(item.type == "prp"){ var background = "#7F55FF"; var icon = "fas fa-file-alt" }

          let href
          if(item.type == "prp"){ 
            href   = `clients/view/${item.id_event}`
            target = "_blank"
          }else{
            href   = "#"
            target = ""
          }


          html += '<a class="dropdown-item d-flex align-items-center" target="'+target+'" href="'+href+'">'
            html += '<div class="mr-3">'
              html += '<div class="icon-circle" style="background: '+background+'">'
                html += '<i class="'+icon+' text-white"></i>'
              html += '</div>'
            html += '</div>'

           html += '<div>'
            html += '<div class="small text-gray-500">'+item.fecha+'</div>'
              html += '<span class="font-weight-bold">'+item.title+'</span>'
            html += '</div>'
          html += '</a>'
        });

        $("#list-notifications").html(html);

      }
    });

}







