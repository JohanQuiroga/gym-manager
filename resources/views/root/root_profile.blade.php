@extends('layout.profile_layout')
@if(Session::get('id'))
@else
<script type="text/javascript">
window.location="{{route('login')}}";
</script>
@endif
<?php
if((Session::get('id')))
{}
else
{dd(Session::get('id'));}
?>

@if(Session::get('rol') == "root")
@else
<script type="text/javascript">
window.location="{{route('inicio')}}";
</script>
@endif

<?php
use App\datopersonal;
use App\tipousuario;
use App\clasificacionusuario;

$correo=Session::get('correo');

$usuario = datopersonal::where('correo',$correo)->first();
$clasificadousuario = clasificacionusuario::where('iddatopersonal',$usuario->iddatopersonal)->first();
$tipousuario = tipousuario::where('idtipousuario',$clasificadousuario->idtipousuario)->first();
?>

@section('sidebar_options')
	<li><a href={{ route('rootPerfil')}}><i class="material-icons">account_circle</i>Perfil</a></li>
	<li><a href={{ route('rootUsuarios')}}><i class="material-icons">people</i>Usuarios</a></li>
@endsection

@section('user_type')
	Root
@endsection

@section('sidebar_user')
	<a href="#!user"><img class="circle" src="{{asset('archivos/img/userdefault.jpg')}}"></a>
	<a href="#!name"><span class="white-text name"><?php echo $usuario->nombre." ".$usuario->apellido;?></span></a>
	<a href="#!email"><span class="white-text email"><?php echo $usuario->correo;?></span></a>
@endsection

@section('profile_options')
	<div class="col s4">
		<a class="btn-floating btn-large waves-effect waves-light orange darken-4 center tooltipped" data-position="bottom" data-delay="50" data-tooltip="Editar" onclick="edit()"><i class="material-icons">mode_edit</i></a>
	</div>
@endsection

@section('profile_data')
	<meta name="_token" content="{!! csrf_token() !!}"/>
		<div class="input-field col s12 m6 l6">
			<input id="firstname" pattern="[a-zA-Z ]+" value="{{$usuario->nombre}}" onkeypress="return validarLetras(event, this.value);" name="firstname" type="text" class="validate" autocomplete="off" disabled required>
			<label for="firstname" data-error="Solo letras" >Nombre</label>
		</div>		
		<div class="input-field col s12 m6 l6">
			<input id="lastname" pattern="[a-zA-Z ]+" value="{{$usuario->apellido}}" onkeypress="return validarLetras(event, this.value);" name="lastname" type="text" class="validate" autocomplete="off" disabled required>
			<label for="lastname" data-error="Solo letras" >Apellido</label>	
		</div>
		<div class="input-field col s12 m6 l6">
		    <select id="tipodocumento" name="tipodocumento" disabled required>
		    	<option value="" disabled selected>Seleccione un tipo de documento...</option>
		    	<option value="TI">TI</option>
		    	<option value="CC">CC</option>
		    </select>
		    <label>Tipo de documento</label>
		</div>
		<div class="input-field col s12 m6 l6">
			<input id="numerodocumento" pattern="[\d]+" value="{{$usuario->numerodocumento}}" onkeypress="return validarNumeros(event, this.value);" name="numerodocumento" type="text" class="validate" autocomplete="off" disabled required>
			<a id="hiddenNumeroDocumento"></a>
			<label for="numerodocumento" data-error="Solo números">Número de documento</label>	
		</div>
		<div class="input-field col s12 m6 l6">
		    <select id="genero" name="genero" disabled required>
		    	<option value="masculino" selected>Masculino</option>
		    	<option value="femenino">Femenino</option>
		    </select>
		    <label>Género</label>
		</div>
		<div class="input-field col s12 m6 l6">
			<input id="birthdate" value="{{$usuario->fechanacimiento}}" onchange="checkDate(this.value);" name="fechanacimiento" type="date" class="datepicker" autocomplete="off"  disabled required>
			<label for="birthdate">Fecha de nacimiento</label>		
		</div>
		<div class="input-field col s12 m6 l6">
			<input id="phone" pattern="[\d]+" value="{{$usuario->telefono}}" onkeypress="return validarNumeros(event, this.value);" name="phone" type="text" class="validate" autocomplete="off" disabled required>
			<label for="phone" data-error="Solo números">Teléfono</label>
		</div>
		<div class="input-field col s12 m6 l6">
			<input id="address" value="{{$usuario->direccion}}" onkeypress="return validarDireccion(event, this.value);" name="address" type="text" class="validate" autocomplete="off" disabled required>
			<label for="address">Dirección</label>		
		</div>
		<div class="input-field col s12 m6 l6">
		    <select id="ciudad" name="ciudad" disabled required>
		    	<option value="Pereira" selected>Pereira</option>
		    </select>
		    <label>Ciudad</label>
		</div>
		<div class="input-field col s12 m6 l6">
		    <select id="pais" name="pais" disabled required>
		    	<option value="Colombia" selected>Colombia</option>
		    </select>
		    <label>País</label>
		</div>
		<div class="input-field col s12 m6 l6">
			<input id="email" pattern= "[\w|.|-]*@\w*\.[\w|.]*" value="{{$usuario->correo}}" name="email" type="email" class="validate" autocomplete="off" disabled required>
			<label for="email" data-error="Correo Electronico">Correo electrónico</label>		
		</div>
		<div id="passwordInput" name="passwordInput" class="input-field col s12 m6 l6" style='visibility:hidden;'>
			<input id="password" name="password" type="password" class="validate" autocomplete="off">
			<label for="password">Contraseña</label>
		</div>
	<div id="editDataOptions" name="editDataOptions" class="row" style='visibility:hidden;'>
		<div class="col s6">
			<a class="btn-floating btn-large waves-effect waves-light blue center" onclick="disableUserDataModification('Transaccion Cancelada!', 'red');"><i class="material-icons">clear</i></a>
		</div>
		<div class="col s6">
			<a class="btn-floating btn-large waves-effect waves-light red darken-4 center" onclick="sendModifiedProfileData();"><i class="material-icons">save</i></a>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{asset('archivos/js/dashboard_elements.js')}}"></script>
	<script type="text/javascript" src="{{asset('archivos/js/profile_init.js')}}"></script>
	
	<script type="application/javascript">
	
	var sexo='<?php echo $usuario->genero;?>';
	var documento = '<?php echo $usuario->tipodocumento;?>';
	var oldId = '<?php echo $usuario->numerodocumento;?>';
	var oldEmail = '<?php echo $usuario->correo;?>';
	
	$("#genero option[value="+ sexo +"]").attr("selected",true);
    $("#tipodocumento option[value="+ documento +"]").attr("selected",true);
    
    function validarLetras(e, contenido) {
			var aux  = true;
		    tecla = (document.all) ? e.keyCode : e.which;
		    if (tecla==8) return true;
		    patron =/[A-Za-z\s]/;
		    te = String.fromCharCode(tecla);
		    
		    if(contenido.length>=20){
		    	Materialize.toast("Ingresa máximo 20 caracteres ",5000,'red');
                aux = false;
		    }
                
		    return patron.test(te)&&aux;
		}
		
	function validarNumeros(e, contenido) {
		var aux  = true;
	    tecla = (document.all) ? e.keyCode : e.which;
	    if (tecla==8) return true;
	    patron =/^\d+$/;
	    te = String.fromCharCode(tecla);
	    
	    if(contenido.length>=20){
	    	Materialize.toast("Ingresa máximo 20 caracteres ",5000,'red');
            aux = false;
	    }
            
	    return patron.test(te)&&aux;
	}
		
	function validarDireccion(e, contenido) {
		var aux  = true;
	    tecla = (document.all) ? e.keyCode : e.which;
	    if (tecla==8) return true;
	    patron =/^[A-Za-z0-9 _]*$/;
	    te = String.fromCharCode(tecla);
	    
	    if(contenido.length>=35){
	    	Materialize.toast("Ingresa máximo 35 caracteres ",5000,'red');
            aux = false;
	    }
            
	    return patron.test(te)&&aux;
	}
		
	function checkDate(data) {
		var today =new Date();
		var inputDate = new Date(data);
		if (inputDate.value == " "){
			Materialize.toast("ingresa alguna fecha",5000,'red');
		} else if (data < "1949-12-31" || data > "1999-12-31") {
			Materialize.toast("Ingresa una fecha entre los años 1950 y 2000 ",5000,'red');
			$('#birthdate').val("1993-06-24");
		} else {
			Materialize.toast("ok",5000, 'blue');
		}
    }
    
    function edit(){
        $(":input").removeAttr('disabled');
        $('select').material_select();
        document.getElementById('passwordInput').style.visibility='visible';
        document.getElementById('editDataOptions').style.visibility='visible';
    }
    
    function disableUserDataModification (message, color) {
		$(":input").attr('disabled', true);
		document.getElementById('passwordInput').style.visibility='hidden';
        document.getElementById('editDataOptions').style.visibility='hidden';
        Materialize.updateTextFields();
		Materialize.toast(message, 5000, color);
	}
	
	function sendModifiedProfileData () {
		var numerodocumento = document.getElementById('numerodocumento');
    	var telefono = document.getElementById('phone');
    	var nombre = document.getElementById('firstname');
    	var apellido = document.getElementById('lastname');
    	var direccion = document.getElementById('address');
    	var correo = document.getElementById('email');
    	var tipodocumento = document.getElementById('tipodocumento');
    	var genero = document.getElementById('genero');
    	var fechanacimiento = document.getElementById('birthdate');
    	var contraseña = document.getElementById('password');
    	
    	var regexpNumber = /^\d+$/;
    	var regexpLetter = /^[a-zA-Z]+$/;
    	var regexpEmail = /^[\w|.|-]*@\w*\.[\w|.]*$/;
    	var regexpCompleteName = /^[a-zA-Z ]+$/;
    	
    	if((numerodocumento.value.match(regexpNumber)&&numerodocumento.required) && (telefono.value.match(regexpNumber)&&telefono.required) && (nombre.value.match(regexpCompleteName)&&nombre.required) &&
    		(apellido.value.match(regexpCompleteName)&&apellido.required) && direccion.required && (correo.value.match(regexpEmail)&&correo.required) && tipodocumento.required && genero.required && fechanacimiento.required)
    	{
    		$.ajaxSetup({
			   headers : { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
			});
    		
    		$.ajax({
				url : '{{URL::to("changeRootData")}}',
				type : 'post',
				data : {'id':oldId, 'oldEmail':oldEmail, 'numerodocumento':numerodocumento.value, 'phone':telefono.value, 'firstname':nombre.value, 'lastname':apellido.value, 'address':direccion.value, 'email':correo.value, 'genero':genero.value, 'fechanacimiento':fechanacimiento.value, 'tipodocumento':tipodocumento.value, 'password':contraseña.value },
				dataType : 'json',
				success : function (data) {
					oldEmail = data.email;
					oldId = data.numerodocumento;
					disableUserDataModification ('Cambios Realizados!', 'green');
				},
				error : function (data) {
					disableUserDataModification ('Transaccion Fallida!', 'red');
				}
			});
    	} else {
    		Materialize.toast("Todos los campos son requeridos!!", 10000, 'red');
    	}
	}
	</script>
@endsection