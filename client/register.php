<?php 
require_once "header.php";
?>
<body>
<div class="container">
  <div class="row">
    <div class="col-sm">
     
    </div>
    <div class="col-sm">
      
    
    

<form class="myForm">
  
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" value="" name="name"  placeholder="Enter name">
 
 
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" value="" name="email"  placeholder="Enter email">
 
  
    <label for="exampleInputPassword1">Date of Birth</label>
    <input type="date" name="dob" value="" class="form-control"  placeholder="date of birth">
  
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" value="" name="password" placeholder="Password">
  
  <button type="submit" id="submit" class="btn btn-primary">Submit</button>
</form>
  </body>
</div>
  <div class="col-sm">
      
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $('#submit').click(function(){
    var data = $('form.myForm').serialize();
    $.ajax({
            url: 'http://localhost:8080/api/register',
            data: data,
            type: 'post',
          
            success: function (res) {
            alert("success");
              
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