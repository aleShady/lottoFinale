
$("#btnEsegui").on( "click", stampa);


function stampa() {
 var anno = $('#cmb_Year').val();    
 var tripla = $('input[name=tripla]:checked').val();
 var ambi = $('input[name=ambi]:checked').val();
 
 
$.ajax({ url: 'index.php',
     data: {'anno' : anno, 'tripla' : tripla, 'ambi' : ambi},
     type: 'post',
     dataType:'json',
     success: function(data) {
                 alert(data.data);
              }
});





//$.ajax({ url: 'valori_miner.php',
//         data: {'data_1' : $('#data_1').value, 
//            'ruota_1' : $('#ruota_1').value, 
//            'estratti_1' : $('#estratti_1').value,
//            'figure_1' : $('#figure_1').value, 
//            'val1_1' : $('#val1_1').value, 
//            'sopra' : $('#sopra').value,
//            'val2_1' : $('#val2_1').value, 
//            'somma_1' : $('#somma_1').value, 
//            'somma_diag_1' : $('#somma_diag_1').value,
//            'sinistra' : $('#sinistra').value, 
//            'diagonale' : $('#diagonale').value, 
//            'destra' : $('#destra').value,
//            'estrazione_2' : $('#estrazione_2').value, 
//            'data_2' : $('#data_2').value, 
//            'ruota_2' : $('#ruota_2').value,
//            'estratti_2' : $('#estratti_2').value, 
//            'figure_2' : $('#figure_2').value, 
//            'val1_2' : $('#val1_2').value,
//            'sotto' : $('#sotto').value, 
//            'val2_2' : $('#val2_2').value, 
//            'somma_2' : $('#somma_2').value,
//            'somma_diag_2' : $('#somma_diag_2').value, 
//            'somma_comune' : $('#somma_comune').value, 
//            'raddoppio_somma_comune' : $('#raddoppio_somma_comune').value },
//     type: 'post',
//     dataType:'json',
//     success: function(data) {
//                  alert(data.data);
//              }
//});

}