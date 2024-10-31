<p>
<label for="<?php echo $titleId; ?>"><?php _e('Title'); ?></label>
<input class="widefat" id="<?php echo $titleId; ?>" name="<?php echo $titleName; ?>" value="<?php _e($title); ?>"/>
</p>

<p>
<label for="<?php echo $urlId; ?>"><?php _e('Quotepro Retail Website Url'); ?></label>
<input class="widefat" id="<?php echo $urlId; ?>" name="<?php echo $urlName; ?>" value="<?php _e($url); ?>"/>
<br/><a href="http://retail.quotepro.com/RetailWebSite/en/Builder/Wizard/StepOne" target="_blank">Request A Url</a>
</p>

<p>
<label><?php _e('Language'); ?></label>
<input type="radio"
       name="<?php echo $langName; ?>" value="en"
       <?php if ( $lang == "en") { echo "checked"; } ?>
/> English
<input type="radio"
       name="<?php echo $langName; ?>" value="es"
       <?php if ( $lang == "es") { echo "checked"; } ?>
/> Espa&ntilde;ol
</p>
