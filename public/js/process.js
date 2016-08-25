$(document).ready(function() {

	var ok = true;
	var gridAjax = [];
	var quantiyAjax = 0;
	var movementsAjax = [];
	var errorMessages = [];


	$("#sender").on("click", ":submit", function(e){
		e.preventDefault();
		ok = true;
		gridAjax = [];
		quantiyAjax = 0;
		movementsAjax = [];
		errorMessages = [];

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
			quantiyAjax = reviewMovementQuantity(lines[4]);

			// Validate movements
			for(i = 0; i<quantiyAjax;i++){
				movementsAjax.push(reviewWordLine(lines[i + 5], i + 5));
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
			}else{
				sweetAlert("Oops..",errorMessages.join("\n"),"error");
			}
		}else{
			sweetAlert("Oops..","Debe haber más de 5 líneas","error");
		}
	});

	// Validation
	function reviewMovementQuantity(n){
		if(n < 1 || n > 100000 || ! $.isNumeric(n)){
			errorMessages.push("Cantidad de movimientos inválida");
			ok = false;
		}
		return n;
	}

	function reviewNumbersLine(index, lines){
		var line = lines[index];
		var values = line.split(" ");
		if(values.length == 4 && areNumbers(values)){
			for(i = 0; i<values.length;i++){
				if(! isValidNumber(values[i])){
					errorMessages.push("Un número no es valido (fila:"+(index+1)+")");
					ok = false;
				}
			}
		}else{
			errorMessages.push("La cantidad de números no es la requerida (fila:"+(index+1)+")");
			ok = false;
		}
		return line;
	}

	function reviewWordLine(word, position){
		var haystack = ["Izquierda", "Derecha", "Arriba", "Abajo"];
		if(haystack.indexOf(word) === -1){
			errorMessages.push("linea "+(position)+" (debe ser Izquierda, Derecha, Arriba, Abajo)");
			ok = false;
		}
		return word;
	}

	// Return functions
	function isValidNumber(n){
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