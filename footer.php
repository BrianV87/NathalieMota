<footer class="site-footer">
    <hr class="footer-line">
    <div class="container footer-inner">
        <?php
        wp_nav_menu([
            'theme_location' => 'footer',
            'container'      => false,
            'menu_class'     => 'footer-menu',
            'fallback_cb'    => false,
        ]);
        ?>
    </div>
    <?php wp_footer(); ?>
</footer>

</body>

</html>