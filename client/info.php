<?php 
require_once "header.php";
?>
<script>  
 if (sessionStorage.getItem('key') == null) {
  window.location.href = "login.php";  
      }
</script>
<div class="container">
  <div class="row">
    <div class="col-sm">

    </div>
    <div class="col-sm">
    <form id="myForm" class="myForm">
<label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" value="" name="name" id="name"  placeholder="name">
 
  
    <label for="exampleInputPassword1">Email</label>
    <input type="text" required  value="" class="form-control" name="email" id="email"  placeholder="email">
    <label for="exampleInputPassword1">Date of birth</label>
    <input type="date"  value="" class="form-control" name="dob" id="dob"  placeholder="dob">
    <label for="exampleInputPassword1">Password</label>
    <input type="text"  value="" class="form-control" name="password" id="password"  placeholder="Password">

    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
</form>
    </div>
    <div class="col-sm">
  
    </div>
  </div>
</div>

<script>
var userID = sessionStorage.getItem('key');

const url="http://localhost:8080/api/getUser/"+userID;

$(document).ready(function(){
$.ajax({
            url: url,
            type: 'GET',
        
            success: function (data, textStatus) {
              var myArray = JSON.parse(data);
              var myForm = $('#myForm'); // its just for caching
              myForm.find('input[name=name]').val(myArray[0][1]);
              myForm.find('input[name=email]').val(myArray[0][2]);
              myForm.find('input[name=dob]').val(myArray[0][3]);
              myForm.find('input[name=password]').val(myArray[0][4]);
            },
            error: function () {
                alert('Check form');
            }
    });
    $('#submit').click(function(){
    var authKey = sessionStorage.getItem('key');
    var data = $('form.myForm').serialize()+ '&authKey=' + authKey;
   
    alert(data);

    $.ajax({
            url: 'http://localhost:8080/api/updateinfo',
            data: data,
            type: 'post',
          
            success: function (data, textStatus) {
              var myArray = JSON.parse(data);
              if(myArray.status==200){
                alert("Success");
              }else{
                alert("email or pass is wrong");
              }
            },
            error: function () {
                alert('Check form');
            }
    });
   
  });
});
</script>
<?php 
require_once "footer.php";
?>