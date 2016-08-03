$(document).ready( function() {
 $('#s1').on('mousewheel', function(event) {
    //console.log(event.deltaX, event.deltaY, event.deltaFactor);
    console.log('Servicios');
  });
 $('.mywork').on('mousewheel', function(event) {
    console.log('Productos');
  });
});