<div class="container-fluid ng-scope" id="ContactUS">
  <div class="container">
    <h2 class="hidden_scroll visible_scroll animated fadeInDown">GET IN TOUCH</h2>
    <div class="row hidden_scroll visible_scroll animated fadeInDown">
     <div class="col-md-4 col-sm-4">
      <div class="contactItem">
       <i class="fa"><img src="images/location.jpg"/></i>
       <p>Srinagar , Kashmir , India</p>
      </div>
     </div>
    <div class="col-md-4 col-sm-4">
     <div class="contactItem">
      <i class="fa"><img src="images/email.jpg"/></i>
      <p>support@panunkart.com</p>
     </div>
    </div>
    <div class="col-md-4 col-sm-4">
     <div class="contactItem">
      <i class="fa"><img src="images/phone.jpg"/></i>
      <p>+91 0123456789</p>
     </div>
    </div>
    </div>

    <form  method="post" action = "<?php echo base_url(); ?>index.php/main/contact_validation"class="ng-pristine ng-valid">
      <div class="row hidden_scroll visible_scroll animated fadeInDown">
        <div class="col-md-4 col-sm-4 col-xs-4">
          <input type="text" name="name" class="form-control ng-pristine ng-valid" value="<?php echo $this->input->post('name'); ?>"placeholder="FullName" data-ng-model="message.fullName">
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
          <input type="text" name="email" class="form-control ng-pristine ng-valid" value="<?php echo $this->input->post('email'); ?>"placeholder="EmailAddress" data-ng-model="message.email">
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
          <input type="text" name="number" class="form-control ng-pristine ng-valid" value="<?php echo $this->input->post('number'); ?>" placeholder="PhoneNumber" data-ng-model="message.phone">
        </div>
      </div>
      <div class="row hidden_scroll visible_scroll animated fadeInDown">
        <div class="col-md-12">
          <textarea name="msg" rows="6" class="form-control ng-pristine ng-valid" value="<?php echo $this->input->post('msg'); ?>" placeholder="YourMessage" data-ng-model="message.text"></textarea>
        </div>
      </div>
      <div class="row hidden_scroll visible_scroll animated fadeInDown">
        <div class="col-md-12">
       <?php
        echo "<p style='color:black;'>";
	echo form_submit('contact_submit','submit');
	echo "</p>";
	   ?>

        </div>
      </div>
    </form>
  </div>
</div>
</div>
