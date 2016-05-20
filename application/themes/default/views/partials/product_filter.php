<?php echo form_open('cart/search', 'class="navbar-search"'); ?>
<ul class="filterUl nav nav-list well">
    <li class="nav-header">
        Product Filter
    </li>
    <li>       
        <input type="text" id="term" name="term" class="span2" placeholder="<?php echo lang('search'); ?>" value="<?php echo $term; ?>"/>
    </li>
    <li>
        <input type="text" id="term2" name="term2" class="input input-mini" placeholder="min prize" value="<?php echo $pmin; ?>"/>
        <input type="text" id="term3" name="term3" class="input input-mini" placeholder="max prize" value="<?php echo $pmax; ?>"/>        
    </li>
    <li>
        <input class="btn btn-info" type="submit" value="filter"/>
        <input class="btn btn btn-default btn-clear" type="button" value="clear"/>
    </li>
</ul>
<?php echo form_close(); ?>