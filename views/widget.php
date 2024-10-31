<form class="quotepro-office-widget" method="POST" action="<?php echo $url; ?>/<?php echo $lang; ?>/Offices/Result">
  <div class="widget-title"><h3><?php _e($title); ?></h3></div>
  <p><input name="ZipCode" type="tel" class="ZipCode col-460"  placeholder="<?php _e('Enter Zip Code'); ?>" maxlength="5" required/></p>
  <p>
  <select id="Radius" name="Radius" class="Radius col-460">
    <option value="">Select Your Search Radius</option>
    <option value="1">1 mile</option>
    <option value="2">2 miles</option>
    <option value="5" selected="selected">5 miles</option>
    <option value="10">10 miles</option>
    <option value="20">20 miles</option>
    <option value="50">50 miles</option>
    <option value="9999">&gt; 50 miles</option>
  </select>
  </p>
  <input type="submit" class="submit" value="Go" />
  <a class="colorbox" title="<?php _e($title); ?>" href="#"></a>
</form>
