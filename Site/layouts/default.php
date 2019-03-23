<html>
    <head>
        <title><?php echo $this->getTitle(); ?></title>
        <?php echo $this->renderCss(); ?>
        <?php echo $this->renderJs(); ?>
    </head>
    <body>
        <h1>Сайт</h1>
        <div class="general-menu">
            <ul>
                <li><a href="<?php echo $this->getRootPath(); ?>">Главная</a></li>
                <li><a href="<?php echo $this->getRootPath(); ?>/default/contacts">Контакты</a></li>
                <li><a href="<?php echo $this->getRootPath(); ?>/default/pages">Страницы</a></li>
            </ul>
        </div>
        <div class="general-content">
            <?php include $TEMPLATE_PATH;?>
        </div>
    </body>
</html>