<form action="?action=createuser" method="post" >

	<label for="email" >Email</label>
    <input type="text" name="email" id="email" class="text" />

	<label for="email" >Password</label>
    <input type="password" name="password" id="password" class="text" onmouseover="this.title=this.value;" />
    <div>To view this password, mouseover to view the title attribute</div>

	<label for="email" >User Level</label>
    <div class="float_container lister" >
		<?php
            if($_SESSION['user_level']==0){
                ?>
                <div class="floater" style="min-width:120px;" >
                    <ul>
						<li><input type="radio" name="user_level" id="user_level0" value="0" checked="checked" /><span onclick="check_me('user_level0','radio');" >Super User</span></li>
                		<li><input type="radio" name="user_level" id="user_level3" value="3" /><span onclick="check_me('user_level3','radio');" ><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> Administrator</span></li>
                    </ul>
                </div>
				<?php
            }
        ?>
        <div class="floater" >
            <ul>
                <li><input type="radio" name="user_level" id="user_level1" value="1" /><span onclick="check_me('user_level1','radio');" >Graduate</span></li>
                <li><input type="radio" name="user_level" id="user_level2" value="2" /><span onclick="check_me('user_level2','radio');" >Employer</span></li>
            </ul>
        </div>

    </div>

	<div><input type="submit" name="submit_user" value="Add User" /></div>

</form>