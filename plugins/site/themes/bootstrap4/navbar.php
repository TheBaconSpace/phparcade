<?php if (!isset($_SESSION)) {
    session_start();
} ?>
<!-- Nav Section -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
        <?php echo gettext('logo'); ?>
    </a>

    <button aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"
            data-target="#navbarNav" data-toggle="collapse" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <form class="col-md-4 ml-auto">
            <?php include_once INST_DIR . 'includes/js/Google/googlecustomsearch.php';?>
        </form>
        <ul class="navbar-nav">
            <?php
            if (!PHPArcade\Users::isUserLoggedIn()) {
                ?>
                <li class="nav-item dropdown">
                    <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle" data-toggle="dropdown"
                       href="#" id="navbarCategories">
                        <?php echo gettext('gamecategories'); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarCategories">
                        <?php include_once __DIR__ . '/categoriesmenu.php'; ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" class="signupbutton"
                       href="<?php echo PHPArcade\Core::getLinkRegister(); ?>" title="<?php echo gettext('login'); ?>">
                        <?php echo gettext('login'); ?>
                    </a>
                </li><?php
            } else {
                include_once __DIR__ . '/navbar-dropdown.php';
            } ?>
        </ul>
    </div>
</nav>
<!--End Nav Section -->