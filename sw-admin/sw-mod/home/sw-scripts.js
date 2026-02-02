
function filter(){
    var bulan = $('.bulan').val();
    var tahun = $('.tahun').val();
    window.location.replace("./&bulan="+bulan+"&tahun="+tahun+"");
}
   

$(".bulan, .tahun").change(function(){
    filter();
});

