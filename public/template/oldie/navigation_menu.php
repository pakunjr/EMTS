<ul id="navigation">
    <li><a href="<?php echo URL_BASE, 'home/'; ?>"><img class="icon-home" src="<?php echo URL_BASE, 'public/img/blank.png'; ?>" />Home</a></li>

    <?php
        $loginModel = new login_model();
        if ( $loginModel->getData('login_status') ):
    ?>
    <li><a href="#">Items</a>
        <div class="sub-menu">
            <a href="<?php echo URL_BASE, 'item/new_item/' ?>">New Item</a>
            <a href="<?php echo URL_BASE, 'item/search_item/' ?>">Search Item</a>
        </div>
    </li>
    <?php endif; ?>
</ul>