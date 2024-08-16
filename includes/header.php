<header>
    <h1 id="title"><?php echo htmlspecialchars($title); ?> Clothing Wishlist</h1>

    <nav id="menu">
        <ul>
            <li class="<?php echo $nav_wishlist_class; ?>"><a href="/">Home</a></li>
            <li class="<?php echo $nav_insert_class; ?>"><a href="/insert">New Entry</a></li>
            <li class="<?php echo $nav_login_class; ?>"><a href="/login"> Login</a></li>


            <!-- Only show log out if using is logged in -->
            <?php if (is_user_logged_in()) { ?>
                <!-- TODO: 5. [x] logout link -->
                <li class="float-right"><a href="<?php echo logout_url(); ?>">Sign Out</a></li>
            <?php } ?>
        </ul>
    </nav>

</header>
