$( document ).ready(function() {
  $('.only-decimal').keypress(function(event) {

      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {

        event.preventDefault();

      }

    });
    $('.only-numeric').keypress(function(event) {

      var charCode = (event.which) ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
    });

    $('.only-numeric-not-null').keypress(function(event) {

      
      var charCode = (event.which) ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
     
      return true;
    });
    $('.only-decimal').change(function(){
      var textval= $(this).val();
      if(textval=="")
      {
          var textval= $(this).val('0.00');
      } 
     
    });

    $('.only-numeric').change(function(){
      var textval= $(this).val();
      if(textval=="")
      {
          var textval= $(this).val('0');
      } 
     
    });
    $('#change_date').change(function(){
$('#change_date_form').submit();
    });
    


    $('.only-numeric-not-null ').change(function(){

      
      var textval= $(this).val();
      
      if(textval=="")
      {
          
          $(this).addClass('text-error');
      }else
      {
          $(this).removeClass('text-error');
      }
       

  });
  $('.Recalculate,.recalculate_approved').click(function(){
      $('.error-message').remove();
      if($(".text-error").length>0)
      {
    $(this).after('<p class="error-message">Please fill out the highlighted fields in above form.</p>')

          return false;

      }

  });
  $('.delete-confirm').click(function(){
    var result = confirm("You sure to delete this record?");
    if (result) {
         return true;
    }
    else
    {
      return false;
    }
  });

  
  $(document).on('click','#print-report', function(){
    var printContents = document.getElementById("printarea").innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
});

var nextElement = $('#change_date > option:selected').next('option');
var previousElement = $('#change_date > option:selected').prev('option');
if (nextElement.length == 0) {
$("#next_list").hide();

}
if (previousElement.length == 0) {
$("#previous_list").hide();
}

$("#next_list").click(function() {

if (nextElement.length > 0) {
  $('#change_date > option:selected').removeAttr('selected').next('option').attr('selected', 'selected');
  $('#change_date').trigger('change');
}
});

$("#previous_list").click(function() {

if (previousElement.length > 0) {
  $('#change_date > option:selected').removeAttr('selected').prev('option').attr('selected', 'selected');
  $('#change_date').trigger('change');
}
});
});