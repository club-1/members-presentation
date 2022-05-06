$(".member").click(function(){
    var name = $(this).html().toLowerCase();
   $("#" + name).show();
 });
 
 $(".member").draggable({ grid: [ 80, 80 ] });
 
 
 $(".presentation").draggable(function(){
   handle: ".handle";
 });
 
 $( "div, p" ).disableSelection();
 
 
 $(".content").resizable();
 
 $(".close").click(function(){
   $(this).parent().hide();
 });