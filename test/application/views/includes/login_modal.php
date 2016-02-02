
<div class="modal fade" id="login-modal" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" align="center">
                          <img id='luda' src='<?php echo base_url(); ?>assets/media/loading_spinner.gif' style='display:none;width:10%;height:10%;float:left;padding-top:3%;'/>
                            <h1 class="logo">Panun<span>Kart</span></h1>
                        </div>

                        <!-- Begin # DIV Form -->
						<div class="w3-row">
              <div id="registerErrors" style="color:red;margin-left:10px;"></div>
						<div id="id">
                        <div id="div-forms">

                            <!-- Begin # Login Form -->
                            <form id="login-form" action ="">
                                <div class="modal-body">
                                  <div id='loginErrors' style="color:red;"></div>
                                    <input id="username" class="form-control" type="text" placeholder="Username" required>
                                    <input id="password" class="form-control" type="password" placeholder="Password" required>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                                    </div>

                                    <div>
                                        <button id="login_lost_btn" type="button" class="btn btn-link">Lost Password?</button>
                                       <button id="login_register_btn" type="button" class="btn btn-link">Register</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End # Login Form -->

                            <!-- Begin | Lost Password Form -->
                            <form id="lost-form" style="display:none;">
                                <div class="modal-body">
                                    <input id="lost_email" class="form-control" type="email" placeholder="Enter your E-Mail" required>
                                </div>
                                <div class="modal-footer">
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
                                    </div>
                                    <div>
                                        <button id="lost_login_btn" type="button" class="btn btn-link">Log In</button>
                                        <button id="lost_register_btn" type="button" class="btn btn-link">Register</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End | Lost Password Form -->

                            <!-- Begin | Register Form -->
                            <form id="register-form" style="display:none;">
                                <div class="modal-body">

                                    <input id="register_username" name="registerUsername" class="form-control" type="text" placeholder="Username" required>
                                    <input id="register_firstName" name="regsiterFirstName" class="form-control" type="text" placeholder="First Name" required>
                                    <input id="register_email" style="margin-top:10px;" name="regsiterEmail" class="form-control" type="email" placeholder="E-Mail" required>
                                    <input id="register_phone" style="margin-top:10px;" name="regsiterPhone" class="form-control" type="number" placeholder="Phone Number" required>
                                    <input id="register_password" name = "registerPassword" class="form-control" type="password" placeholder="Password" required>
                                    <input id="register_cpassword" name = "registerCPassword" class="form-control" type="password" placeholder="Confirm Password" required>
                                    <input  id="register_terms" style="float:left;margin-top:10px;" name = "registerTerms" value="" class="" type="checkbox"><p style="margin-left:30px;margin-top:10px;">I accept the Terms and Conditions</p>


                                </div>
                                <div class="modal-footer">
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Register</button>
                                    </div>
                                    <div>
                                        <button id="register_login_btn" type="button" class="btn btn-link">Log In</button>
                                        <button id="register_lost_btn" type="button" class="btn btn-link">Lost Password?</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End | Register Form -->
                          </div>
						  </div>
						  <div id="id1">
						  <a href="#">sign in with google+</a><br>
						  <a href="#">sign in with facebook</a>
						  </div>
                        </div>
                        <!-- End # DIV Form -->

                    </div>
                </div>
            </div>
