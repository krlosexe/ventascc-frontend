<?php 

if (!defined('BASEPATH')) exit ('No direct script access allowed');

Class Membresia_model extends CI_Model
{

    private $tabla_clientePagador = "cliente_pagador";
    private $tabla_lval       = "lval";
    private $tabla_cuenta_clientePa = "cuenta_cliente";
    private $tabla_repLegal     = "repLegal_cliente_pagador";
    private $tabla_datosPersonales  = "datos_personales";
    private $tabla_contacto     = "contacto";
    private $tabla_paquetes = "paquetes";
   
    /*
    *   Listado de clientes segun rfc
    */
    public function consultarClientePagadorRfc($rfc,$tipo_persona){
        //-----------------------------------------------------------------------------------
        //--Consulto datos personales....
        $res_datos_personales = $this->mongo_db->where(array('eliminado'=>false,'rfc_datos_personales'=>$rfc))->get('datos_personales');
        $listado = [];
        $valores = [];
        /*var_dump($rfc);
        die('');*/
        if(count($res_datos_personales)==0){
            //aqui va un error si no existe en datos personales
             $listado[0]["error"] = true;
        }else {
            foreach ($res_datos_personales as $clave_dt => $valor_dt) {
                
                $valores["id_datos_personales"] = (string)$valor_dt["_id"]->{'$id'};
                
                $valores["id_contacto"] = (string)$valor_dt["id_contacto"];
                $valores["nombre_datos_personales"] = $valor_dt["nombre_datos_personales"];

                (isset($valor_dt["apellido_p_datos_personales"]))? $valores["apellido_p_datos_personales"] = $valor_dt["apellido_p_datos_personales"]: $valores["apellido_p_datos_personales"] ="";

                (isset($valor_dt["apellido_m_datos_personales"]))? $valores["apellido_m_datos_personales"] = $valor_dt["apellido_m_datos_personales"]: $valores["apellido_m_datos_personales"] ="";
                
                (isset($valor_dt["curp_datos_personales"]))? $valores["curp_datos_personales"] = $valor_dt["curp_datos_personales"]: $valores["curp_datos_personales"] = "";
                
                (isset($valor_dt["rfc_datos_personales"]))? $valores["rfc_datos_personales"] = $valor_dt["rfc_datos_personales"]:$valores["rfc_datos_personales"] = "";
                
                (isset($valor_dt["genero_datos_personales"]))? $valores["genero_datos_personales"] = $valor_dt["genero_datos_personales"]:$valores["genero_datos_personales"] ="";

                (isset($valor_dt["fecha_nac_datos_personales"]))? $valores["fecha_nac_datos_personales"] = $valor_dt["fecha_nac_datos_personales"]: $valores["fecha_nac_datos_personales"] ="";
                
                (isset($valor_dt["edo_civil_datos_personales"])) ? $valores["edo_civil_datos_personales"] = $valor_dt["edo_civil_datos_personales"]: $valores["edo_civil_datos_personales"] ="";
                
                (isset($valor_dt["nacionalidad_datos_personales"])) ? $valores["nacionalidad_datos_personales"] = $valor_dt["nacionalidad_datos_personales"]:$valores["nacionalidad_datos_personales"] ="";
                //-------------------------------------------------------------------------------
                #Consulto cliente pagador
                //
                $res_cliente_pagador = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_datos_personales'=>$valores["id_datos_personales"],"tipo_persona_cliente"=>$tipo_persona))->get($this->tabla_clientePagador);
                /*var_dump($tipo_persona);
                var_dump($res_cliente_pagador);
                die('');*/
                //var_dump($res_cliente_pagador);die('');
                if(count($res_cliente_pagador)>0){
                //----------------------------------
                    foreach ($res_cliente_pagador as $clave => $valor) {
                        $valores["id_cliente"] = (string)$valor["_id"]->{'$id'};

                        $valores["empresa_pertenece"] =  $valor["empresa_pertenece"];


                        (isset($valor["actividad_e_cliente"]))? $valores["actividad_e_cliente"] = $valor["actividad_e_cliente"]:$valores["actividad_e_cliente"] = "";
                        
                        $valores["rfc_img"] = $valor["rfc_img"];
                        
                        (isset($valor["pais_cliente"]))? $valores["pais_cliente"] = $valor["pais_cliente"]:$valores["pais_cliente"] = "";
                        
                        $valores["tipo_persona_cliente"] = $valor["tipo_persona_cliente"];
                        $valores["dominio_fiscal_img"] = $valor["dominio_fiscal_img"];
                        
                        (isset($valor["acta_constitutiva"]))? $valores["acta_constitutiva"] = $valor["acta_constitutiva"]:$valores["acta_constitutiva"] = "";

                        (isset($valor["acta_img"])) ? $valores["acta_img"] = $valor["acta_img"]: $valores["acta_img"] = "";
                        (isset($valor["giro_mercantil"])) ? $valores["giro_mercantil"] = $valor["giro_mercantil"]:$valores["giro_mercantil"] = "";

                        (isset($valor["pasaporte"])) ? $valores["pasaporte"] = $valor["pasaporte"]:$valores["pasaporte"] = "";
                        (isset($valor["tipo_cliente"])) ? $valores["tipo_cliente"] = $valor["tipo_cliente"]: $valores["tipo_cliente"]="";

                        $valores["id_datos_personales"] = new MongoDB\BSON\ObjectId($valor["id_datos_personales"]);
                        //---
                        #cambio para mostrar la imagen del cliente abril 2019
                       (isset($valor["imagenCliente"]))? $valores["imagenCliente"] = $valor["imagenCliente"]:$valores["imagenCliente"] = "default-img.png";
                        //---
                        //-----------------------------------------------------------------------------------
                        //--Consulto contactos
                        $id_contacto = new MongoDB\BSON\ObjectId($valor["id_contacto"]);
                        $res_contacto = $this->mongo_db->where(array("_id"=>$id_contacto))->get($this->tabla_contacto);
                        foreach ($res_contacto as $clave_contacto => $valor_contacto) {
                            $valores["id_codigo_postal"] = $valor_contacto["id_codigo_postal"];
                            $valores["telefono_principal_contacto"] = $valor_contacto["telefono_principal_contacto"];
                            (isset($valor_contacto["correo_contacto"]))? $valores["correo_contacto"] = $valor_contacto["correo_contacto"]:$valores["correo_contacto"] = "";

                            (isset($valor_contacto["telefono_movil_contacto"]))? $valores["telefono_movil_contacto"]=$valor_contacto["telefono_movil_contacto"]: $valores["telefono_movil_contacto"] ="";
                            
                            (isset($valor_contacto["direccion_contacto"])) ? $valores["direccion_contacto"] = $valor_contacto["direccion_contacto"]:$valores["direccion_contacto"] ="";

                            (isset($valor_contacto["calle_contacto"])) ? $valores["calle_contacto"] = $valor_contacto["calle_contacto"]:$valores["calle_contacto"] = "";

                            (isset($valor_contacto["exterior_contacto"]))? $valores["exterior_contacto"] = $valor_contacto["exterior_contacto"]:$valores["exterior_contacto"] ="";

                            (isset($valor_contacto["interior_contacto"])) ? $valores["interior_contacto"] = $valor_contacto["interior_contacto"]:$valores["interior_contacto"] ="";

                            #$valores["status"] = $valor_contacto["status"];
                        }
                        //--------------------------------------------------------------
                        //-----------------------------------------------------------------------------------
                        //--Consulto usuario
                        $id_registro = $valor["auditoria"][0]->cod_user;
                        $id = new MongoDB\BSON\ObjectId($id_registro);
                        $res_us_rg = $this->mongo_db->where(array("_id"=>$id))->get("usuario");
                        foreach ($res_us_rg as $clave_us_reg => $valor_us_reg) {
                            $valores["user_regis"] = $valor_us_reg["correo_usuario"];
                            $valores["id_rol"] = (string)$valor_us_reg["id_rol"];
                            $valores["correo_usuario"] = $valor_us_reg["correo_usuario"];
                        }
                        //$valores["fec_regins"] = $valor["auditoria"][0]->fecha;
                        $vector_auditoria = end($valor["auditoria"]);
                        
                        $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                        
                        $valores["status"] = $valor["status"];
                        //-----------------------------------------------------------------------------------
                        //aqui pongo error en false si la data esta ok...
                        $valores["error"] = false;
                        //--------------------------------------------------------------

                    }//Fin de ForEach de cliente Pagador
                //----------------------------------    
                }else{
                    //aqui va otro error si no esta en clientes
                    $valores["error"] = true;
                }
                //Fin cliente pagador   
            //-----------------------------------------------------------------------------------
                $listado[] = $valores;
            } //Fin datos personales
        }
        //-------------------------------------------------------------------------------
        return $listado;
    } 
    /*
    *   Listado planes
    */
    public function buscarPlanes($id_planes){
        $id = new MongoDB\BSON\ObjectId($id_planes);
        $res_planes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id))->get('planes');
        $listado = [];
        $valores = [];

        foreach ($res_planes as $key => $value) {
            //-----------------------
            //--consulto la vigencia
            $rs_vigencia = $this->mongo_db->where(array('eliminado'=>false))->get('vigencia');
            
            if($rs_vigencia[0]["descripcion"]=="Anual"){
                $ayo_vigencia = (integer)date("Y")+1;
                $mes_vigencia = date("m");
            }else if($rs_vigencia[0]["descripcion"]=="Mensual"){
                if(date("m")=="12")
                    $mes_vigencia = 1;
                else
                    $mes_vigencia = (integer)date("m")+1; 
                $ayo_vigencia = date("Y");
            }
            $mes_inicio = $this->meses_en_espayol(date("m"));
            $fecha_inicio = date("d")." ".$mes_inicio." ".date("Y");
            $mes_vigencia_esp = $this->meses_en_espayol($mes_vigencia);
            $vigencia = (string)date("d")." ".(string)$mes_vigencia_esp." ".(string)$ayo_vigencia;
            if($value["jornadas_limitadas"]==true){
                $horas_jornadas = "Jornadas Ilimitadas";
            }else{
                $horas_jornadas = $value["horas_jornadas"]; 
            }
            $valores[]= array(
                                "valor"=>number_format($value["precio"],2),
                                "horas_jornadas" =>$horas_jornadas,
                                "inicio"=> $fecha_inicio,
                                "vigencia" => $vigencia,
                                "condicion" => $value["status"]
                    );
            //-----------------------
        
            //--
        }
        return $valores;
    }
    /*
    *
    */
    public function buscarPlanesPaquetesTabla($id_planes,$id_paquete){
        //var_dump($id_planes);die('');
        $id = new MongoDB\BSON\ObjectId($id_planes);
        //---
        #Consulto precio paquetes
        $id_paq = new MongoDB\BSON\ObjectId($id_paquete);
        $res_paquetes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_paq))->get('paquetes');
        //valor = $res_paquetes[0]["precio"];
        $valor =  number_format($res_paquetes[0]["precio"],2);
        //---
        /*
        *   Nota importante: En vista a que segun documentación entregada por el sr Abrahans marzo 2019, se decidió modificar el funcionamiento del submodulo de membresia por segunda vez, la unica forma de obtener las horas pertenecientes al servicio HORAS DE COWORKING es a través de su campo descripción. Anteriormente que según mi criterio es como deberia funcionar la app, el campo de horas provenia del plan y no de un servicio... en la documentación no se explica de forma clara como relacionar una membresia con las horas de servicio por lo que decidi hacerlo a través de la descripción...
        */
        /*
        Nota importante. Como muestra de las deficiencias presentes en los requerimientos, abril 2019 se define agregar a la colección de servicios el campo tipo_servicios, y se usara el mismo como filtro para consultar las horas de coworking, a través del tipo de servicio horas de coworking
        */
        #Consulto el tipo de servicio horas de coworking
        $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');
        $id_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};
        $res_serv = $this->mongo_db->where(array('eliminado'=>false,'tipo_servicio'=>$id_horas_coworking))->get('servicios');

        $horas_jornadas = "0";
        //$precio = $res_paquetes[0]["precio"];
        $precio = number_format($res_paquetes[0]["precio"], 2);
        $servicios = $res_paquetes[0]["servicios"];
        #Recorro c/u de los servicios 
        foreach ($servicios as $clave => $valor) {
           /*$id_servicios = new MongoDB\BSON\ObjectId($valor->id_servicios);
            $res_serv = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_servicios,'descripcion'=>"HORAS DE COWORKING"))->get('servicios');
            */
            if($valor->id_servicios==$res_serv[0]["_id"]->{'$id'}){
                $horas_jornadas = $valor->valor;
            }else if($valor->id_servicios==$res_serv[1]["_id"]->{'$id'}){
                $horas_jornadas = $valor->valor;
            }
            //--
        }
        //--
                
        //var_dump($precio);die('');
        //---
        $id_pl = new MongoDB\BSON\ObjectId($id_planes);
        $res_planes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_pl))->get('planes');
        //var_dump($id_planes);die('');
        $listado = [];
        $valores = [];

        foreach ($res_planes as $key => $value) {
            //-----------------------
            //--consulto la vigencia
            $rs_vigencia = $this->mongo_db->where(array('eliminado'=>false))->get('vigencia');
            
            $fecha_actual = date("d-m-Y");

            if($rs_vigencia[0]["descripcion"]=="Anual"){
                /*$ayo_vigencia = (integer)date("Y")+1;
                $mes_vigencia = date("m");*/
                $vigenciaH = date("d-m-Y",strtotime($fecha_actual."+".$value["tiempo_contrato"]." month"));
            }else if($rs_vigencia[0]["descripcion"]=="Mensual"){
                /*if(date("m")=="12")
                    $mes_vigencia = 1;
                else
                    $mes_vigencia = (integer)date("m")+1; 
                $ayo_vigencia = date("Y");*/
                //sumo 1 mes
                $vigenciaH = date("d-m-Y",strtotime($fecha_actual."+".$value["tiempo_contrato"]." month"));
            }
            $mes_inicio = $this->meses_en_espayol(date("m"));
            $fecha_inicio = date("d")." ".$mes_inicio." ".date("Y");
            //$mes_vigencia_esp = $this->meses_en_espayol($mes_vigencia);
            //$vigencia = (string)date("d")." ".(string)$mes_vigencia_esp." ".(string)$ayo_vigencia;

             
            /*if($value["jornadas_limitadas"]==true){
                $horas_jornadas = "Jornadas Ilimitadas";
            }else{
                $horas_jornadas = $value["horas_jornadas"]; 
            }*/
            //$horas_jornadas = "0";
            //var_dump($vigenciaH);die;
            $vector_vigencia = explode("-",$vigenciaH);
            $mes_vigencia = $this->meses_en_espayol((integer)$vector_vigencia[1]);
            $vigencia = $vector_vigencia[0]." ".$mes_vigencia." ".$vector_vigencia[2];
            $valores[]= array(
                                "valor"=>$precio,
                                "horas_jornadas" =>$horas_jornadas,
                                "inicio"=> $fecha_inicio,
                                "vigencia" => $vigencia,
                                "condicion" => $value["status"]
                    );
            //-----------------------
        
            //--
        }
        return $valores;
    }
    /*
    *   Metodo para buscar paquetes...
    */
    public function buscarPaquetes($id_planes){
    //------------------------------------------
        $res_paquetes = $this->mongo_db->where(array('eliminado'=>false))->get('paquetes');
        $listado = [];
        $valores = [];
        $paquetes = [];
        //--Recorro los paquetes
        foreach ($res_paquetes as $key => $value) {
            //--Recorro los planes_servicios
            //$planes_servicios = $value["planes_servicios"];
            //foreach ($planes_servicios as $key_planes => $value_planes) {
            if($value["plan"]==$id_planes){
                $value["id_paquete"] = (string)$value["_id"]->{'$id'};
                $paquetes[] = $value;
            }
            //}
            //--
        }
        $valores = $paquetes;
        return $valores;
    //------------------------------------------    
    }
    /*
    *   Listado de planes
    */
    public function listado_planes(){
        
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("planes");
        foreach ($resultados as $clave => $valor) {
            //----------------------------------------------------
            //---Consulto paquetes: Si el plan no forma parte de un paquete no deberia listarse....
            $id_planes = (string)$valor["_id"]->{'$id'};
            //$rs_paquetes = $this->mongo_db->where(array('eliminado'=>false,'id_plan'=>$id_planes))->get('paquetes');
            //if(count($rs_paquetes)>0){
            //------------------------------------------------
            $auditoria = $valor["auditoria"][0];
            //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $vector_auditoria = end($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            isset($res_us[0]["correo_usuario"])?$valor["correo_usuario"] = $res_us[0]["correo_usuario"]:$valor["correo_usuario"] = "";
            $valor["status"] = $valor["status"];
            $valor["id_planes"] = (string)$valor["_id"]->{'$id'};
            
            if(isset($valor["jornadas_limitadas"])){
                ($valor["jornadas_limitadas"]== true)? $valor["ind_jornada"] = "S" : $valor["ind_jornada"] = "N";
            }else{
                $valor["jornadas_limitadas"] = "";
                $valor["ind_jornada"] = "";
            }

            if(isset($valor["plan_empresarial"])){
                ($valor["plan_empresarial"]== true)? $valor["ind_plan_empresarial"] = "S" : $valor["ind_plan_empresarial"] = "N";
            }else{
                $valor["plan_empresarial"] = "";
                $valor["ind_plan_empresarial"] = "";
            }

            (!isset($valor["horas_jornadas"]))? $valor["horas_jornadas"] = ""  : $valor["horas_jornadas"] = $valor["horas_jornadas"];
            //--Consulto la vigencia
            $id_vigencia = new MongoDB\BSON\ObjectId($valor["id_vigencia"]);            
            $res_vigencia = $this->mongo_db->where(array('_id'=>$id_vigencia))->get('vigencia');
            $valor["vigencia"] = $res_vigencia[0]["descripcion"];
           

            $listado[] = $valor;
            //------------------------------------------------
            //}
            //----------------------------------------------------
        }    
        //--
        $listado2 = $listado;
        return $listado2;    
    }
    /*
    *   Listado recargos saldos
    */
    public function listado_reservaciones_saldos($id_mem,$fecha,$numero_renovacion,$actual){
        $listado = [];
        #0)Paso 0 : Consulto las reservaciones
        //if($fecha==""){
        $res_reservaciones = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,"numero_renovacion"=>$numero_renovacion))->get("reservaciones");
        /*}else{

            $fecha_mongo = new MongoDB\BSON\UTCDatetime(strtotime($fecha)*1000);
            $res_reservaciones = $this->mongo_db->order_by(array('_id' => 'DESC'))->where_lte('fecha', $fecha_mongo)->where(array('eliminado'=>false,'id_membresia'=>$id_mem))->get("reservaciones");
            //var_dump($fecha);die('');
        }*/
        #1) Paso 1: Recorro las reservaciones
        foreach ($res_reservaciones as $clave_reservaciones => $valor_reservaciones) {


             $valores["codigo"] = $valor_reservaciones["codigo"];          

             
            $valores["id_reservaciones"] = (string)$valor_reservaciones["_id"]->{'$id'};
            $valores["condicion"]        = $valor_reservaciones["condicion"];
            #2) Paso 2: consulto la salas reservada
            #consulto la sala 
            $id_sala = new MongoDB\BSON\ObjectId($valor_reservaciones["id_servicio_sala"]);
            $res_sala = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_sala))->get("servicios");
            $valores["sala"] = $res_sala[0]["descripcion"];
            #3) Paso 3: Armo arreglos de horas...
            if($valor_reservaciones["hora_ingreso"]!=""){
                //$fecha_inicio = $valor_reservaciones["hora_ingreso"]->toDateTime();
                $fecha_inicio = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_ingreso"]));
                $valor_reservaciones["hora_ingreso"] = $fecha_inicio;
               
            }else{
                $valor_reservaciones["hora_ingreso"] = "";
            }
            //--Hora inicio
            if($valor_reservaciones["hora_inicio"]!=""){
                //$fecha_inicio_h = $valor_reservaciones["hora_inicio"]->toDateTime();
                $fecha_inicio_h = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_inicio"]));
                $valor_reservaciones["hora_inicio"] = $fecha_inicio_h;
                
            }else{
                $valor_reservaciones["hora_inicio"] = "";
            }
            //--Hora fin 
            if($valor_reservaciones["hora_fin"]!=""){
                //$fecha_fin_h = $valor_reservaciones["hora_fin"]->toDateTime();
                $fecha_fin_h = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_fin"]));
                $valor_reservaciones["hora_fin"] = $fecha_fin_h;
            }else{
                $valor_reservaciones["hora_fin"] = "";
            }
            //--
            if($valor_reservaciones["hora_salida"]!='Sin salir'){
                //$fecha_fin = $valor_reservaciones["hora_salida"]->toDateTime();
                $fecha_fin = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_salida"]));
                $valor_reservaciones["hora_salida"] = $fecha_fin;
            }else{
                $valor_reservaciones["hora_salida"] = "Sin salir";
            }
            $valor_reservaciones["hora_liberada"] = $valor_reservaciones["hora_salida"];
            $fecha_reservacion = $valor_reservaciones["fecha"]->toDateTime();
            $valor_reservaciones["fecha_reservacion"] = $fecha_reservacion;
            //------------------------------------------------------------
                   
            #4) Paso 4: Calculo de horas contratadas    
            $intervalo = $fecha_fin_h->diff($fecha_inicio_h);
                    
            if(isset($intervalo)){
                $valores["horas_contratadas"] = $intervalo->format('%H:%i:%s');
             }else{
                $valores["horas_contratadas"] = "00:00:00";
             }
            /*-----------------------------------------------------------------------------*/
            #5) Paso 5: Calculo de horas consumidas
            if($valor_reservaciones["condicion"]=="REGISTRADA"){
                #Si la condicion es registrada: se resta la hora actua a la hora de ingreso
                if($valor_reservaciones["hora_ingreso"]!=""){
                    $hoy = new DateTime("now");
                    $intervalo2 =$fecha_inicio->diff($hoy);
                    $intervaloConsmuidas = $intervalo2;
                    if(isset($intervalo2)){
                        $valores["horas_consumidas"] = $intervalo2->format('%H:%i:%s');
                    }else{
                        $valores["horas_consumidas"] = "00:00:00";
                    }
                }else{
                    $valores["horas_consumidas"] = "00:00:00";
                } 
            } if($valor_reservaciones["condicion"]=="LIBERADA" || ($valor_reservaciones["condicion"]=="CANCELADA")){
                #Si la condicione s liberada: se resta la hora salida a la de ingreso
                $intervalo3 =$fecha_fin->diff($fecha_inicio);
                $intervaloConsmuidas = $intervalo3;

                if(isset($intervalo3)){
                    $valores["horas_consumidas"] = $intervalo3->format('%H:%i:%s');
                }else{
                    $valores["horas_consumidas"] = "00:00:00";
                }
            }else if(($valor_reservaciones["condicion"]=="RESERVADA")){
                $valores["horas_consumidas"] = "00:00:00";
            }
            //------------------------------------------------------------
            #6) Paso6: Calculo de horas por consumir
            if(($valores["horas_contratadas"]!="00:00:00")&&($valores["horas_consumidas"]!="00:00:00")){
                //var_dump($intervalo<$intervaloConsmuidas);die('');
                
                $horas_uno = new DateTime($valores["horas_contratadas"]);
                $horas_dos = new DateTime($valores["horas_consumidas"]);
                if($horas_dos>$horas_uno){   
                        $valores["horas_disponibles"] = "00:00:00";
                }else{
                    $intervaloDisponibles = $horas_uno->diff($horas_dos);
                    $valores["horas_disponibles"] = $intervaloDisponibles->format('%H:%i:%s'); 
                    /*if($valores["horas_disponibles"]>$valores["horas_contratadas"]){
                        $valores["horas_disponibles"] = "";
                    }*/
                }    
            }else{
                if($valores["horas_consumidas"]=="00:00:00")
                    $valores["horas_disponibles"] = $valores["horas_contratadas"];
                else 
                    $valores["horas_disponibles"] = "00:00:00";
            }
            #  


            $listado[] = $valores;
            //--fin de Foreach de rservaciones...
        } 
        return $listado;
    }
     /*
    *   Listado recargos saldos
    */
    public function listado_reservaciones_saldos_dos($id_mem,$fecha,$numero_renovacion,$actual,$id_servicio_sala){
        $listado = [];
        $acumulador_segundos_disponibles = 0;
        $acumulador_segundos_consumidos = 0;
        $horas_contratadas = 0;
        #0)Paso 0 : Consulto las reservaciones
        //if($fecha==""){
        $res_reservaciones = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,"numero_renovacion"=>$numero_renovacion,"id_servicio_sala"=>$id_servicio_sala))->get("reservaciones");
        /*}else{

            $fecha_mongo = new MongoDB\BSON\UTCDatetime(strtotime($fecha)*1000);
            $res_reservaciones = $this->mongo_db->order_by(array('_id' => 'DESC'))->where_lte('fecha', $fecha_mongo)->where(array('eliminado'=>false,'id_membresia'=>$id_mem))->get("reservaciones");
            //var_dump($fecha);die('');
        }*/
        #1) Paso 1: Recorro las reservaciones
        foreach ($res_reservaciones as $clave_reservaciones => $valor_reservaciones) {
            $valores["id_reservaciones"] = (string)$valor_reservaciones["_id"]->{'$id'};
            #2) Paso 2: consulto la salas reservada
            #consulto la sala 
            $id_sala = new MongoDB\BSON\ObjectId($valor_reservaciones["id_servicio_sala"]);
            $res_sala = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_sala))->get("servicios");
            $valores["sala"] = $res_sala[0]["descripcion"];
            #3) Paso 3: Armo arreglos de horas...
            if($valor_reservaciones["hora_ingreso"]!=""){
                //$fecha_inicio = $valor_reservaciones["hora_ingreso"]->toDateTime();
                $fecha_inicio = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_ingreso"]));
                $valor_reservaciones["hora_ingreso"] = $fecha_inicio;
               
            }else{
                $valor_reservaciones["hora_ingreso"] = "";
            }
            //--Hora inicio
            if($valor_reservaciones["hora_inicio"]!=""){
                //$fecha_inicio_h = $valor_reservaciones["hora_inicio"]->toDateTime();
                $fecha_inicio_h = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_inicio"]));
                $valor_reservaciones["hora_inicio"] = $fecha_inicio_h;
                
            }else{
                $valor_reservaciones["hora_inicio"] = "";
            }
            //--Hora fin 
            if($valor_reservaciones["hora_fin"]!=""){
                //$fecha_fin_h = $valor_reservaciones["hora_fin"]->toDateTime();
                $fecha_fin_h = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_fin"]));
                $valor_reservaciones["hora_fin"] = $fecha_fin_h;
            }else{
                $valor_reservaciones["hora_fin"] = "";
            }
            //--
            if($valor_reservaciones["hora_salida"]!='Sin salir'){
                //$fecha_fin = $valor_reservaciones["hora_salida"]->toDateTime();
                $fecha_fin = new DateTime(date("Y-m-d g:i a",$valor_reservaciones["hora_salida"]));
                $valor_reservaciones["hora_salida"] = $fecha_fin;
            }else{
                $valor_reservaciones["hora_salida"] = "Sin salir";
            }
            $valor_reservaciones["hora_liberada"] = $valor_reservaciones["hora_salida"];
            $fecha_reservacion = $valor_reservaciones["fecha"]->toDateTime();
            $valor_reservaciones["fecha_reservacion"] = $fecha_reservacion;
            //------------------------------------------------------------
                   
            #4) Paso 4: Calculo de horas contratadas    
            $intervalo = $fecha_fin_h->diff($fecha_inicio_h);
                    
            if(isset($intervalo)){
                $valores["horas_contratadas"] = $intervalo->format('%H:%i:%s');
             }else{
                $valores["horas_contratadas"] = "00:00:00";
             }
            /*-----------------------------------------------------------------------------*/
            #5) Paso 5: Calculo de horas consumidas
            if($valor_reservaciones["condicion"]=="REGISTRADA"){
                #Si la condicion es registrada: se resta la hora actua a la hora de ingreso
                if($valor_reservaciones["hora_ingreso"]!=""){
                    $hoy = new DateTime("now");
                    $intervalo2 =$fecha_inicio->diff($hoy);
                    $intervaloConsmuidas = $intervalo2;
                    if(isset($intervalo2)){
                        $valores["horas_consumidas"] = $intervalo2->format('%H:%i:%s');
                    }else{
                        $valores["horas_consumidas"] = "00:00:00";
                    }
                }else{
                    $valores["horas_consumidas"] = "00:00:00";
                } 
            } if($valor_reservaciones["condicion"]=="LIBERADA"){
                #Si la condicione s liberada: se resta la hora salida a la de ingreso
                $intervalo3 =$fecha_fin->diff($fecha_inicio);
                $intervaloConsmuidas = $intervalo3;

                if(isset($intervalo3)){
                    $valores["horas_consumidas"] = $intervalo3->format('%H:%i:%s');
                }else{
                    $valores["horas_consumidas"] = "00:00:00";
                }
            }else if(($valor_reservaciones["condicion"]=="RESERVADA")||($valor_reservaciones["condicion"]=="CANCELADA")){
                $valores["horas_consumidas"] = "00:00:00";
            }
            //------------------------------------------------------------
            #6) Paso6: Calculo de horas por consumir
            if(($valores["horas_contratadas"]!="00:00:00")&&($valores["horas_consumidas"]!="00:00:00")){
                //var_dump($intervalo<$intervaloConsmuidas);die('');
                
                $horas_uno = new DateTime($valores["horas_contratadas"]);
                $horas_dos = new DateTime($valores["horas_consumidas"]);
                if($horas_dos>$horas_uno){   
                        $valores["horas_disponibles"] = "00:00:00";
                }else{
                    $intervaloDisponibles = $horas_uno->diff($horas_dos);
                    $valores["horas_disponibles"] = $intervaloDisponibles->format('%H:%i:%s'); 
                    /*if($valores["horas_disponibles"]>$valores["horas_contratadas"]){
                        $valores["horas_disponibles"] = "";
                    }*/
                }    
            }else{
                if($valores["horas_consumidas"]=="00:00:00")
                    $valores["horas_disponibles"] = $valores["horas_contratadas"];
                else 
                    $valores["horas_disponibles"] = "00:00:00";
            }
            #    
            $segundos_consumidos= $this->convertir_segundos($valores["horas_consumidas"]);
            $segundos_disponibles= $this->convertir_segundos($valores["horas_disponibles"]);  
            //--
            $horas_contratadas =  $valores["horas_contratadas"];

            $acumulador_segundos_consumidos = $acumulador_segundos_consumidos+$segundos_consumidos;
            $acumulador_segundos_disponibles = $acumulador_segundos_disponibles+$segundos_disponibles;
            //--
            
            //--fin de Foreach de rservaciones...
        } 


        $horas_consumidas = $this->conversorSegundosHoras($acumulador_segundos_consumidos);
        $horas_disponibles = $this->conversorSegundosHoras($acumulador_segundos_disponibles);
        $listado =  array("horas_contratadas"=>$horas_contratadas,"horas_consumidas"=>$horas_consumidas,"horas_disponibles"=>$horas_disponibles);
        return $listado;
    }
    /*
    *   Convertir segundos
    */
    public function convertir_segundos($horas){
        $vector_horas = explode(":",$horas);
        $horas_trans_segundos = $vector_horas[0]*3600;
        $minutos_trans_segundos = $vector_horas[1]*60;
        $segundos_trans = $vector_horas[2]+$minutos_trans_segundos+$horas_trans_segundos;
        return $segundos_trans;
    }
    /*
    *   Listado recargos saldos dos: Es una sobre carga del metodo listado_reservaciones_saldos, y recorre la información 
    */
   
    /*
    *   LIstado jornadas saldos
    */
    public function listado_jornadas_saldos($id_mem,$fecha,$numero_renovacion,$actual){
        $listado = [];
        $horas_jornadas = "0";
        $precio = "0,00"; 
        //$id = new MongoDB\BSON\ObjectId($id_membresia);
        #0)-Paso 0:Consulto el tipo de servicio horas de coworking
        $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');

        $id_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};  

        #-1)Paso 1: Consulto las jornadas, asociadas a esa membresia
        if($fecha==""){
            $res_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,"numero_renovacion"=>$numero_renovacion))->get("jornadas");


        }else if($fecha!=""){
            $fecha_mongo = new MongoDB\BSON\UTCDatetime(strtotime($fecha)*1000);
            $res_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,"numero_renovacion"=>$numero_renovacion))->where_lte('fecha_hora_inicio', $fecha_mongo)->get("jornadas");
        }
        #-2) Paso 2: Recorro las jornadas
        foreach ($res_jornadas as $clave_jornadas => $valor_jornadas) {

            $id_jornadas = $valor_jornadas["_id"]->{'$id'};  
            $valores["id_jornadas"] = (string)$valor_jornadas["_id"]->{'$id'}; 
            $id_membresia =  new MongoDB\BSON\ObjectId($id_mem);
 
            #-3) Paso 3: consulto las membresia 
            #3.1 Verifico si la renovacion actual o esta en el arreglo renovaciones
            #Si consulto a la colección principal de membreśia
            
            $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_membresia))->get("membresia");
                //var_dump($res_membresia);die("actual");
            if($actual!="actual"){
                $res_membresia2 = $res_membresia[0]["renovaciones"];
                $res_membresia = [];
                foreach ($res_membresia2 as $key => $value_renova) {
                    # code...
                    if($value_renova->numero_renovacion==$numero_renovacion){
                        $res_membresia[] =  (array)$value_renova;
                    }
                }
                #Si consulto al subdocumento de renovaciones de membresia
               
            }
            #-4) Paso 4: Recorro la membresia
            foreach ($res_membresia as $clave_membresia => $valor_membresia) {
                //--------------------------------------------------------------
                #5) Paso 5:Consulto planes
                $id_planes = new MongoDB\BSON\ObjectId($valor_membresia["plan"]);
                //'eliminado'=>false,
                $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
                if($res_planes[0]["jornadas_limitadas"]==true){
                    $horas_jornadas = "Jornadas ilimitadas";
                }
                #6) Paso 6:Consulto horas de coworking segun su id
                $res_serv = $this->mongo_db->where(array('eliminado'=>false,'tipo_servicio'=>$id_horas_coworking))->get('servicios');
                /*---------------------------------------------------------------------------*/
                $id_paquete =  $valor_membresia["paquete"];
                $id_paq = new MongoDB\BSON\ObjectId($id_paquete);
                $res_paquetes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_paq))->get('paquetes');
                $servicios = $res_paquetes[0]["servicios"];
                #7) Paso 7:Recorro c/u de los servicios 
                foreach ($servicios as $clave_serv => $valor_serv) {
                    if($valor_serv->id_servicios==$res_serv[0]["_id"]->{'$id'}){
                        $horas_jornadas = $valor_serv->valor;
                    }else if($valor_serv->id_servicios==$res_serv[1]["_id"]->{'$id'}){
                        $horas_jornadas = $valor_serv->valor;
                    }
                }
                //--------------------------------------------------------------/*
                
                $valores["contratados"] = $horas_jornadas;
                /*
                *
                */
                #9) Paso 9: Consulto las horas transcurridas asociada a esa jornada particular
                /*
                *   
                */
                if($id_jornadas!=""){
                    $horas_transcurridas_individual = $this->calcular_horas_jornadas_individual($id_jornadas);
                }
                //---------------------------------------------------------------------------------
                if($horas_jornadas!="Jornadas ilimitadas"){
                   
                    if($horas_transcurridas_individual=="00:00:00"){
                        $horas_disponibles = $horas_jornadas;
                    }else{
                        //--Restar fechas sin date time
                        $vector_horas = explode(":",$horas_transcurridas_individual);
                        $horas_trans_segundos = $vector_horas[0]*3600;
                        $minutos_trans_segundos = $vector_horas[1]*60;
                        $segundos_trans = $vector_horas[2]+$minutos_trans_segundos+$horas_trans_segundos;
                        $segundos_disponibles= (integer)$horas_jornadas*3600;
                        $segundos_totales = $segundos_disponibles - $segundos_trans;
                        $horas_disponibles_prev = round($segundos_totales/3600,1);
                        $minutos_disp_en_horas = explode(".",$horas_disponibles_prev);
                        //var_dump($minutos_disp_en_horas);die('');
                        if(count($minutos_disp_en_horas)>1){
                            $super_min = "0.".$minutos_disp_en_horas[1];
                        }else{
                            $super_min = "0";
                        }
                        $min = (float)$super_min;
                        $minutos_disponibles = $min*60;
                        
                        if($minutos_disp_en_horas[0]<0){
                            $positivo = -1*($minutos_disp_en_horas[0]);
                            $horas_disponibles = "<label style='danger'>00:00:00</label> Se excedió en:".$positivo."Hrs ".$minutos_disponibles." Min";
                        }else{
                            $horas_disponibles = $minutos_disp_en_horas[0]."Hrs ".$minutos_disponibles." Min";
                        }
                        
                    }

                }else{
                    $horas_disponibles = "Jornadas ilimitadas";
                }
                $valores["disponibles"]=$horas_disponibles;

                $valores["consumidos"] = $horas_transcurridas_individual;
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                $valores["actual"]=$fecha->toDateTime();

                $listado[] = $valores;
                //---------------------------------------------------------------------------------
            //---Fin de for
            }
            //---

        }
        return $listado;

       /*foreach ($resultados as $clave => $valor) {
            
            $valores = $valor;
            $valores["id_membresia"] = (string)$valor["_id"]->{'$id'};
          
            #Consulto planes
            $id_planes = new MongoDB\BSON\ObjectId($valor["plan"]);
            //'eliminado'=>false,
            $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
                       
            if($res_planes[0]["jornadas_limitadas"]==true){
                $horas_jornadas = "Jornadas ilimitadas";
            }
            #Consulto el tipo de servicio horas de coworking
            $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');
            $id_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};
            $res_serv = $this->mongo_db->where(array('eliminado'=>false,'tipo_servicio'=>$id_horas_coworking))->get('servicios');

            $id_paquete =  $valor["paquete"];
            $id_paq = new MongoDB\BSON\ObjectId($id_paquete);
            $res_paquetes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_paq))->get('paquetes');
            $servicios = $res_paquetes[0]["servicios"];
            #Recorro c/u de los servicios 
            foreach ($servicios as $clave_serv => $valor_serv) {
                if($valor_serv->id_servicios==$res_serv[0]["_id"]->{'$id'}){
                    $horas_jornadas = $valor_serv->valor;
                }
            }
            /*
            *   Cambiar horas y valor!
            */
            /*$valores["plan_horas_jornadas"] = $horas_jornadas;
                        /*
            *
            */
            /*$valores["fecha_inicio"] = $valor["fecha_inicio"]->toDateTime();

            $valores["fecha_fin"] = $valor["fecha_fin"]->toDateTime();

           
            /*
            *   Consulto todas las jornadas asociadas a esa membresia
            */
           /* $horas_transcurridas = $this->calcular_horas_jornadas($id_membresia);
            if($id_jornadas!=""){
                $horas_transcurridas_individual = $this->calcular_horas_jornadas_individual($id_jornadas);
            }else{
                $horas_transcurridas_individual = "00:00:00";
            }
            
            //$horas_transcurridas = "00:00:00";
            /*
            *   Se debe cambiar esto ya que se debe calcular de otra forma segun cambio alcance señalado pro el Sr Abrahans Mayo-2013
            */
           /* if($horas_jornadas!="Jornadas ilimitadas"){
               
                if($horas_transcurridas=="00:00:00"){
                    $horas_disponibles = $horas_jornadas;
                }else{
                    //--Restar fechas sin date time
                    $vector_horas = explode(":",$horas_transcurridas);
                    $horas_trans_segundos = $vector_horas[0]*3600;
                    $minutos_trans_segundos = $vector_horas[1]*60;
                    $segundos_trans = $vector_horas[2]+$minutos_trans_segundos+$horas_trans_segundos;
                    $segundos_disponibles= (integer)$horas_jornadas*3600;
                    $segundos_totales = $segundos_disponibles - $segundos_trans;
                    $horas_disponibles_prev = round($segundos_totales/3600,1);
                    $minutos_disp_en_horas = explode(".",$horas_disponibles_prev);
                    //var_dump($minutos_disp_en_horas);die('');
                    if(count($minutos_disp_en_horas)>1){
                        $super_min = "0.".$minutos_disp_en_horas[1];
                    }else{
                        $super_min = "0";
                    }
                    $min = (float)$super_min;
                    $minutos_disponibles = $min*60;
                    
                    if($minutos_disp_en_horas[0]<0){
                        $positivo = -1*($minutos_disp_en_horas[0]);
                        $horas_disponibles = "<label style='danger'>00:00:00</label> Se excedió en:".$positivo."Hrs ".$minutos_disponibles." Min";
                    }else{
                        $horas_disponibles = $minutos_disp_en_horas[0]."Hrs ".$minutos_disponibles." Min";
                    }
                    
                }

                //------------------------------------------------------------------
            }else{
                $horas_disponibles = "Jornadas ilimitadas";
            }
            
            $valores["horas_transcurridas"]=$horas_transcurridas;
            //var_dump($valores["horas_transcurridas"]);
            $valores["horas_disponibles"]=$horas_disponibles;

            $valores["horas_transcurridas_x_jornada"] = $horas_transcurridas_individual;
            
            $fecha = new MongoDB\BSON\UTCDateTime();
            $valores["actual"]=$fecha->toDateTime();

            $listado[] = $valores;
        }*/
        //var_dump($listado);
    }
    /*
    *   conversorSegundosHoras
    */
    public function conversorSegundosHoras($tiempo_en_segundos) {
        $horas = floor($tiempo_en_segundos / 3600);
        $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
        $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

        return $horas . ':' . $minutos . ":" . $segundos;
    }
    /*
    *   Conversor horas strinf
    */
    public function conversorHorasString($segundos_totales){
        $horas_disponibles_prev = round($segundos_totales/3600,1);
        $minutos_disp_en_horas = explode(".",$horas_disponibles_prev);
        //var_dump($minutos_disp_en_horas);die('');
        if(count($minutos_disp_en_horas)>1){
            $super_min = "0.".$minutos_disp_en_horas[1];
        }else{
            $super_min = "0";
        }
        $min = (float)$super_min;
        $minutos_disponibles = $min*60;
        
        if($minutos_disp_en_horas[0]<0){
            $positivo = -1*($minutos_disp_en_horas[0]);
            $horas_disponibles = "<label style='danger'>00:00:00</label> Se excedió en:".$positivo."Hrs ".$minutos_disponibles." Min";
        }else{
            $horas_disponibles = $minutos_disp_en_horas[0]."Hrs ".$minutos_disponibles." Min";
        }
        return $horas_disponibles;
                        
    }
    /*
    *   Listado jornadas saldos Dos: este metodo aplica para consultar las horas de coworking consumidas, la misma debe coincidir con el tiempo contratado, transcurrido y dispoble de jornadas, este metodo es una sobre carga de listado_jornadas_saldos
    */
    public function listado_jornadas_saldos_dos($id_mem,$fecha,$numero_renovacion,$actual){
        $listado = [];
        $horas_jornadas = "0";
        $precio = "0,00"; 
        $acum_disponibles = 0;
        $acum_consumidos = 0;
        //$id = new MongoDB\BSON\ObjectId($id_membresia);
        #0)-Paso 0:Consulto el tipo de servicio horas de coworking
        $res_tipo_serv = $this->mongo_db->where(array('eliminado'=>false,'titulo'=>"HORAS DE COWORKING"))->get('tipo_servicios');

        $id_horas_coworking =  $res_tipo_serv[0]["_id"]->{'$id'};  

        #-1)Paso 1: Consulto las jornadas, asociadas a esa membresia
        if($fecha==""){
            $res_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,"numero_renovacion"=>$numero_renovacion))->get("jornadas");


        }else if($fecha!=""){
            $fecha_mongo = new MongoDB\BSON\UTCDatetime(strtotime($fecha)*1000);
            $res_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,"numero_renovacion"=>$numero_renovacion))->where_lte('fecha_hora_inicio', $fecha_mongo)->get("jornadas");
        }
        #-2) Paso 2: Recorro las jornadas
        foreach ($res_jornadas as $clave_jornadas => $valor_jornadas) {

            $id_jornadas = $valor_jornadas["_id"]->{'$id'};  
            $valores["id_jornadas"] = (string)$valor_jornadas["_id"]->{'$id'}; 
            $id_membresia =  new MongoDB\BSON\ObjectId($id_mem);
 
            #-3) Paso 3: consulto las membresia 
            #3.1 Verifico si la renovacion actual o esta en el arreglo renovaciones
            #Si consulto a la colección principal de membreśia
            
            $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_membresia))->get("membresia");
                //var_dump($res_membresia);die("actual");
            if($actual!="actual"){
                $res_membresia2 = $res_membresia[0]["renovaciones"];
                $res_membresia = [];
                foreach ($res_membresia2 as $key => $value_renova) {
                    # code...
                    if($value_renova->numero_renovacion==$numero_renovacion){
                        $res_membresia[] =  (array)$value_renova;
                    }
                }
                #Si consulto al subdocumento de renovaciones de membresia
               
            }
            #-4) Paso 4: Recorro la membresia
            foreach ($res_membresia as $clave_membresia => $valor_membresia) {
                //--------------------------------------------------------------
                #5) Paso 5:Consulto planes
                $id_planes = new MongoDB\BSON\ObjectId($valor_membresia["plan"]);
                //'eliminado'=>false,
                $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
                if($res_planes[0]["jornadas_limitadas"]==true){
                    $horas_jornadas = "Jornadas ilimitadas";
                }
                #6) Paso 6:Consulto horas de coworking segun su id
                $res_serv = $this->mongo_db->where(array('eliminado'=>false,'tipo_servicio'=>$id_horas_coworking))->get('servicios');
                /*---------------------------------------------------------------------------*/
                $id_paquete =  $valor_membresia["paquete"];
                $id_paq = new MongoDB\BSON\ObjectId($id_paquete);
                $res_paquetes = $this->mongo_db->where(array('eliminado'=>false,'_id'=>$id_paq))->get('paquetes');
                $servicios = $res_paquetes[0]["servicios"];
                #7) Paso 7:Recorro c/u de los servicios 
                foreach ($servicios as $clave_serv => $valor_serv) {
                    if($valor_serv->id_servicios==$res_serv[0]["_id"]->{'$id'}){
                        $horas_jornadas = $valor_serv->valor;
                    }else if($valor_serv->id_servicios==$res_serv[1]["_id"]->{'$id'}){
                        $horas_jornadas = $valor_serv->valor;
                    }
                }
                //--------------------------------------------------------------/*
                
                $valores["contratados"] = $horas_jornadas;
                /*
                *
                */
                #9) Paso 9: Consulto las horas transcurridas asociada a esa jornada particular
                /*
                *   
                */
                if($id_jornadas!=""){
                    $horas_transcurridas_individual = $this->calcular_horas_jornadas_individual($id_jornadas);
                }
                //---------------------------------------------------------------------------------
                if($horas_jornadas!="Jornadas ilimitadas"){
                   
                    if($horas_transcurridas_individual=="00:00:00"){
                        $horas_disponibles = $horas_jornadas;
                    }else{
                        //--Restar fechas sin date time
                        $vector_horas = explode(":",$horas_transcurridas_individual);
                        $horas_trans_segundos = $vector_horas[0]*3600;
                        $minutos_trans_segundos = $vector_horas[1]*60;
                        $segundos_trans = $vector_horas[2]+$minutos_trans_segundos+$horas_trans_segundos;
                        //--Horas transcurridas...
                        $horas_trans_total= round($segundos_trans/3600,2);
                        //--
                        $segundos_disponibles= (integer)$horas_jornadas*3600;
                        $segundos_totales = $segundos_disponibles - $segundos_trans;
                        $horas_disponibles_prev = round($segundos_totales/3600,2);
                    }

                }else{
                    $horas_disponibles = "Jornadas ilimitadas";
                }
                $acum_disponibles=$acum_disponibles+$segundos_totales;

                $acum_consumidos = $acum_consumidos+$segundos_trans;
                
                $disponible_formatos = $this->conversorHorasString($acum_disponibles);
                $consumidos_formatos = $this->conversorSegundosHoras($acum_consumidos);

                //----------------------------------------------------------------------
            //---Fin de for
            }
            //---
            #Calculo la resta de acum_consumidos - $horas_jornadas
            $segundos_contratados= (integer)$horas_jornadas*3600;
            $horas_disponibles_total = $segundos_contratados - $acum_consumidos;
            $disponible_formatos = $this->conversorHorasString($horas_disponibles_total);
            //---
            $listado = array("contratados"=>$horas_jornadas,"disponible"=>$disponible_formatos,"consumidos"=>$consumidos_formatos);
        }
        return $listado;

      
    }
    /*
    *   listado recargos adicionales saldos
    */
    public function listado_recargos_adicionales_saldos($id_mem,$fecha,$numero_renovacion,$actual){
        $cantidad = 0;
        $listado = [];
        $listado_def = []; 
        $arreglo_id = [];
        $arreglo_unico = [];
        $servicios_jornadas = ["servicios"=>"","cantidad"=>""];
        $num_renovacion = (integer)$numero_renovacion;
        #0)Paso 0 : Consulto las jornadas
        //var_dump($id_mem);var_dump($fecha);die('');
        if($id_mem!=""){
            #Si no filtra por campo fecha
            if($fecha==""){
                $res_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,'numero_renovacion'=>$num_renovacion))->get("jornadas");

            }else if($fecha!=""){
                #Si filtra por campo fecha
                $fecha_mongo =  new MongoDB\BSON\UTCDateTime();
                //new MongoDB\BSON\UTCDatetime(strtotime($fecha)*1000);
                $res_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'id_membresia'=>$id_mem,'numero_renovacion'=>$num_renovacion))->where_lte('fecha_hora_inicio', $fecha_mongo)->get("jornadas");
            
            }
        }else{
            $res_jornadas = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'numero_renovacion'=>$num_renovacion))->get("jornadas");
        }
        #1) Paso 1 : Recorro las jornadas
        foreach ($res_jornadas as $clave_jornadas => $valor_jornadas) {
            #2) paso 2: Si la jornada tiene servicios adicionales
            if(count($valor_jornadas["servicios"])>0){
                $servicios = $valor_jornadas["servicios"];
                /*var_dump($fecha_mongo);echo "<br>";
                var_dump($valor_jornadas["fecha_hora_inicio"]);echo "<br>";*/

                #3) Paso 3: Recorro esos servicios adicionales
                foreach ($servicios as $clave_servicios => $valor_servicios) {
                    if($valor_servicios->tipo=="opcional"){
                        $servicios_jornadas["servicios"] = $valor_servicios->id_servicio;
                        $servicios_jornadas["cantidad"] = $valor_servicios->cantidad;
                        $servicios_jornadas["fecha"] = $valor_servicios->auditoria[0]->fecha->toDateTime();
                        #4) Paso 4: Armo arreglo de id
                        $arreglo_id [] = $valor_servicios->id_servicio;
                        $listado [] = $servicios_jornadas;
                    }
                }
            }
        }
    
        #5) Paso 5: Creo un arreglo unico de los servicios consumidos
        $arreglo_unico = array_unique($arreglo_id);

        #6) Paso 6: Recorro el arreglo de servicios unicos
        foreach ($arreglo_unico as $idServicio) {
            #7) Paso 7: Obtengo las posiciones donde sen encuentran los servicios unicos
            $posicion = array_keys($arreglo_id,$idServicio);
            /*echo "ID:";
            var_dump($idServicio);
            echo "<br>";
            var_dump($posicion);
            echo "<br>";*/
            #8) Paso 8: Recorro posicion
            foreach ($posicion as $valor_posicion) {
                //prp($listado[$valor_posicion]['fecha'],1);
                $cantidad = $cantidad+$listado[$valor_posicion]["cantidad"];
                $fecha    =  $listado[$valor_posicion]['fecha'];
            }
            #9) Paso 9: Consulto el titulo.... $this->mongo_db->date($fecha_ini)
            $titulo = $this->consultar_titulo_servicios($idServicio);
            $listado_def[] = array("id"=>$idServicio,"titulo"=>$titulo,"cantidad"=>$cantidad,"fecha"=>$fecha);
            $cantidad = 0;
        }
        return $listado_def;
    }
    /*
    *   listado_recargos_saldos
    */
    public function listado_recargos_saldos($id_mem,$fecha,$numero_renovacion,$actual){
        $listado = [];
        #0)Paso 0 : Consulto las reservaciones
        $id =  new MongoDB\BSON\ObjectId($id_mem);
        #Si el filtro fecha esta en blanco visualizo toda la info de membresia al dia
        if($fecha == ""){
            //$res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("membresia");
            #Si consulto a la colección principal de membreśia
            $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("membresia");
            
            if($actual!="actual"){

                $res_membresia2 = $res_membresia[0]["renovaciones"];
                $res_membresia = [];
                foreach ($res_membresia2 as $key => $value_renova) {
                    # code...
                    if($value_renova->numero_renovacion==$numero_renovacion){
                        $res_membresia[] =  (array)$value_renova;
                    }
                }
                #Si consulto al subdocumento de renovaciones de membresia
               
            }
        }

        
        if(count($res_membresia)>0){
            #Si es la membresia actual
            $servicios_arreglo = $res_membresia[0]["servicios"];
         
            #1)Paso 1 : Recorro los servicios
            foreach ($servicios_arreglo as $clave_servicios => $valor_servicios) {
                
                if($valor_servicios->activo == 1){
                    foreach($valor_servicios->servicios as $key2 => $valor){
                       //echo json_encode($valor->servicios);
                        #---
                        #2) Paso 2 : Consulto titulo de servicios
                        $titulo = $this->consultar_titulo_servicios($valor->servicios);
                        #3) Paso 3: Consulto la cantidad consumida de ese servicio
                        $consumidos = $this->consultar_servicio_consumido($valor->servicios,$id_mem);
                        #4) Paso 4: Calculo cantidad disponible
                    
                       // $consumidos = $valor->cantidad-$valor->disponible;
                        $valores["id_servicios"] = $valor->servicios;
                        $valores["servicios"] = $titulo;
                        $valores["contratados"] = $valor->cantidad;
                        $valores["consumidos"] = $valor->consumidos;
                        $valores["disponibles"] = $valor->disponible;
                        #---
                        $listado[] = $valores;
                    }
                    #5)Paso 5: Recorro servicios c
                   // $servicios_arreglo_c = $res_membresia[0]["servicios_c"];
                    $servicios_arreglo_c = $valor_servicios->servicios_c;
                    if(count($servicios_arreglo_c)>0){
                        //---
                        foreach ($servicios_arreglo_c as $clave_servicios_c => $valor_servicios_c){
                            #---
                            #6) Paso 6: Consulto titulo de servicios
                            $titulo = $this->consultar_titulo_servicios($valor_servicios_c->servicios);
                            $disponible = $valor_servicios_c->valor;
                            $valores2["id_servicios"] = $valor_servicios_c->servicios;
                            $valores2["servicios"] = $titulo;
                            $valores2["contratados"] = "";
                            $valores2["consumidos"] = "";
                            $valores2["disponibles"] = $disponible;
                            #---
                            $listado[] = $valores2;
                        }
                        //---
                    }

                }
            }
                
                
        }

        //var_dump($listado);die('');
        return $listado;
    }










    public function listado_recargos_saldosByMes($id_mem,$mes,$numero_renovacion,$actual){
        $listado = [];
        #0)Paso 0 : Consulto las reservaciones
        $id =  new MongoDB\BSON\ObjectId($id_mem);
        #Si el filtro fecha esta en blanco visualizo toda la info de membresia al dia
        if($fecha == ""){
            //$res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("membresia");
            #Si consulto a la colección principal de membreśia
            $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("membresia");
            
            if($actual!="actual"){
                
                $res_membresia2 = $res_membresia[0]["renovaciones"];
                $res_membresia = [];
                foreach ($res_membresia2 as $key => $value_renova) {
                    # code...
                    if($value_renova->numero_renovacion==$numero_renovacion){
                        $res_membresia[] =  (array)$value_renova;
                    }
                }
                #Si consulto al subdocumento de renovaciones de membresia
               
            }
        }


        
        if(count($res_membresia)>0){
            #Si es la membresia actual
            $servicios_arreglo = $res_membresia[0]["servicios"];
         
            #1)Paso 1 : Recorro los servicios
            foreach ($servicios_arreglo as $clave_servicios => $valor_servicios) {
                
                if($valor_servicios->mes == $mes){
                    foreach($valor_servicios->servicios as $key2 => $valor){
                       //echo json_encode($valor->servicios);
                        #---
                        #2) Paso 2 : Consulto titulo de servicios
                        $titulo = $this->consultar_titulo_servicios($valor->servicios);
                        #3) Paso 3: Consulto la cantidad consumida de ese servicio
                        $consumidos = $this->consultar_servicio_consumido($valor->servicios,$id_mem);
                        #4) Paso 4: Calculo cantidad disponible
                    
                        $consumidos = $valor->cantidad-$valor->disponible;;
                        $valores["id_servicios"] = $valor->servicios;
                        $valores["servicios"] = $titulo;
                        $valores["contratados"] = $valor->cantidad;
                        $valores["consumidos"] = $valor->consumidos;
                        $valores["disponibles"] = $valor->disponible;
                        #---
                        $listado[] = $valores;
                    }
                    #5)Paso 5: Recorro servicios c
                   // $servicios_arreglo_c = $res_membresia[0]["servicios_c"];
                    $servicios_arreglo_c = $valor_servicios->servicios_c;
                    if(count($servicios_arreglo_c)>0){
                        //---
                        foreach ($servicios_arreglo_c as $clave_servicios_c => $valor_servicios_c){
                            #---
                            #6) Paso 6: Consulto titulo de servicios
                            $titulo = $this->consultar_titulo_servicios($valor_servicios_c->servicios);
                            $disponible = $valor_servicios_c->valor;
                            $valores2["id_servicios"] = $valor_servicios_c->servicios;
                            $valores2["servicios"] = $titulo;
                            $valores2["contratados"] = "";
                            $valores2["consumidos"] = "";
                            $valores2["disponibles"] = $disponible;
                            #---
                            $listado[] = $valores2;
                        }
                        //---
                    }

                }
            }
                
                
        }

        //var_dump($listado);die('');
        return $listado;
    }

    
    /*
    *   Consultar Planes/Paquetes
    */
    public function listado_planes_paquetes($id_mem,$numero_renovacion,$actual){
        $id =  new MongoDB\BSON\ObjectId($id_mem);
        $res_membresia = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("membresia");
            
            if($actual!="actual"){
                $res_membresia2 = $res_membresia[0]["renovaciones"];
                $res_membresia = [];
                foreach ($res_membresia2 as $key => $value_renova) {
                    # code...
                    if($value_renova->numero_renovacion==$numero_renovacion){
                        $res_membresia[] =  (array)$value_renova;
                    }
                }
            }
        //---
        foreach ($res_membresia as $clave_membresia => $valor_membresia) {
            $id_planes = new MongoDB\BSON\ObjectId($valor_membresia["plan"]);
            $id_paquetes = new MongoDB\BSON\ObjectId($valor_membresia["paquete"]);
            $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
            $res_paquetes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_paquetes))->get("paquetes");

        }    
        //---
        $arreglo_plan_paquetes = array("plan"=>$res_planes[0]["titulo"],"paquete"=>$res_paquetes[0]["descripcion"]); 
        //---   
        return $arreglo_plan_paquetes;
    }
    /*
    *   Consultar titulo
    */
    public function consultar_titulo_servicios($id_servicios){
        $id = new MongoDB\BSON\ObjectId($id_servicios);
        $res_servicios = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id))->get("servicios");
        $titulo = $res_servicios[0]["descripcion"];
        return $titulo;
    }
    /*
    *   Consultar cantidad consumida de servicios en jornadas
    */
    public function consultar_servicio_consumido($id_servicio,$id_membresia){
        $cantidad = 0;
        //Consulto las jornadas con esa membresia
        $rs_jornadas = $this->mongo_db->where(array("eliminado"=>false,"status"=>true,'id_membresia'=>$id_membresia))->get('jornadas');
        //Recorro las jornadas asociadas a esa membresia
        foreach ($rs_jornadas as $clave_jornadas => $valor_jornadas) {
            //Si tiene servicios
            //var_dump(count($valor_jornadas["servicios"]));die('');
            if(count($valor_jornadas["servicios"])>0){
                //Recorro los servicios
                foreach ($valor_jornadas["servicios"] as $clave_serv => $valor_serv) {
                    //Si el servicio es igual al servicio en cuestion
                    if(($id_servicio==$valor_serv->id_servicio)&&($valor_serv->tipo==="contratados")){
                        $cantidad = $cantidad+$valor_serv->cantidad;
                    }
                }
                //$cantidad = 0;
            }
            //var_dump($valor_jornadas["servicios"]);die('');
        }
        return $cantidad;

    }
    /*
    *   Calcular horas jornadas
    */
    public function calcular_horas_jornadas_individual($id_jornada){
        
        $fecha = new MongoDB\BSON\UTCDateTime();
        $id = new MongoDB\BSON\ObjectId($id_jornada);
        $horas_transcurridas = "00:00:00";
        $res_jornadas = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id))->get("jornadas");
        $cont = 0;
        $e = new DateTime('00:00');
        $f = clone $e;
        //---
        /*$fecha_inicio = $res_jornadas[0]["fecha_hora_inicio"]->toDateTime();
        $fecha_hora_ini = $fecha_inicio->format('Y-m-d H:i:s');*/
        //var_dump($res_jornadas[0]["fecha_hora_inicio"]);die('');
        if(count($res_jornadas)>0){
            //-------------------------------
            $fecha_hora_ini = new DateTime(date("Y-m-d H:i:s",$res_jornadas[0]["fecha_hora_inicio"]));
            if((isset($res_jornadas[0]["fecha_hora_fin"]))&&($res_jornadas[0]["fecha_hora_fin"]!="Sin salir")){
                //$fecha_fin = $res_jornadas[0]["fecha_hora_fin"]->toDateTime();
                //$fecha_hora_fini = $fecha_fin->format('Y-m-d H:i:s');
                 $fecha_hora_fini = new DateTime(date("Y-m-d H:i:s",$res_jornadas[0]["fecha_hora_fin"]));
            }else{
                $fecha_fin = "";
                $fecha_hora_fini = new DateTime();
            }
             
            //$fecha1 = new DateTime($fecha_hora_ini);//fecha inicial
            //$fecha2 = new DateTime($fecha_hora_fini);//fecha de cierre
            $fecha1 = $fecha_hora_ini;//fecha inicial
            $fecha2 = $fecha_hora_fini;//fecha de cierre     
            
            $intervalo_siguiente=$fecha1->diff($fecha2);
            $e->add($intervalo_siguiente);
            $intervalo = $f->diff($e);
            
            if(isset($intervalo)){
                $horas_transcurridas = $intervalo->format('%H:%i:%s');
            }else{
                $horas_transcurridas = "00:00:00";
            }
            //-------------------------------
        }
        
        //--
        return $horas_transcurridas;   
    }
   
    /*
    *   Listado paquetes
    */
    public function listado_paquetes(){
        //------------------------------------------------------------------------------
        //--Modificacion con Mongo db
        //---------------------------------------------------------------------------
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get($this->tabla_paquetes);
        foreach ($resultados as $clave => $valor) {
            $auditoria = $valor["auditoria"][0];
            //var_dump($auditoria->cod_user);die('');
            //--usuario en cuestion
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            //var_dump($res_us[0]["auditoria"]->status);die('');
            //$valor["fec_regins"] = $res_us[0]["auditoria"][0]->fecha->toDateTime();
            $vector_auditoria = end($valor["auditoria"]);
            $valor["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            
            $valor["correo_usuario"] = $res_us[0]["correo_usuario"];
            $valor["status"] = $valor["status"];
            $valor["id_paquete"] = (string)$valor["_id"]->{'$id'};
            $valor["descripcion_paquete"] = (string)$valor["descripcion"]; 
           
            //-------------------------------------------------            
            $listado[] = $valor;
        }    
        //--
        $listado2 = $listado;
        return $listado2;
        //---------------------------------------------------------------------------
    }
    /*
    *   Meses en espayol
    */
    public function meses_en_espayol($mes){
        switch ($mes) {
            case 'January':
                $mes2 = 'Ene';
                break;
            case 'February':
                $mes2 = 'Febr';
                break;
            case 'March':
                $mes2 = 'Mar';
                break;
            case 'April':
                $mes2 = 'Abr';
                break; 
            case 'May':
                $mes2 = 'May';
                break; 
            case 'June':
                $mes2 = 'Jun';
                break;
            case 'July':
                $mes2 = 'Jul';
                break;
            case 'August':
                $mes2 = 'Ago';
                break;
            case 'September':
                $mes2 = 'Sep';
                break;
            case 'October':
                $mes2 = 'Oct';
                break;
            case 'November':
                $mes2 = 'Nov';
                break;
            case 'December':
                $mes2 = 'Dic';
                break;
            //------------------------    
            case 1:
                $mes2 = 'Ene';
                break;
            case 2:
                $mes2 = 'Febr';
                break;
            case 3:
                $mes2 = 'Mar';
                break;
            case 4:
                $mes2 = 'Abr';
                break; 
            case 5:
                $mes2 = 'May';
                break; 
            case 6:
                $mes2 = 'Jun';
                break;
            case 7:
                $mes2 = 'Jul';
                break;
            case 8:
                $mes2 = 'Ago';
                break;
            case 9:
                $mes2 = 'Sep';
                break;
            case 10:
                $mes2 = 'Oct';
                break;
            case 11:
                $mes2 = 'Nov';
                break;
            case 12:
                $mes2 = 'Dic';
                break;                                    
            default:
                # code...
                break;
        }
        return $mes2;
    }
    /*
    *   Meses en numeros
    */
     public function meses_en_numeros($mes){
        switch ($mes) {
            case 'Ene':
                $mes2 = '01';
                break;
            case 'Febr':
                $mes2 = '02';
                break;
            case 'Mar':
                $mes2 = '03';
                break;
            case 'Abr':
                $mes2 = '04';
                break; 
            case 'May':
                $mes2 = '05';
                break; 
            case 'Jun':
                $mes2 = '06';
                break;
            case 'Jul':
                $mes2 = '07';
                break;
            case 'Ago':
                $mes2 = '08';
                break;
            case 'Sep':
                $mes2 = '09';
                break;
            case 'Oct':
                $mes2 = '10';
                break;
            case 'Nov':
                $mes2 = '11';
                break;
            case 'Dic':
                $mes2 = '12';
                break;         
            default:
                # code...
                break;
        }
        return $mes2;
    }
    /*
    *   Consultar numero de membresia 
    */
    public function obtener_numero_membresia(){
         $rs_membresia = $this->mongo_db->limit(1)->order_by(array('_id' => 'DESC'))->get('membresia');

         if(count($rs_membresia)>0){
            $numero_membresia = (integer)$rs_membresia[0]["n_membresia"]+1;
         }else{
            $numero_membresia = 1;
         }
         return $numero_membresia;
    }
    /*
    *   Consultar numero de renovacion
    */
    public function obtener_numero_renovacion($id_cliente,$id_membresia){
        if($id_cliente!=""){
            $rs_membresia = $this->mongo_db->limit(1)->order_by(array('_id' => 'DESC','identificador_prospecto_cliente'=>$id_cliente,'cancelado'=>true))->get('membresia');
        }else if ($id_membresia!=""){
            //var_dump($id_membresia);
            $id = new MongoDB\BSON\ObjectId($id_membresia);
            $rs_membresia = $this->mongo_db->where(array('_id'=>$id,'cancelado'=>true))->get('membresia');
        }
        
        if(count($rs_membresia)>0){
            $numero_renovacion = (integer)$rs_membresia[0]["numero_renovacion"]+1;
         }else{
            $numero_renovacion = 1;
         }
         return $numero_renovacion;
    }
    /*
    *   Registro de membresía
    */
    public function registrar_membresia($data,$case=false){
        /***/
        if(!$case){
        $rs_membresia = $this->mongo_db->where_or(array('identificador_prospecto_cliente'=>$data['identificador_prospecto_cliente'],"serial_acceso"=>$data['serial_acceso']))->where(array("eliminado"=>false))->get("membresia"); 

        }else{
            $rs_membresia = $this->mongo_db->where(['identificador_prospecto_cliente'=>$data['identificador_prospecto_cliente'],"serial_acceso"=>$data['serial_acceso'],"eliminado"=>false])->get("membresia"); 
        }

        if(count($rs_membresia) == 0){
            $insertar1 = $this->mongo_db->insert("membresia", $data);
            if($case == false){
                echo json_encode("<span>La membresía se ha registrado exitosamente!</span>");
            }
        }else{
            if($case==false)
                echo "<span>¡Ya se encuentra registrada una membresía con ese serial o con ese usuario!</span>";
        }
        /***/
    }
    /*
    *   Actualizacion de membresia
    */
    public function actualizar_membresia($data){
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_membresia = new MongoDB\BSON\ObjectId($data["id_membresia"]);
        //--
        $res_membresia = $this->mongo_db->limit(1)->where(array('_id'=>$id_membresia,"eliminado"=>false))->set($data)->update("membresia");

        //var_dump($data);
        //var_dump($res_membresia);
        //die();    
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar membresia',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_membresia))->push('auditoria',$data_auditoria)->update("membresia");
        echo json_encode("<span>La membresia se ha editado exitosamente!</span>");
    }  
    /*
    *   Renovar membresia
    */
    public function renovar_membresia($data){
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));

        $id_membresia = new MongoDB\BSON\ObjectId($data["id_membresia"]);
        //--
        $res_membresia = $this->mongo_db->limit(1)->where(array('_id'=>$id_membresia,"eliminado"=>false))->set($data)->update("membresia");

        /*var_dump($data);
        var_dump($res_membresia);
        die();    */
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Renovar membresia',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id_membresia))->push('auditoria',$data_auditoria)->update("membresia");
        
        echo json_encode("La membresia ha sido renovada!");
    }   
    /*
    *   LIstado de membresía
    */
    public function listado_membresia(){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false))->get("membresia");
        $contador = 0;
        foreach ($resultados as $clave => $valor) {
            $valores = $valor;

            $valores["id_membresia"] = (string)$valor["_id"]->{'$id'};
            $valores["valor"] = number_format($valores["valor"],2);
            #Consulto datos personales
            $rfc = $valor["identificador_prospecto_cliente"];
            $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("rfc_datos_personales"=>$rfc))->get("datos_personales");
            if(count($res_dt)>0){
                $valores["nombre_datos_personales"] = $res_dt[0]["nombre_datos_personales"];
                $valores["apellido_p_datos_personales"] = $res_dt[0]["apellido_p_datos_personales"];
            }else{
                $valores["nombre_datos_personales"] = "";
            }
            #Consulto planes
            $id_planes = new MongoDB\BSON\ObjectId($valor["plan"]);
            $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
            //Debo volverlo a poner  
            //'eliminado'=>false,
            $valores["planes"] = $res_planes[0]["titulo"]." ".$res_planes[0]["descripcion"];
            
            #Consulto usuario
            $id = new MongoDB\BSON\ObjectId($valor["auditoria"][0]->cod_user);
            $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
            $vector_auditoria = end($valor["auditoria"]);
            $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
            $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
            //--
            $vector_fecha_inicio = explode("-",$valor["fecha_inicio"]);
            
            //$valores["fecha_inicio"] = $vector_fecha_inicio[2]."-".$vector_fecha_inicio[1]."-".$vector_fecha_inicio[0];

            //$vector_fecha_fin = explode("-",$valor["fecha_fin"]);

            //$valores["fecha_fin"] = $vector_fecha_fin[2]."-".$vector_fecha_fin[1]."-".$vector_fecha_fin[0];
            
            $valores["fecha_inicio"] = $valor["fecha_inicio"]->toDateTime();
            $valores["fecha_fin"] = $valor["fecha_fin"]->toDateTime();

            $contador++;
            $valores["numero"] = $contador;
            $listado[] = $valores;
        }
        return $listado;
    }
    /*
    *   Consultar serial existe
    */
    public function consultar_serial_existe($serial_acceso){
        $rs_membresia = $this->mongo_db->where(array("eliminado"=>false,"serial_acceso"=>$serial_acceso))->get("membresia"); 
        if(count($rs_membresia) == 0){
            //--Consulto cada registro de trabajador asociado
            $rs_membresia_todos = $this->mongo_db->where(array("eliminado"=>false))->get("membresia");  
            foreach ($rs_membresia_todos as $clave_membresia => $valor_membresia) {
                foreach ($valor_membresia["trabajadores"] as $clave_trabajador => $valor_trabajador) {
                    if($valor_trabajador->serial_acceso==$serial_acceso){
                        return true;
                    }
                }
            }
        }else{
            return true;
        }
        return false;
    }
    /*
    *   Registrar datos de trabajadores
    */
    public function registrar_datos_trabajadores($datos){
        $id_membresia = new MongoDB\BSON\ObjectId($datos["id_membresia"]);
        
        $rs_membresia_trabajadores = $this->mongo_db->where(array('_id'=>$id_membresia))->push('trabajadores',$datos)->update("membresia");
        
        if(count($rs_membresia_trabajadores)>0){
            echo json_encode("<span>Los datos del trabajador han sido anexado a la membresia!</span>");
        }else{
            echo json_encode("<span>Ocurrió un error inesperado!</span>");
        }
    }
    /*
    *   Actualizar datos trabajadores
    */
    public function actualizar_trabajadores($where_array,$data){
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--
        /*$res_membresia = $this->mongo_db->where(array('trabajadores.$.id_membresia'=>$id_membresia,'trabajadores.$.serial_acceso'=>$data["serial_acceso"],"trabajadores.$.eliminado"=>false))->set($data)->update("membresia.trabajadores");*/

        $res_membresia = $this->mongo_db->where($where_array)->set($data)->update("membresia");   
        //var_dump($res_membresia);die(''); 
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar trabajador membresia ',
                                        'operacion'=>''
                                );
        $mod_auditoria = $this->mongo_db->where($where_array)->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia");
        echo json_encode("<span>Los datos del trabajador se han editado exitosamente!</span>");
    }
    /*
    *   Listado membresia trabjadores
    */
    public function listado_membresia_trabajadores($id){
        $listado = [];
        $id_m = new MongoDB\BSON\ObjectId($id);
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_m))->get("membresia");
        foreach ($resultados[0]["trabajadores"] as $clave => $valor) {
            $valores = (array)$valor;
            if($valores["eliminado"]==false){
                $valores["nombre_datos_personales"] = $valores["nombre"]." ".$valores["apellido_paterno"]." ".$valores["apellido_materno"];
                $id = new MongoDB\BSON\ObjectId($valor->auditoria[0]->cod_user);
                $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
                $vector_auditoria = end($valores["auditoria"]);
                $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
                $valores["correo_datos_personales"] = $valores["correo"];
                $valores["telefono_datos_personales"] = $valores["telefono"];
                //--            
                $listado[] = $valores;
            }
        }
        return $listado;
    }
    /*
    *   status
    */
    public function status($id, $status, $tabla){
        //-------------------------------------------------------------
        //Migracion Mongo DB
        $id = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        switch ($status) {
            case '1':
                $status2 = true;
                break;
            case '2':
                $status2 = false;
                break;
        }
        $datos = array(
                        'status'=>$status2,
        );
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update($tabla);
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update($tabla); 
        }
        //-------------------------------------------------------------
    }
    /*
    *   Status trabajador
    */
    public function status_datos_trabajador($id,$status){
        //Migracion Mongo DB
        $serial = $id;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        switch ($status) {
            case '1':
                $status2 = true;
                break;
            case '2':
                $status2 = false;
                break;
        }
        $datos = array(
                        'trabajadores.$.status'=>$status2,
        );
        $modificar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
        //--Auditoria
        if($modificar){
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar status',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia"); 
        }
        //-------------------------------------------------------------
    }
    /*
    *   Status Multiple trabajador
    */
    public function status_multiple_datos_trabajador($id,$status){
        //Migracion Mongo DB
        $serial = $id;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();

        $arreglo_id = explode(' ',$id);
        
        foreach ($arreglo_id as $valor) {
            
            $serial = $valor;

            switch ($status) {
                case '1':
                    $status2 = true;
                    break;
                case '2':
                    $status2 = false;
                    break;
            }
            $datos = array(
                            'trabajadores.$.status'=>$status2,
            );
            $modificar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
            //--Auditoria
            if($modificar){
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia"); 
            }
        }    
        //-------------------------------------------------------------
    }
    /*
    *   Eliminar membresia
    */
    public function eliminar ($id, $tipo){
        switch ($tipo){
            case 'membresia':
                    //-----------------------------------------------------------------------------
                    $id = new MongoDB\BSON\ObjectId($id);

                    $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                    
                    $fecha = new MongoDB\BSON\UTCDateTime();
                    
                    $datos = array(
                                    'eliminado'=>true,
                                    );

                    $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("membresia");

                    //--Auditoria
                    if($eliminar){
                        $data_auditoria = array(
                                                    'cod_user'=>$id_usuario,
                                                    'nom_user'=>$this->session->userdata('nombre'),
                                                    'fecha'=>$fecha,
                                                    'accion'=>'Eliminar membresia',
                                                    'operacion'=>''
                                                );
                        $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("membresia"); 
                        echo json_encode("<span>La membresia se ha eliminado exitosamente!</span>"); 
                    }    
                    //----------------------------------------------------------------------------
              break;
            case 'datos_trabajadores':
                //-----------------------------------------------------------------------------
                $serial = $id;

                $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
                
                $fecha = new MongoDB\BSON\UTCDateTime();
                
                $datos = array(
                                'trabajadores.$.eliminado'=>true,
                                );

                $eliminar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
                //--Auditoria
                if($eliminar){
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar membresia',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia"); 

                    echo json_encode("<span>La membresia se ha eliminado exitosamente!</span>"); 
                }    
                //----------------------------------------------------------------------------
            break;
        }
    }      
    /*
    *   Eliminar Multiple
    */    
    public function eliminar_multiple($id_membresia){
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
       // $c=0;
        foreach($id_membresia as $membresia){
            
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            /*
            *   Valido que la membresia no tenga jornadas asociadas
            */
            //--
            $res_jornadas = $this->mongo_db->where(array("id_membresia"=>$membresia,"eliminado"=>false))->get("jornadas");
            $res_reservaciones = $this->mongo_db->where(array("id_membresia"=>$membresia,"eliminado"=>false))->get("reservaciones");
            
            /*if($c==1){
                var_dump(count($res_jornadas));
                var_dump(count($res_reservaciones));
                var_dump($res_jornadas || $res_reservaciones);
                die($c);
            }
            $c++;*/
           //$res_jornadas || 
            if($res_jornadas || $res_reservaciones){
                $noEliminados++;
            }else{
                
                //--
                $id = new MongoDB\BSON\ObjectId($membresia);
                $datos = $data=array(
                                        'eliminado'=>true,
                );
                $eliminar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("membresia");
                //--Auditoria
                if($eliminar){
                    $eliminados++;
                    $data_auditoria = array(
                                                'cod_user'=>$id_usuario,
                                                'nom_user'=>$this->session->userdata('nombre'),
                                                'fecha'=>$fecha,
                                                'accion'=>'Eliminar membresia',
                                                'operacion'=>''
                                            );
                    $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("membresia");
                }else{
                    $noEliminados++;
                } 
                //--
            }      
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }
    /*
    *   Eliminar multiple datos trabajador
    */
    public function eliminar_multiple_datos_trabajador($id){
        //--------------------------------------------------------------------------------------
        //MIGRACION MONGO DB
        $eliminados=0;
        $noEliminados=0;
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        foreach($id as $datos_trabajadores){
        //---------------------------------------------------------------------------------
        //--Migracion Mongo DB
            $serial = $datos_trabajadores;
            $datos = array(
                            'trabajadores.$.eliminado'=>true,
                            );
            $eliminar = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->set($datos)->update("membresia");
            //--Auditoria
            if($eliminar){
                $eliminados++;
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Eliminar datos trabajadores',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('trabajadores.serial_acceso'=>$serial))->push('trabajadores.$.auditoria',$data_auditoria)->update("membresia");
            }else{
                $noEliminados++;
            }   
            //----------------------------------------------------------------------------------    

        }
        echo json_encode("<span>Registros eliminados: ".$eliminados."</span><br><span>Registros no eliminados (porque tienen dependencia en otras tablas): ".$noEliminados);
        //----------------------------------------------------------------------------
    }
    /*
    *   Modificar Status
    */
    public function status_multiple_membresia($id, $status){
        //---------------------------------------------------------------------------
        //--Migracion Mongo DB
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        
        $fecha = new MongoDB\BSON\UTCDateTime();

        $arreglo_id = explode(' ',$id);
        
        foreach ($arreglo_id as $valor) {
            $id = new MongoDB\BSON\ObjectId($valor);
            //var_dump($id);die('');
            
            switch ($status) {
                case '1':
                    $status2 = true;
                    break;
                case '2':
                    $status2 = false;
                    break;
            }
            $datos = $data=array(
                                    'status'=>$status2,
            );
            $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("membresia");
            //var_dump($modificar);die('');
            //--Auditoria
            if($modificar){
                $data_auditoria = "";
                $data_auditoria = array(
                                            'cod_user'=>$id_usuario,
                                            'nom_user'=>$this->session->userdata('nombre'),
                                            'fecha'=>$fecha,
                                            'accion'=>'Modificar status membresia',
                                            'operacion'=>''
                                        );
                $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("membresia"); 
            }
        }
        //---------------------------------------------------------------------------
    }
    /*
    *   Ordenar array
    */
    public function array_sort_by(&$arrIni, $col, $order = SORT_ASC){
        $arrAux = array();
        foreach ($arrIni as $key=> $row)
        {
            $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
            $arrAux[$key] = strtolower($arrAux[$key]);
        }
        array_multisort($arrAux, $order, $arrIni);
    }
    /*
    *   ObtenerServicios
    */
    public function obtenerServicios($paquete){
        $listado = [];
        $servicios_n = [];
        $servicios_c = [];
        $id =  new MongoDB\BSON\ObjectId($paquete);
        $paquetes =  $this->mongo_db->where(array('_id'=>$id,'status'=>true,'eliminado'=>false))->get("paquetes");
        /*var_dump($planes);
        echo "</br>";
        var_dump($paquetes);
        die('');*/
        foreach ($paquetes as $clave => $valor) {
           $arreglo_servicios = $valor["servicios"]; 
           //$this->array_sort_by($arreglo_servicios,"posicion",$order = SORT_ASC);

           foreach ($arreglo_servicios as $key_serv => $value_serv) {
                //---
                if($value_serv->eliminado==false){
                    //---
                    $id = new MongoDB\BSON\ObjectId($value_serv->id_servicios);
                    //Consulto servicios
                    //#Modificacion gsantucci 13-06-2019 realizada segun tarea 123, en la que se indica que la membresia debe almacenar servicios de tipo caracter no consumible para se mostrado ne pestaña de saldos.
                    //$servicios =  $this->mongo_db->where(array('_id'=>$id,'status'=>true,'eliminado'=>false,'tipo'=>'N'))->get("servicios");
                    $servicios =  $this->mongo_db->where(array('_id'=>$id,'status'=>true,'eliminado'=>false))->get("servicios");
                    if(count($servicios)){
                       //Si el servicio es de tipo numerico 
                        if($servicios[0]["tipo"]=="N"){
                           $valores["servicios"] = (string)$servicios[0]["_id"]->{'$id'};
                           $valores["cantidad"] =  $value_serv->valor;
                           $valores["disponible"] = $value_serv->valor;
                           $valores["monto"] = $servicios[0]["monto"];
                           $servicios_n[]=$valores; 
                        }else{
                            $valores2["servicios"] = (string)$servicios[0]["_id"]->{'$id'};
                            $valores2["valor"] =  $value_serv->valor;
                            $valores2["monto"] = $servicios[0]["monto"];
                            $servicios_c[]=$valores2; 
                        }
                        //---

                    }
                    //---
                }
                
                //---
            } 
            $listado = array("servicios_n"=>$servicios_n,"servicios_c"=>$servicios_c);
           //--
           //die('');

        }
        return $listado;
    }
    /*
    *
    */
     public function listado_clientes($tipo){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'status'=>true,'tipo_persona_cliente'=>$tipo,'tipo_cliente'=>'CLIENTE'))->get("cliente_pagador");
        
        foreach ($resultados as $clave => $valor) {
            
            $valores = $valor;
            $valores["id_datos_personales"] = $valor["id_datos_personales"];
            #Consulto datos personales
            $id_dt =  new MongoDB\BSON\ObjectId($valores["id_datos_personales"]);
            $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,"_id"=>$id_dt))->get("datos_personales");
            $nombre_datos_personales = $res_dt[0]["nombre_datos_personales"];
            $rfc = $res_dt[0]["rfc_datos_personales"];
            if($rfc!=""){
                $valores["rfc"] = $rfc;
                if(isset($res_dt[0]["apellido_p_datos_personales"])){
                    $nombre_datos_personales.=" ".$res_dt[0]["apellido_p_datos_personales"];
                }

                if(isset($res_dt[0]["apellido_m_datos_personales"])){
                    $nombre_datos_personales.=" ".$res_dt[0]["apellido_m_datos_personales"];
                }

                $valores["nombre_datos_personales"] = $rfc."-".$nombre_datos_personales;
                
                $listado[] = $valores;
            }
        }
        return $listado;
    }
    /*
    * Buscar jornadas asociadas a esta membresia
    */
    public function buscarJornadas($id){
        $res_jornadas = $this->mongo_db->where(array("id_membresia"=>$id,"eliminado"=>false))->get("jornadas");
        if($res_jornadas){
            return count($res_jornadas);
        }else{
            return 0;
        }
    }
    /*
    * Buscar reservaciones asociadas a esta membresia
    */
    public function buscarReservaciones($id){
        $res_reservaciones = $this->mongo_db->where(array("id_membresia"=>$id,"eliminado"=>false))->get("reservaciones");
        if($res_reservaciones){
            return count($res_reservaciones);
        }else{
            return 0;
        }
    }        
    /*
    *
    */
    /*
    *   Consultar membresia para renovaciones...
    */
    public function consultar_membresia_renovacion($id){
        $listado = [];
        $id_renovaciones = new MongoDB\BSON\ObjectId($id);
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false,'_id'=>$id_renovaciones))->get("membresia");
        /*foreach ($resultados[0]["trabajadores"] as $clave => $valor) {
            $valores = (array)$valor;
            if($valores["eliminado"]==false){
                $valores["nombre_datos_personales"] = $valores["nombre"]." ".$valores["apellido_paterno"]." ".$valores["apellido_materno"];
                $id = new MongoDB\BSON\ObjectId($valor->auditoria[0]->cod_user);
                $res_us = $this->mongo_db->where(array('_id'=>$id))->get('usuario');
                $vector_auditoria = end($valores["auditoria"]);
                $valores["fec_regins"] = $vector_auditoria->fecha->toDateTime();
                $valores["correo_usuario"] = $res_us[0]["correo_usuario"];
                $valores["correo_datos_personales"] = $valores["correo"];
                $valores["telefono_datos_personales"] = $valores["telefono"];
                //--            
                $listado[] = $valores;
            }
        }*/
        return $resultados;
    }
    /*
    *   Registro de renovacion de membresia
    */
    public function registrar_renovacion($data_membresia,$id_membresia){

        $data =  array("ejemplo"=>"ejemplo");

        $id_renovaciones = new MongoDB\BSON\ObjectId($id_membresia);

        $renovacion = $this->mongo_db->where(array('_id'=>$id_renovaciones))->push('renovaciones',$data_membresia)->update("membresia");
        if($renovacion){
            return true;
        }
        //var_dump($renovacion);die('');
        //echo json_encode("<span>La renovación se ha realizado exitosamente!</span>");
    }
    //
    /*
    *   Cancelar
    */
    public function cancelar_membresia($id, $status){
        $id = new MongoDB\BSON\ObjectId($id);
        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        $fecha = new MongoDB\BSON\UTCDateTime();
        $datos = $data=array(
                                    'cancelado'=>$status,
        );
        $modificar = $this->mongo_db->where(array('_id'=>$id))->set($datos)->update("membresia");
        //var_dump($modificar);die('');
        //--Auditoria
        if($modificar){
            $data_auditoria = "";
            $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Cancelar membresia',
                                        'operacion'=>''
                                    );
            $mod_auditoria = $this->mongo_db->where(array('_id'=>$id))->push('auditoria',$data_auditoria)->update("membresia"); 
        }
    }    
    /***/
    /*
    *   Verificar si esta cancelada la membresia
    */
    public function consultar_membresia_cancelar($id_membresia){
        $id = new MongoDB\BSON\ObjectId($id_membresia);
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'cancelado'=>true,'_id'=>$id))->get("membresia");
        //var_dump($resultados);die('');
        if($resultados){
            return count($resultados);
        }else{
            return 0;
        }
    }
    /***/
    /*
    *   Verificar si la membresia/renovación tiene jornada activa asociada
    */
    public function consultar_existe_jornada($id_membresia,$numero_renovacion){
        //--
        $n_renovacion = (int)$numero_renovacion;
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'fecha_hora_fin' => 'Sin salir','id_membresia'=>$id_membresia,'numero_renovacion'=>$n_renovacion))->get("jornadas");
        //var_dump($resultados);die('');
        if($resultados){
            return count($resultados);
        }else{
            return 0;
        }
        //--
    }
    /***/
    /*
    *   Verificra si la membreśia/renovacion tiene reserva activa asociada
    */
    public function consultar_existe_reserva($id_membresia,$numero_renovacion){
        //--
        $n_renovacion = (int)$numero_renovacion;
        $resultados = $this->mongo_db->where_ne('condicion','LIBERADA')->where(array('eliminado'=>false,'status'=>true,'id_membresia'=>$id_membresia,'numero_renovacion'=>$n_renovacion))->get("reservaciones");
       //var_dump($id_membresia); var_dump($n_renovacion);var_dump($resultados);die('');
        if($resultados){
            return count($resultados);
        }else{
            return 0;
        }
        //--
    }
    /*
    *   Consultar renovaciones membresia...
    */
    public function consultarRenovaciones($id_membresia){
        $id = new MongoDB\BSON\ObjectId($id_membresia);
        $resultados = $this->mongo_db->where(array('eliminado'=>false,'status'=>true,'_id'=>$id))->get("membresia");
        $renovaciones = $resultados[0]["renovaciones"];
        $numero_renovacion = [];
        foreach ($renovaciones as $clave => $valor) {
            $numero_renovacion[] = "Renovación ".$valor->numero_renovacion;
        }
        $numero_renovacion[] = "Renovación actual número(".$resultados[0]["numero_renovacion"].")";
        return $numero_renovacion;
    }
    /*
    *   Validar serial editar
    */
    public function validarSerialEditar($id_membresia_actualizar,$serial){
    //---    
        $res_membresia= $this->mongo_db->where(array('eliminado'=>false,'serial_acceso' => $serial))->get("membresia");
        //var_dump($serial);die('');
        if(count($res_membresia)>0){
            $id_membresia_bd = (string)$res_membresia[0]["_id"]->{'$id'};
            //--
            if($id_membresia_bd!=$id_membresia_actualizar){
                echo "<span>Ya existe una membresia con ese serial</span>";die('');
            }
            //--
        }
    }
    //---
    /*
    *
    */


    //-----------------------------------------------------------------------------------  
    /*
    *   Buscar de membresía
    */
    public function buscar_membresia($correo, $serial){
        $listado = [];
        $resultados = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array('eliminado'=>false, 'serial_acceso' => $serial))->get("membresia");
        $contador = 0;
        $servicios_temp =  $this->mongo_db->where(array('eliminado'=>false))->get("servicios");
        $servicios = array();
        foreach ($servicios_temp as $key => $value) {
            $servicios[$value['_id']->{'$id'}] = $value['descripcion'];
        }
        foreach ($resultados as $clave => $valor) {
            $valores = $valor;

            $valores["id_membresia"] = (string)$valor["_id"]->{'$id'};
            #Consulto datos personales
            $rfc = $valor["identificador_prospecto_cliente"];
            $res_dt = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("rfc_datos_personales"=>$rfc))->get("datos_personales");
            if(count($res_dt)>0){
                $valores['datos_persona'] = $res_dt[0];
                $id_contacto = new MongoDB\BSON\ObjectId($valores['datos_persona']['id_contacto']);
                
                $res_dt1 = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_contacto))->get("contacto");
                if(count($res_dt1)>0){
                    $valores['datos_contacto'] = $res_dt1[0];
                }
            }
            $res_dt2 = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("rfc_datos_personales"=>$rfc))->get("cliente_pagador");
            if(count($res_dt2)>0){
                $valores['cliente_pagador'] = $res_dt2[0];
                $valores['cliente_pagador']['imagenCliente'] = base_url()."assets/cpanel/ClientePagador/images/".(empty($valores['cliente_pagador']['imagenCliente'])||$valores['cliente_pagador']['imagenCliente']==""?"default-img.png":$valores['cliente_pagador']['imagenCliente']);
            }else{
                $valores['cliente_pagador']['imagenCliente'] = base_url()."assets/cpanel/ClientePagador/images/default-img.png";
            }

            #Consulto planes
            $id_planes = new MongoDB\BSON\ObjectId($valor["plan"]);
            $res_planes = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_planes))->get("planes");
            //Debo volverlo a poner  
            //'eliminado'=>false,
            $valores["planes"] = $res_planes[0];

            #Consulto paquete
            $id_paquete = new MongoDB\BSON\ObjectId($valor["paquete"]);
            $res_paquete = $this->mongo_db->order_by(array('_id' => 'DESC'))->where(array("_id"=>$id_paquete))->get("paquetes");
            //Debo volverlo a poner  
            //'eliminado'=>false,
            $valores["paquetes"] = $res_paquete[0];

            //--
            $vector_fecha_inicio = explode("-",$valor["fecha_inicio"]);
            
            //$valores["fecha_inicio"] = $vector_fecha_inicio[2]."-".$vector_fecha_inicio[1]."-".$vector_fecha_inicio[0];

            //$vector_fecha_fin = explode("-",$valor["fecha_fin"]);

            //$valores["fecha_fin"] = $vector_fecha_fin[2]."-".$vector_fecha_fin[1]."-".$vector_fecha_fin[0];
            
            $valores["fecha_inicio"] = $valor["fecha_inicio"]->toDateTime();
            $valores["fecha_fin"] = $valor["fecha_fin"]->toDateTime();

            $temp_values = array();
            foreach ($valores["servicios"] as $key22 => $value22) {
                $temp_values[$key22]['servicios'] = $value22->servicios;
                $temp_values[$key22]['descripcion'] = isset($servicios[$value22->servicios])?$servicios[$value22->servicios]:"";
                $temp_values[$key22]['cantidad'] = $value22->cantidad;
                $temp_values[$key22]['disponible'] = $value22->disponible;
                $temp_values[$key22]['monto'] = $value22->monto;
            }
            $valores["servicios"] = $temp_values;

            $temp_values = array();
            foreach ($valores["servicios_c"] as $key22 => $value22) {
                $temp_values[$key22]['servicios'] = $value22->servicios;
                $temp_values[$key22]['descripcion'] = isset($servicios[$value22->servicios])?$servicios[$value22->servicios]:"";
                $temp_values[$key22]['valor'] = $value22->valor;
                $temp_values[$key22]['monto'] = $value22->monto;
            }
            $valores["servicios_c"] = $temp_values;

            unset($valores["auditoria"], $valores["renovaciones"], $valores["historial_token"], $valores['datos_persona']["auditoria"], $valores['planes']["auditoria"], $valores['paquetes']["auditoria"], $valores['paquetes']["servicios"]);

            $contador++;
            $valores["numero"] = $contador;
            if($correo == (isset($valores['datos_contacto']['correo_contacto'])?$valores['datos_contacto']['correo_contacto']:"")){
                $listado[] = $valores;
            }
        }
        return $listado;
    }
    /*
    *   Buscar Historial Token
    */
    public function historial_token_buscar($array_data)
    {
        $res = $this->mongo_db->where($array_data)->get('membresia');
        if(count($res)>0){
            return $res;
        }else{
            return array();
        }
    }
    /*
    *   Registro Historial Token
    */
    public function historial_token_registrar($datos,$id_membresia){
        
        $id_membresia = new MongoDB\BSON\ObjectId($id_membresia);
        
        $historial_token_res = $this->mongo_db->where(array('_id'=>$id_membresia))->push('historial_token',$datos)->update("membresia");
        
        if($historial_token_res){
            return true;
        }
        return false;
    }

     public function actualizar_servicios_membresia($where_array,$data){
        $fecha = new MongoDB\BSON\UTCDateTime();

        $id_usuario = new MongoDB\BSON\ObjectId($this->session->userdata('id_usuario'));
        //--
     
        //Auditoria...
        $data_auditoria = array(
                                        'cod_user'=>$id_usuario,
                                        'nom_user'=>$this->session->userdata('nombre'),
                                        'fecha'=>$fecha,
                                        'accion'=>'Modificar servicio membresia ',
                                        'operacion'=>''
                                );
     //  prp( $this->mongo_db->where($where_array)->get('membresia'),1);
        $this->mongo_db->where($where_array)->set($data)->update("membresia");
        $mod_auditoria = $this->mongo_db->where($where_array)->push('servicios.$.auditoria',$data_auditoria)->update("membresia");
      }
}    