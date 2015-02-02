      <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="whitebox">
        	<input class="searchinput" type="text" value="Search..." name="s" id="s" onblur="if (this.value == '') {this.value = 'Search...';}"  onfocus="if (this.value == 'Search...') {this.value = '';}" />
    		<input type="submit" class="submit" name="submit" id="searchsubmit" value="" />
       </form>
