$(document).ready(function() {

	var ok = true;
	var gridAjax = [];
	var quantiyAjax = 0;
	var movementsAjax = [];


	$("#sender").on("click", ":submit", function(e){
		e.preventDefault();
		ok = true;
		movementsAjax = [];
		gridAjax = [];
		quantiyAjax = 0;

		var text = $("#rules").val();
		var lines = text.split(/\r|\r\n|\n/);

		// Validate line numbers
		if(lines.length > 5){

			// Validate numbers by line
			gridAjax.push(reviewNumbersLine(0, lines));
			gridAjax.push(reviewNumbersLine(1, lines));
			gridAjax.push(reviewNumbersLine(2, lines));
			gridAjax.push(reviewNumbersLine(3, lines));

			// Validate movements quantity
			quantiyAjax = reviewMovementQuantity(4, lines);

			// Validate movements
			for(i = 0; i<quantiyAjax;i++){
				movementsAjax.push(reviewWordLine(lines[i + 5]));
			}

			if(ok){
				$.ajax({ type: "POST", url: "http://0.0.0.0:8080/process",
				    data: { grid: gridAjax, quantity: quantiyAjax, movements: movementsAjax},
				    success: function(data) {
						if(data.result){
							$("#result").val(data.grid);
						}
				    }
				});
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
		var line = lines[index];
		var values = line.split(" ");
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
		return line;
	}

	function reviewWordLine(line)
	{
		var haystack = ["Izquierda", "Derecha", "Arriba", "Abajo"];
		if(haystack.indexOf(line) === -1){
			console.log("la palabra :"+(line)+" es incorrecta (debe ser Izquierda, Derecha, Arriba, Abajo)");
			ok = false;
		}
		return line;
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