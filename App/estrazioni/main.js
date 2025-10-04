var gridEstr, gridDist;
$(document).ready(function () {
    createTables();
    downloadEstrazioni();
    $('#cmb_Year').change(downloadEstrazioni);
    $('#cmb_Est').change(downloadValori)
});
function createTables() {
    var a = ($(window).height() - $('#header').height() - $('#toolbar').height()) / 2;
    $('#distance_Table').height((a - 17) + 'px');
    $('#extraction_Table').height((a - 27) + 'px');
    gridEstr = new dhtmlXGridObject('extraction_Table');
    gridEstr.setHeader('Ruota, <center>I</center>, <center>II</center>, <center>III</center>, <center>IV</center>, <center>V</center>');
    gridEstr.setInitWidths('*');
    gridEstr.setColAlign('left,center,center,center,center,center');
    gridEstr.enableEditEvents(false, false, false);
    gridEstr.setStyle('background: #00ace6;color:white;font-size:15px', 'font-size:15px;', '', '');
    gridEstr.init();
    gridDist = new dhtmlXGridObject('distance_Table');
    gridDist.setHeader('Ruota, <center>1°-2°</center>, <center>1°-3°</center>, <center>1°-4°</center>, <center>1°-5°</center>, <center>2°-3°</center>, <center>2°-4°</center>, <center>2°-5°</center>,<center>3°-4°</center>, <center>3°-5°</center>, <center>4°-5°</center>');
    gridDist.setColAlign('left,center,center,center,center,center,center,center,center,center,center');
    gridDist.setInitWidths('*');
    gridDist.enableEditEvents(false, false, false);
    gridDist.setStyle('background: #00ace6;color:white;font-size:15px', 'font-size:15px;', '', '');
    gridDist.init()
}
function downloadEstrazioni() {
    $.ajax({
        url: 'estrazioni_miner.php',
        data: {
            year: $('#cmb_Year').val()
        },
        dataType: 'JSON',
        cache: false,
        success: function (d) {
            var c = d.length;
            $('#cmb_Est').empty();
            for (var b in d) {
                $('#cmb_Est').append($('<option></option>').attr('value', c--).text(d[b]))
            };
            downloadValori()
        }
    })
}
function downloadValori() {
    $.ajax({
        url: 'valori_miner.php',
        data: {
            year: $('#cmb_Year').val(),
            estr: $('#cmb_Est').val()
        },
        dataType: 'JSON',
        cache: false,
        success: function (d) {
            gridEstr.clearAll();
            gridEstr.parse(d.estr, 'json');
            gridDist.clearAll();
            gridDist.parse(d.dist, 'json')
        }
    })
}
