
<style type="text/css" >
#searchform {
	margin:2px 12px;
}
#searchform label {
	margin:6px 0;
	line-height:1em;
}
#searchform select {
	margin:0;
	line-height:1em;
}
#searchform input[type="radio"] {
	margin-top:0;
	margin-bottom:0;
	background:#333;
}
#searchform .formfloat_left {
	margin-right:12px;
}
#searchform .formfloat_left, #searchform .formfloat_right {
	float:left;
}
</style>
<h2>Quick Search</h2>
<form name="searchform" id="searchform" action="search.php?" method="get" enctype="application/x-www-form-urlencoded" >
    <div class="formfloat_left" >
        <label for="srch_keyword" >Keyword</label>
        <input type="text" name="keyword" id="srch_keyword" style="width:140px;" >
        <label for="srch_availability" >Availability</label>
        <select name="availability" id="srch_availability" style="width:140px;" >
            <option value="" >Select if required&nbsp;&nbsp;&nbsp;&nbsp;</option>
            <optgroup label="Options" >
                <?php
                    foreach($availability_array as $availability){
                    ?><option value="<?php echo $availability ; ?>" title="<?php echo $availability ; ?>" ><?php echo string_elipsis($availability,25) ; ?></option>
                    <?php
                    }
                ?>
            </optgroup>
        </select>
    </div>
    <div class="formfloat_right" >
        <label for="srch_subject" >Subject Studied</label>
        <select name="subject" id="srch_subject" >
            <option value="" >Please select a subject&nbsp;&nbsp;&nbsp;&nbsp;</option>
            <optgroup label="Options" >
                <?php
                    foreach($subjects_array as $subject){
                    ?><option value="<?php echo $subject ; ?>" title="<?php echo $subject ; ?>" ><?php echo string_elipsis($subject,25) ; ?></option>
                    <?php
                    }
                ?>
            </optgroup>
        </select>
        <label for="srch_catagory" >Job Category</label>
        <select name="catagory" id="srch_catagory" >
            <option value="" >Please select a job category&nbsp;&nbsp;&nbsp;&nbsp;</option>
            <optgroup label="Options" >
                <?php
                    foreach($category_array as $category){
                    ?><option value="<?php echo $category ; ?>" title="<?php echo $category ; ?>" ><?php echo string_elipsis($category,25) ; ?></option>
                    <?php
                    }
                ?>
            </optgroup>
        </select>
    </div>
    <div align="left" style="clear:both; padding-top:8px;" ><input style="margin-top:0;" type="submit" name="submit" value="Search Graduates" />&nbsp;&nbsp;&nbsp;<a href="search.php" >Advanced Search (Employers only)</a></div>
</form>