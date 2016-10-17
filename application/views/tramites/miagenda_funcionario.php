<?php
    function transformarFecha($fecha){
        $mes='';
        switch($fecha[0]){
            case 1:
                $mes='Enero';
            break;
            case 2:
                $mes='Febrero';
            break;
            case 3:
                $mes='Marzo';
            break;
            case 4:
                $mes='Abril';
            break;
            case 5:
                $mes='Mayo';
            break;
            case 6:
                $mes='Junio';
            break;
            case 7:
                $mes='Julio';
            break;
            case 8:
                $mes='Agosto';
            break;
            case 9:
                $mes='Septiembre';
            break;
            case 10:
                $mes='Octubre';
            break;
            case 11:
                $mes='Noviembre';
            break;
            case 12:
                $mes='Diciembre';
            break;

        }
        $val=$fecha[1].' de '.$mes.' de '.$fecha[2];
        return $val;
    }
   if(isset($idagenda) && is_numeric($idagenda) && $idagenda!=0){
      $defaultid=$idagenda;
   }else{
      $defaultid=(isset($agendas) && is_array($agendas))?$agendas[0]->id:0;
   }
?>
<link rel="stylesheet" href= "<?= base_url('assets/css/jquery-ui.css') ?>" >
<link rel="stylesheet" href= "<?= base_url('assets/calendar/css/calendar.css') ?>" >
<link rel="stylesheet" href= "<?= base_url('assets/css/bootstrap-datetimepicker.min.css') ?>" >
<script src= "<?= base_url('/assets/js/jquery-ui/js/jquery-ui.js') ?>"></script>
<script src= "<?= base_url('/assets/js/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/calendar/components/underscore/underscore-min.js') ?>"></script>
<!-- <script type="text/javascript" src="<?= base_url('assets/calendar/components/bootstrap2/js/bootstrap.min.js') ?>"></script> -->
<script type="text/javascript" src="<?= base_url('assets/calendar/components/jstimezonedetect/jstz.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/calendar/js/language/es-CO.js') ?>"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src= "<?= base_url('/assets/calendar/js/moment-2.2.1.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/calendar/js/calendar-custom.js?v=0.5') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/calendarfront.js?v=0.19') ?>"></script>
<script src="<?= base_url() ?>assets/js/collapse.js"></script>
<script src="<?= base_url() ?>assets/js/transition.js"></script>

<h2>Mis citas</h2>
<form method="POST" id="formcitasfunc" action="<?=site_url('/tramites/miagenda')?>">
  <input type="hidden" id="txtidagenda" name="idagenda" value="<?= $defaultid ?>" >
  <?php
     if(isset($agendas) && count($agendas)>1){
        echo '
           <label>Agenda</label>
           <select id="cmbagenda" name="cmbagenda">';
              if(isset($agendas) && $agendas){
                 foreach($agendas as $item){
                    if($defaultid==$item->id){
                       echo '<option selected="selected" value="'.$item->id.'">'.$item->name.'</option>';
                    }else{
                       echo '<option value="'.$item->id.'">'.$item->name.'</option>';    
                    }
                 }
              }
        echo '</select>';
     }else{
      echo '<input id="cmbagenda" type="hidden" name="cmbagenda" value="'.$agendas[0]->id.'" />';
     }
  ?>
  <div id="tabs" class="containter-tabs-citas" style="display:none">
    <ul>
      <li><a href="#tabs-1">Hoy</a></li>
      <li><a href="#tabs-2">Citas</a></li>
    </ul>
      <div id="tabs-1" class="tabs-func" >
        <h2><?php 
           $fecha=explode('/',date('m/d/Y'));
           echo transformarFecha($fecha); 
        ?></h2>
           <div class="containter-tab-agenda">
              <table class="table js-tab-agenda" >
                 <thead>
                    <tr>
                       <th>Tramite</th>
                       <th>Solicitante</th>
                       <th>Cuando</th>
                       <th>Asistencia</th>
                       <th>Acciones</th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php
                    if(isset($data) && is_array($data) && count($data)>0){
                       $swnocitas=true;
                       foreach($data as $item){
                          $fecha=date('d/m/Y H:i:s',strtotime($item->appointment_time));
                          $tmp=explode(' ',$fecha);
                          $fe=explode('/',$tmp[0]);
                          $fechaparam=$fe[0].'-'.$fe[1].'-'.$fe[2];
                          $hoy=date('d-m-Y');
                          $hora=date('H:i',strtotime($item->appointment_time));
                          if($fechaparam==$hoy){
                            $solicitante=(isset($item->applyer_name) && !empty($item->applyer_name))?$item->applyer_name:'Anonimo';

                            $acsi=(isset($item->applyer_attended) && $item->applyer_attended==1)?'active':'';
                            $acno=(isset($item->applyer_attended) && $item->applyer_attended==0)?'active':'';
                            $acciones='<a class="btn btn-primary" onclick="ver_cita('.$item->appointment_id.',\''.$solicitante.'\',\''.$fechaparam.'\',\''.$hora.'\',\''.$item->tramite.'\',\''.$item->applyer_email.'\')" href="#"><i class="icon-white icon-edit"></i> Ver</a> <a class="btn btn-danger btncanappofun" href="#" onclick="cancelarCita(\''.$item->appointment_id.'\',\''.$fechaparam.'\');"><i class="icon-white icon-remove"></i> Cancelar</a>';
                            $asistencia='<a class="btn '.$acsi.' js-sia" data-idcita="'.$item->appointment_id.'" onclick="asistio(1,'.$item->appointment_id.','.$item->idtramite.','.$item->calendar_id.');" href="#">Si</a> <a class="btn '.$acno.' js-noa" data-idcita="'.$item->appointment_id.'" href="#" onclick="asistio(0,'.$item->appointment_id.','.$item->idtramite.','.$item->calendar_id.');" >No</a>';
                            echo '<tr><td>'.$item->tramite.'</td><td>'.$solicitante.'</td><td>'.$hora.'</td><td>'.$asistencia.'</td><td>'.$acciones.'</td></tr>';
                          }
                          $swnocitas=false;
                       }
                       if($swnocitas){
                          echo '<tr><td colspan="5" style="text-align: center;" >No existen citas agendadas</td></tr>';   
                       }
                    }else{
                       echo '<tr><td colspan="5" style="text-align: center;" >No existen citas agendadas</td></tr>';
                    }
                    ?>
                 </tbody>
              </table>
           </div>
           <input type="hidden" id="pagina" name="pagina" value="<?= $pagina ?>" />
           <div id="paginador" class="clearfix cls-pagfunc">
              <ul class="pagination" style="max-width: 255px;">
                 <li><a onclick="recargarPagina(1);">&laquo;</a></li>
                 <?php
                 for($i=$pagina_desde;$i<=$pagina_hasta;$i++){
                    if($i>0 && $i<=$total_paginas){
                       echo '<li><a onclick="recargarPagina('.$i.');">'.$i.'</a></li>';
                    }
                 }
                 ?>
                 <li><a onclick="recargarPagina(<?= $total_paginas ?>);">&raquo;</a></li>
              </ul>
           </div>
       
      </div>
      <div id="tabs-2" class="con-tab-cal">
        <div class="containter-calendar">
           <input type="hidden" id="urlbase" value="<?= base_url() ?>" />
           <div class="page-header">
              <div class="pull-right form-inline">
                 <div class="btn-group">
                    <button type="button" class="btn" data-calendar-nav="prev"><<</button>
                    <button type="button" class="btn btn-primary" data-calendar-nav="today">Hoy</button>
                    <button type="button" class="btn" data-calendar-nav="next">>></button>
                 </div>
                 <div class="btn-group">
                    <button type="button" class="btn" data-calendar-view="year">A&ntilde;o</button>
                    <button type="button" class="btn active" data-calendar-view="month">Mes</button>
                    <!-- <button class="btn btn-warning" data-calendar-view="week">Semana</button> -->
                    <button type="button" class="btn" data-calendar-view="day">D&iacute;a</button>
                 </div>
              </div>
              <h3></h3>
           </div>
          <div id="calendar" class="calendar cal-front-fun"></div>
        </div>
        <div style="margin-top: 30px; display:none;">
          <div class="clearfix">
            <div class="circ event-info float-left"></div><span>&nbsp;&nbsp;&nbsp;Este color identifica a las citas reservadas. Pasando el cursor sobre ellas podra ver el detalle de la cita </span>
          </div>
          <div class="clearfix">
            <div class="circ event-warning float-left"></div><span>&nbsp;&nbsp;&nbsp;Este color identifica la disponibilidad de citas.</span>
          </div>
          <div class="clearfix">
            <div class="circ event-success float-left"></div><span>&nbsp;&nbsp;&nbsp;Este color identifica cuando una franja horaria esta bloqueada. Aqui no se prodran reservar citas.</span>
          </div>
          <div class="clearfix">
            <div class="circ-blank float-left"></div><span>&nbsp;&nbsp;&nbsp;Al presionar 1 clic sobre un dia del calendario, vera el detalle del dia.</span>
          </div>
          <div class="clearfix">
            <div class="circ-blank float-left"></div><span>&nbsp;&nbsp;&nbsp;Al presionar 2 clic sobre un dia del calendario, cambiara a vista de dia.</span>
          </div>
          <div class="clearfix">
            <span style="margin-left:3px; cursor: inherit;" class="glyphicon glyphicon glyphicon-ban-circle cursor" aria-hidden="true"></span><span>&nbsp;&nbsp;&nbsp;Para bloquear la disponibilidad de cita presionar clic sobre el icono en la visa de dia.</span>
          </div>
          <div class="clearfix">
            <span style="margin-left:3px; cursor: inherit;" class="glyphicon glyphicon glyphicon-remove-circle cursor" aria-hidden="true"></span><span>&nbsp;&nbsp;&nbsp;Para eliminar un bloqueo de cita presionar clic sobre el icono en la visa de dia.</span>
          </div>
        </div>
      </div>
  </div>
</form>
<center><div id="ajaxLoaderfuncini" class='ajaxLoader ajaxLoaderfuncini'>Cargando</div></center>
<form id="formvercita" method="GET" style="position:relative" method="<?= base_url('backend/agendasusuario/ajax_modal_ver_cita_funcionario/') ?>">
  <input type="hidden" id="ver_solicitante" name="soli" >
  <input type="hidden" id="ver_dia" name="dia" >
  <input type="hidden" id="ver_hora" name="hora" >
  <input type="hidden" id="ver_tramite" name="tramite" >
  <input type="hidden" id="ver_email" name="email" >
</form>
<script type="text/javascript">
   var calendars = {};
   $(function(){
      $('#cmbagenda').change(function(){
         var pagina=$('#pagina').val();
         var form=$('#tabs');
         $(form).append("<div class='ajaxLoader ajaxLoaderfunc'>Cargando</div>");
         var ajaxLoader=$(form).find(".ajaxLoader");
         $(ajaxLoader).css({
            left: ($(form).width()/2 - $(ajaxLoader).width()/2)+"px", 
            top: ($(form).height()/2 - $(ajaxLoader).height()/2)+"px"
         });
         recargarPagina(pagina);
      });
   });
   //Cuando se escoje una de las fechas se crea el otro calendario estimando tiempos limites.
   function crearObjetoFecha(id1,id2,e){
      var val=jQuery('#cont-cal'+id2).find('.js-date').val();
      jQuery('#datetimepicker'+id2).off('dp.change');
      $('#cont-cal'+id2).html('<div class=\'input-group date\' id=\'datetimepicker'+id2+'\'><input type=\'text\' readonly="readonly" name="fecha'+id2+'" class="form-control js-date" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>');
      if(id2==2){
         jQuery('#datetimepicker'+id2).datetimepicker({
            ignoreReadonly: true,
            format: 'DD/MM/YYYY HH:mm',
            minDate: new Date(e.date._d)
         }).on("dp.change", function(e) {
            crearObjetoFecha(id2,id1,e);
        });
      }else{
         var mindate=new Date();
         var maxdate=new Date(e.date._d);
         minutes=1;//munutos
         maxdate=maxdate.getTime()+minutes*60000;//adiciona minutos para que la fecha minima no sea igual a la maxima
         jQuery('#datetimepicker'+id2).datetimepicker({
            ignoreReadonly: true,
            format: 'DD/MM/YYYY HH:mm',
            minDate: mindate,
            maxDate: maxdate
         }).on("dp.change", function(e) {
            crearObjetoFecha(id2,id1,e);
        });
      }
      $('#cont-cal'+id2).find('.js-date').val(val);
   }
   function editar_cita(idcita){
      var cal=$('#cmbagenda').val();
      //$("#modalcancelar").load(site_url + "backend/agendasusuario/ajax_modal_editar_cita/" + idcita+'?idagenda='+cal);
      $("#modalcancelar").load(site_url + "backend/agendasusuario/ajax_modal_editar_cita_funcionario/" + idcita+'?idagenda='+cal);
      $("#modalcancelar").modal();
   }
    function ver_cita(idcita,solicitante,dia,hora,tramite,correo){
      $('#ver_solicitante').val(solicitante);
      $('#ver_dia').val(dia);
      $('#ver_hora').val(hora);
      $('#ver_tramite').val(tramite);
      $('#ver_email').val(correo);
      var param=$('#formvercita').serialize();
      $("#modalcancelar").load(site_url + "backend/agendasusuario/ajax_modal_ver_cita_funcionario/" + idcita+'?'+param);
      $("#modalcancelar").modal();
    }
   function cancelarCita(id,fecha) {
      $("#modalcancelar").load(site_url + "backend/agendasusuario/ajax_cancelar_cita?id="+id+"&fecha="+fecha+"&func=1");
      $("#modalcancelar").modal();
      return false;
   } 
   function recargarPagina(pagina){
      var urlbase='<?=site_url('/tramites/miagenda')?>';
      $("#formcitasfunc").attr('action',urlbase+'/'+pagina);
      $("#formcitasfunc").submit();
   }
   function asistio(asistio,idcita,idtramite,calid){
      ajax_asistencia(asistio,idcita,idtramite,calid);
      /*$("#modalcancelar").load(site_url + "backend/agendasusuario/ajax_asistencia/" + asistio+"/"+idcita+"/"+idtramite+"/"+calid);
      $("#modalcancelar").modal();*/
   }
  function ajax_asistencia(asistencia,idcita,idtramite,calendario){
    var form=$('#tabs');
    $(form).append("<div class='ajaxLoader ajaxLoaderfunc'>Cargando</div>");
      var ajaxLoader=$(form).find(".ajaxLoader");
      $(ajaxLoader).css({
        left: ($(form).width()/2 - $(ajaxLoader).width()/2)+"px", 
        top: ($(form).height()/2 - $(ajaxLoader).height()/2)+"px"
    });
    $('.validacion').html('');
    $.ajax({
        url:'<?= base_url('backend/agendasusuario/ajax_confirmo_asistencia') ?>',
        data:{
            idcita:idcita,
            asistencia:asistencia,
            idtramite:idtramite,
            calendario:calendario
        },
        dataType: "json",
        success: function( data ) {
            if(data.code==200){
                if(asistencia==1){
                    $.each($('.js-sia'),function(index,value){
                        if($(this).attr('data-idcita')==idcita){
                            $("a[data-idcita='"+idcita+"']").removeClass('active');
                            $(this).addClass('active');
                        }
                    });
                }else{
                    $.each($('.js-noa'),function(index,value){
                        if($(this).attr('data-idcita')==idcita){
                            $("a[data-idcita='"+idcita+"']").removeClass('active');
                            $(this).addClass('active');
                        }
                    });
                }
            }else{
                $('.validacion').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>'+data.message+' .</div>');
            }
            $(".ajaxLoader").remove();
        }
    });
  }
   $('#btnbloqueo').click(function(){
      var agenda=$('#cmbagendabloq').val();
      if(agenda!=''){
         $('.validacionbloq').html('');
         var fei=$('#cont-cal1').find('.js-date').val();
         var fef=$('#cont-cal2').find('.js-date').val();
         if(fei!='' && fef!=''){
            var val=$('#formbloqueo').serialize();
            $("#modalcancelar").load(site_url + "tramites/ajax_confirmar_agregar_bloqueo?"+val);
            $("#modalcancelar").modal();   
         }else{
            $('.validacionbloq').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>Debe seleccionar rango de fechas/horas.</div>');
         }
      }else{
         $('.validacionbloq').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>Debe seleccionar una agenda.</div>');
      }
      
      
   });
</script>
<div id="modalcancelar" class="modal hide fade"></div>
<div id="modalasistencia" class="modal hide fade"></div>