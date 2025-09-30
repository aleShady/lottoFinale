var type, index, lengths, result, firstTime, values;
$(document).ready(function () {

    type = 'destroso';
    index = 0;
    lengths = {
        "destroso": 0,
        "sinistroso": 0
    };
    firstTime = true;
    
    $("#btnRicerca").click(function(){
    
   $('.spinner').show();
     $.ajax({
            url: 'stampa.php',
          
dataType: 'json',
            data:{
            model: JSON.stringify({
               
                "currentYear": $('#cmb_Year').val()
            })
        },
            success: function (elencoSestine) {
                
                     $('.spinner').hide();   

                       $('#sestine').DataTable().clear();
            
            var jsdata = JSON.parse(elencoSestine);
            if(jsdata.length > 0)
                $('#sestine').dataTable().fnAddData(jsdata);
            $('#sestine').DataTable().draw();

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $('.spinner').hide();   

            alert('Errore');
            
        }
        }).done(function() {
       $('#noData').hide();  
         $('.spinner').hide();   
     });
                  

});
    $('#btnEsegui').click(btnEsegui_Click);
        $('#btnPagSestine').click(btnPagSestine_Click);

    $('#btnSinistroso').click(function () {
        $('#btnSinistroso').addClass('typeActive');
        $('#btnDestroso').removeClass('typeActive');
        type = 'sinistroso';
        index = 0;
        updateValues()
    });
    $('#btnDestroso').click(function () {
        $('#btnSinistroso').removeClass('typeActive');
        $('#btnDestroso').addClass('typeActive');
        type = 'destroso';
        index = 0;
        updateValues()
    });
    $('#btnNext').click(function () {
        index++;
        if (index == lengths[type]) {
            index = 0
        };
        updateValues()
    });
    $('#btnPrev').click(function () {
        if (index == 0) {
            index = lengths[type] - 1
        } else {
            index--
        };
        updateValues()
    });
    $('#actual').keypress(function (a) {
        var b = a.which;
        if (b == 13) {
            index = $('#actual').val() - 1;
            updateValues();
            return false
        }
    });
    $('#btnSingola').click(stampa)
});
function btnEsegui_Click() {
    $.ajax({
        url: 'valori_miner.php',
        dataType: 'JSON',
            type: 'POST',
        data: {
            model: generateModel()
        },
        success: function (d) {
            result = d;
            lengths.destroso = result.destroso.length;
            lengths.sinistroso = result.sinistroso.length;
            index = 0;
            updateValues()
        }
    })
}

function btnPagSestine_Click() {
   stampaPagSestine();
}
function generateModelSestine(res) {
    return JSON.stringify({
        "sestine": res
        
    })
}
function generateModel() {
    return JSON.stringify({
        "year": $('#cmb_Year').val(),
        "ambo": $('input[name=ambi]:checked', '#toolbar').val(),
        "tripla": $('input[name=tripla]:checked', '#toolbar').val()
    })
}
function updateValues() {
    addValues(1);
    addValues(2);
    $('#somma_comune').text(result[type][index].somma_comune);
    $('#raddoppio_somma_comune').text(result[type][index].raddoppio_somma_comune);
    $('#diagonale').text(result[type][index].diagonale);
    $('#sopra').text(result[type][index].sopra);
    $('#destra').text(result[type][index].destra);
    $('#sotto').text(result[type][index].sotto);
    $('#sinistra').text(result[type][index].sinistra);
    updateCounter();
    if (firstTime === true) {
        $('#myBoard').fadeToggle('slow');
        firstTime = false
    }
}
function addValues(c) {
    $('#estrazione_' + c).text(result[type][index]['estrazione_' + c]);
    $('#data_' + c).text(result[type][index]['data_' + c]);
    $('#ruota_' + c).text(result[type][index]['ruota_' + c]);
    $('#estratti_' + c).text('(' + result[type][index]['distanza_' + c] + ')');
    $('#figure_' + c).text('(' + result[type][index]['trip_' + c] + ')');
    $('#val1_' + c).text(result[type][index]['val1_' + c]);
    $('#val2_' + c).text(result[type][index]['val2_' + c]);
    $('#somma_' + c).text(result[type][index]['somma_' + c]);
    $('#somma_diag_' + c).text(result[type][index]['somma_diag_' + c])
}
function updateCounter() {
    $('#actual').val(index + 1);
    $('#total').text(lengths[type])
}
function stampa() {
    values = [result[type][index]];
    var x = $('#printer');
    x.html(getHTML(0));
    x.print();


}

function stampaPagSestine(){
     $('.spinner').show();     
        $.ajax({
            url: 'sestine.php',
          

            data:{
            model: JSON.stringify({
               
                "currentYear": $('#cmb_Year').val()
            })
        },
            success: function (d) {
                result = d;    
                     $('.spinner').hide();   

                        alert('Dati aggiornati');

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    $('.spinner').hide();   

            alert('Errore');
            
        }
        }).done(function() {
       $('#noData').hide();  
         $('.spinner').hide();   
     });

     
}
function generateModelSest(anno,tripla) {
    return JSON.stringify({
        "year": anno.toString(),
        "ambo": "uniti",
        "tripla": tripla
    })
}


function getHTML(index) {
    var k;
    var m = [0, 0, 0, 0, 0, 0];
    var l = [0, 0, 0, 0, 0, 0];
    var o = [values[index].somma_1, values[index].somma_diag_1, values[index].somma_2, values[index].somma_diag_2, values[index].somma_comune, values[index].raddoppio_somma_comune];
    var n = [values[index].somma_1, values[index].somma_diag_1, values[index].somma_2, values[index].somma_diag_2, values[index].somma_comune, values[index].raddoppio_somma_comune];
    k = '<div class="item">';
    k += '<div class="header">';
    k += 'FORMAZIONE SINGOLA AMBI DISTANZA 3 DI ORDINE ' + type;
    k += '</div>';
    k += '<div class="chart">';
    k += '<table class="oldTable" align="center" cellpadding="2" cellspacing="0">';
    k += '<tr>';
    k += '<td>N:</td>';
    k += '<td>' + values[index].estrazione_1 + '</td>';
    k += '<td>del</td>';
    k += '<td style="width:130px">' + values[index].data_1 + '</td>';
    k += '<td style="padding-right:20px">' + values[index].ruota_1 + '</td>';
    k += '<td style="padding-right:15px">(' + values[index].distanza_1 + ')</td>';
    k += '<td style="padding-right:70px">(' + values[index].trip_1 + ')</td>';
    k += '<td class="estratto" style="text-align:right;padding-right:60px;">' + values[index].val1_1 + '</td>';
    k += '<td class="contorno" style="text-align:right;padding-right:50px;">' + values[index].sopra + '</td>';
    k += '<td class="estratto borderRight" style="text-align:right;padding-right:20px;">' + values[index].val2_1 + '</td>';
    k += '<td class="color1" style="text-align:right;padding-left:20px;font-size:25px;">' + values[index].somma_1 + '</td>';
    k += '<td class="color2" style="text-align:right;padding-left:30px;font-size:25px;">' + values[index].somma_diag_1 + '</td>';
    k += '</tr>';
    k += '<tr>';
    k += '<td class="contorno" colspan="8" style="text-align:right;padding-right:60px;">' + values[index].sinistra + '</td>';
    k += '<td class="diagonale" style="text-align:right;padding-right:42px;">' + values[index].diagonale + '</td>';
    k += '<td class="contorno borderRight" style="text-align:right;padding-right:20px;">' + values[index].destra + '</td>';
    k += '</tr>';
    k += '<tr>';
    k += '<td class="borderBottom">N:</td>';
    k += '<td class="borderBottom">' + values[index].estrazione_2 + '</td>';
    k += '<td class="borderBottom">del</td>';
    k += '<td class="borderBottom" style="width:130px">' + values[index].data_2 + '</td>';
    k += '<td class="borderBottom" style="padding-right:20px">' + values[index].ruota_2 + '</td>';
    k += '<td class="borderBottom" style="padding-right:15px">(' + values[index].distanza_2 + ')</td>';
    k += '<td class="borderBottom" style="padding-right:70px">(' + values[index].trip_2 + ')</td>';
    k += '<td class="estratto borderBottom" style="text-align:right;padding-right:60px;">' + values[index].val1_2 + '</td>';
    k += '<td class="contorno borderBottom" style="text-align:right;padding-right:50px;">' + values[index].sotto + '</td>';
    k += '<td class="estratto borderBottom borderRight" style="text-align:right;padding-right:20px;">' + values[index].val2_2 + '</td>';
    k += '<td class="color3 borderBottom" style="text-align:right;padding-left:20px;font-size:25px;">' + values[index].somma_2 + '</td>';
    k += '<td class="color4 borderBottom" style="text-align:right;padding-left:30px;font-size:25px;">' + values[index].somma_diag_2 + '</td>';
    k += '</tr>';
    k += '<tr>';
    k += '<td class="color5 borderRight" style="text-align:right; padding-right:92px;font-size:25px;" colspan="10">' + values[index].somma_comune + '</td>';
    k += '<td class="color6" style="text-align:center;font-size:25px;" colspan="3">' + values[index].raddoppio_somma_comune + '</td>';
    k += '</tr>';
    k += '</table>';
    k += '</div>';
    k += '<div class="grid">';
    k += '<table cellpadding="0" cellspacing="0">';
    k += '<tr>';
    k += '<td width="40px">N</td>';
    k += '<td width="100px">Data</td>';
    k += '<td colspan="5"><center>' + values[index].ruota_1 + '</center></td>';
    k += '<td width="100px"></td>';
    k += '<td colspan="5"><center>' + values[index].ruota_2 + '</center></td>';
    k += '</tr>';
    k += '<tr>';
    k += '<td height="10px" colspan="13"></td>';
    k += '</tr>';
    $.ajax({
        url: 'history_miner.php',
        dataType: 'JSON',
        data: {
            model: JSON.stringify({
                estrazione: getMax(values[index].estrazione_2, values[index].estrazione_1),
                ruota1: values[index].ruota_1,
                ruota2: values[index].ruota_2,
                "year": $('#cmb_Year').val()
            })
        },
        async: false,
        success: function (d) {
            var p = 0;
            for (var b in d[values[index].ruota_1]) {
                if (p < 25) {
                    calculateOccurence([d[values[index].ruota_1][b].uno, d[values[index].ruota_1][b].due, d[values[index].ruota_1][b].tre, d[values[index].ruota_1][b].quattro, d[values[index].ruota_1][b].cinque], o, m);
                    calculateOccurence([d[values[index].ruota_2][b].uno, d[values[index].ruota_2][b].due, d[values[index].ruota_2][b].tre, d[values[index].ruota_2][b].quattro, d[values[index].ruota_2][b].cinque], n, l)
                };
                k += '<tr>';
                k += '<td>' + d[values[index].ruota_1][b].estrazione + '</td>';
                k += '<td width="115px">' + d[values[index].ruota_1][b].data + '</td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_1][b].uno, index) + '</td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_1][b].due, index) + '</td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_1][b].tre, index) + '</td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_1][b].quattro, index) + '</td>';
                k += '<td width="25px"><center>' + checkValidated(d[values[index].ruota_1][b].cinque, index) + '</center></td>';
                k += '<td width="150px"></td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_2][b].uno, index) + '</td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_2][b].due, index) + '</td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_2][b].tre, index) + '</td>';
                k += '<td class="historyBorder">' + checkValidated(d[values[index].ruota_2][b].quattro, index) + '</td>';
                k += '<td width="25px"><center>' + checkValidated(d[values[index].ruota_2][b].cinque, index) + '</center></td>';
                k += '</tr>';
                if (p++ === 24) {
                    k += '<tr>';
                    k += '<td style="border-top:1px solid black;" colspan="13"></td>';
                    k += '</tr>'
                }
            }
        }
    });
    k += '</table>';
    k += '</div>';
    k += '<div style="position:absolute;top:205px;left:325px;">';
    orderVector(m, o);
    for (KK = 0; KK < 6; KK++) {
        k += m[KK] + ' (' + checkValidated(o[KK], index) + ')<br>'
    };
    k += '</div>';
    k += '<div style="font-size:12px;position:absolute;top:330px;left:320px;">';
    k += 'A_B_C = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_2, index) + ' = ' + giveMe(m, o, values[index].somma_comune) + '_' + giveMe(m, o, values[index].raddoppio_somma_comune) + '_' + giveMe(m, o, values[index].somma_2) + '<br>';
    k += 'A_B_D = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_1, index) + ' = ' + giveMe(m, o, values[index].somma_comune) + '_' + giveMe(m, o, values[index].raddoppio_somma_comune) + '_' + giveMe(m, o, values[index].somma_1) + '<br>';
    k += 'B_C_D = ' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_2, index) + '_' + checkValidated(values[index].somma_1, index) + ' = ' + giveMe(m, o, values[index].raddoppio_somma_comune) + '_' + giveMe(m, o, values[index].somma_2) + '_' + giveMe(m, o, values[index].somma_1) + '<br><br>';
    k += 'A_B_F = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_diag_1, index) + ' = ' + giveMe(m, o, values[index].somma_comune) + '_' + giveMe(m, o, values[index].raddoppio_somma_comune) + '_' + giveMe(m, o, values[index].somma_diag_1) + '<br>';
    k += 'A_B_E = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_diag_2, index) + ' = ' + giveMe(m, o, values[index].somma_comune) + '_' + giveMe(m, o, values[index].raddoppio_somma_comune) + '_' + giveMe(m, o, values[index].somma_diag_2) + '<br>';
    k += 'B_F_E = ' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_diag_1, index) + '_' + checkValidated(values[index].somma_diag_2, index) + ' = ' + giveMe(m, o, values[index].raddoppio_somma_comune) + '_' + giveMe(m, o, values[index].somma_diag_1) + '_' + giveMe(m, o, values[index].somma_diag_2) + '<br><br>';
    k += 'C_D_E = ' + checkValidated(values[index].somma_2, index) + '_' + checkValidated(values[index].somma_1, index) + '_' + checkValidated(values[index].somma_diag_2, index) + ' = ' + giveMe(m, o, values[index].somma_2) + '_' + giveMe(m, o, values[index].somma_1) + '_' + giveMe(m, o, values[index].somma_diag_2) + '<br>';
    k += 'C_D_F = ' + checkValidated(values[index].somma_2, index) + '_' + checkValidated(values[index].somma_1, index) + '_' + checkValidated(values[index].somma_diag_1, index) + ' = ' + giveMe(m, o, values[index].somma_2) + '_' + giveMe(m, o, values[index].somma_1) + '_' + giveMe(m, o, values[index].somma_diag_1) + '<br>';
    k += 'D_E_F = ' + checkValidated(values[index].somma_1, index) + '_' + checkValidated(values[index].somma_diag_2, index) + '_' + checkValidated(values[index].somma_diag_1, index) + ' = ' + giveMe(m, o, values[index].somma_1) + '_' + giveMe(m, o, values[index].somma_diag_2) + '_' + giveMe(m, o, values[index].somma_diag_1) + '<br><br>';
    k += '</div>';
    k += '<div style="position:absolute;top:205px;left:600px;">';
    orderVector(l, n);
    for (KK = 0; KK < 6; KK++) {
        k += l[KK] + ' (' + checkValidated(n[KK], index) + ')<br>'
    };
    k += '</div>';
    k += '<div style="font-size:12px;position:absolute;top:330px;left:595px;">';
    k += 'A_B_C = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_2, index) + ' = ' + giveMe(l, n, values[index].somma_comune) + '_' + giveMe(l, n, values[index].raddoppio_somma_comune) + '_' + giveMe(l, n, values[index].somma_2) + '<br>';
    k += 'A_B_D = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_1, index) + ' = ' + giveMe(l, n, values[index].somma_comune) + '_' + giveMe(l, n, values[index].raddoppio_somma_comune) + '_' + giveMe(l, n, values[index].somma_1) + '<br>';
    k += 'B_C_D = ' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_2, index) + '_' + checkValidated(values[index].somma_1, index) + ' = ' + giveMe(l, n, values[index].raddoppio_somma_comune) + '_' + giveMe(l, n, values[index].somma_2) + '_' + giveMe(l, n, values[index].somma_1) + '<br><br>';
    k += 'A_B_F = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_diag_1, index) + ' = ' + giveMe(l, n, values[index].somma_comune) + '_' + giveMe(l, n, values[index].raddoppio_somma_comune) + '_' + giveMe(l, n, values[index].somma_diag_1) + '<br>';
    k += 'A_B_E = ' + checkValidated(values[index].somma_comune, index) + '_' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_diag_2, index) + ' = ' + giveMe(l, n, values[index].somma_comune) + '_' + giveMe(l, n, values[index].raddoppio_somma_comune) + '_' + giveMe(l, n, values[index].somma_diag_2) + '<br>';
    k += 'B_F_E = ' + checkValidated(values[index].raddoppio_somma_comune, index) + '_' + checkValidated(values[index].somma_diag_1, index) + '_' + checkValidated(values[index].somma_diag_2, index) + ' = ' + giveMe(l, n, values[index].raddoppio_somma_comune) + '_' + giveMe(l, n, values[index].somma_diag_1) + '_' + giveMe(l, n, values[index].somma_diag_2) + '<br><br>';
    k += 'C_D_E = ' + checkValidated(values[index].somma_2, index) + '_' + checkValidated(values[index].somma_1, index) + '_' + checkValidated(values[index].somma_diag_2, index) + ' = ' + giveMe(l, n, values[index].somma_2) + '_' + giveMe(l, n, values[index].somma_1) + '_' + giveMe(l, n, values[index].somma_diag_2) + '<br>';
    k += 'C_D_F = ' + checkValidated(values[index].somma_2, index) + '_' + checkValidated(values[index].somma_1, index) + '_' + checkValidated(values[index].somma_diag_1, index) + ' = ' + giveMe(l, n, values[index].somma_2) + '_' + giveMe(l, n, values[index].somma_1) + '_' + giveMe(l, n, values[index].somma_diag_1) + '<br>';
    k += 'D_E_F = ' + checkValidated(values[index].somma_1, index) + '_' + checkValidated(values[index].somma_diag_2, index) + '_' + checkValidated(values[index].somma_diag_1, index) + ' = ' + giveMe(l, n, values[index].somma_1) + '_' + giveMe(l, n, values[index].somma_diag_2) + '_' + giveMe(l, n, values[index].somma_diag_1) + '<br><br>';
    k += '</div>';
    k += '</div>';
    return k
}
function giveMe(s, t, u) {
    for (key in t) {
        if (t[key] == u) {
            return s[key]
        }
    }
}
function orderVector(v, w) {
    order = false;
    while (!order) {
        order = true;
        for (i = 0; i < 5; i++) {
            if (parseInt(v[i]) > parseInt(v[i + 1])) {
                order = false;
                tmp = v[i];
                v[i] = v[i + 1];
                v[i + 1] = tmp;
                tmp = w[i];
                w[i] = w[i + 1];
                w[i + 1] = tmp
            }
        }
    }
}
function calculateOccurence(h, f, g) {
    for (i = 0; i < 6; i++) {
        for (var b in h) {
            if (parseInt(f[i]) === parseInt(h[b])) {
                g[i]++
            }
        }
    }
}
function getMax(q, r) {
    if (parseInt(q) > parseInt(r)) {
        return q
    } else {
        return r
    }
}
function checkValidated(j, index) {
    if (j == values[index].somma_1) {
        return "<span class='color1'><b>" + ('0' + j).slice(-2) + '</b></span>'
    };
    if (j == values[index].somma_2) {
        return "<span class='color3'><b>" + ('0' + j).slice(-2) + '</b></span>'
    };
    if (j == values[index].somma_diag_1) {
        return "<span class='color2'><b>" + ('0' + j).slice(-2) + '</b></span>'
    };
    if (j == values[index].somma_diag_2) {
        return "<span class='color4'><b>" + ('0' + j).slice(-2) + '</b></span>'
    };
    if (j == values[index].somma_comune) {
        return "<span class='color5'><b>" + ('0' + j).slice(-2) + '</b></span>'
    };
    if (j == values[index].raddoppio_somma_comune) {
        return "<span class='color6'><b>" + ('0' + j).slice(-2) + '</b></span>'
    };
    return '-'
}