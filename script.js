function TablaPaciente (){
    let fecha = document.getElementById("fecha").value;    
    parametros = {
        "TablaPaciente": 1,
        "fecha" : fecha
      } 
        console.log(parametros)
        $.ajax({
            data:  parametros,
            url:   '/capturadoraF/consultas.php',
            type: 'POST',
            success:function(r){
                $('#TablaPaciente').html(r) 
            }
        })
}

function Seleccionar(x){    
    window.location.href = '/capturadoraF/capturadora.php?valor=' + x;

}

function SinTurno(){
    window.location.href = '/capturadoraF/sinTurno.php';
}




