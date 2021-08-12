<?php
require_once "header.php";
?>

<div class="container">
  <div class="row">
    <div class="col-sm">
      
    </div>
    <div class="col-sm">
    <form class="myForm">
    <label for="exampleInputEmail1">Email address</label>
    <input type="text" class="form-control" value="" name="email" id="email"  placeholder="Enter email">
 
  
    <label for="exampleInputPassword1">Password</label>
    <input type="password"  value="" class="form-control" name="password" id="password"  placeholder="Password">

    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
    <div class="col-sm">
        
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $('#submit').click(function(){
    var data = $('form.myForm').serialize();
    var dataEmail = $("#email").val();
    $.ajax({
            url: 'http://localhost:8080/api/login',
            data: data,
            type: 'post',
          
            success: function (data, textStatus) {
              var myArray = JSON.parse(data);
              if(myArray.status=="success"){
                if(myArray.isActive=="FALSE"){
                  alert("Please check your email address and active your account");
                }else{
                var authKey=myArray.authKey;
                alert("success");
                alert(authKey);
                sessionStorage.setItem('key', authKey);
                window.location.href = "info.php";
              }
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