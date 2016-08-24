$(document).ready(function() {

	var ok = true;

	$("#sender").on("click", ":submit", function(e){
		e.preventDefault();

		ok = true;

		var text = $("#rules").val();
		var lines = text.split(/\r|\r\n|\n/);

		// Validate line numbers
		if(lines.length > 5){

			// Validate numbers by line
			reviewNumbersLine(0, lines);
			reviewNumbersLine(1, lines);
			reviewNumbersLine(2, lines);
			reviewNumbersLine(3, lines);

			// Validate movements quantity
			var n = reviewMovementQuantity(4, lines);

			// Validate movements
			for(i = 0; i<n;i++){
				reviewWordLine(lines[i + 5]);
			}

			if(ok){
				console.log("Aquí va a dispararse el servicio");
			}
		}else{
			console.log("Debe haber más de 5 lineas");
		}
	});

	// Validation
	function reviewMovementQuantity(index, lines)
	{
		var n = parseInt(lines[index]);
		if(n < 1 || n > 100000){
			console.log("cantidad de movimientos invalido");
			ok = false;
		}
		return n;
	}

	function reviewNumbersLine(index, lines)
	{
		var values = lines[index].split(" ");
		if(values.length == 4 && areNumbers(values)){
			for(i = 5; i<values;i++){
				if(! power_of_2(values[i])){
					console.log("un numero no es valido (fila:"+(index+1)+")");
					ok = false;
				}
			}
		}else{
			console.log("la cantidad de numeros no es la requerida (fila:"+(index+1)+")");
			ok = false;
		}
	}

	function reviewWordLine(line)
	{
		var haystack = ["Izquierda", "Derecha", "Arriba", "Abajo"];
		if(haystack.indexOf(line) === -1){
			console.log("la palabra :"+(line)+" es incorrecta (debe ser Izquierda, Derecha, Arriba, Abajo)");
			ok = false;
		}
		console.log(line);
	}

	// Return functions
	function power_of_2(n){
		v = parseInt(n);
	    return (n && (n & (n - 1)) === 0) && n <= 2048 ;
	}

	function areNumbers(numbers){
		for(i=0;i<numbers.length;i++){
			if(! $.isNumeric(numbers[i])){
				return false;
			}
		}
		return true;
	}

});