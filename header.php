<header>
    <nav class="top_menu" id="menu">
<!--        <a class="navbar-brand" href="index.php">-->
<!--            <img class="img-thumbnail" src="img/logo.jpg" width="100" height="50" alt="Логотип">-->
<!--        </a>-->
        <ul>
            <li><a href="#">ЖУРНАЛ УДС</a>
                <ul>
                    <li><a href="jurnal.php?j=today" id="today">За сегодня</a></li>
                    <li><a href="jurnal.php?j=yesterday" id="yesterday">За вчера</a></li>
                    <li><a href="jurnal.php?j=dat" id="dat">Произвольный поиск...</a></li>
                    <li class="disabled"><a href="jurnal.php?j=all" id="all">Все данные</a></li>
<!--                    <li><a href="jurnal.php?j=gn" id="gn">Поиск по адресу...</a></li>-->
                </ul>
            </li>
            <li><a href="#">ВНЕСТИ ДАННЫЕ</a>
                <ul>
                    <li><a href="input2.php" id="nav_2">Добавить НДУ...</a></li>
<!--                    <li><a href="input3.php" id="nav_3">Добавить ФОТО НДУ...</a></li>-->
                </ul>
            </li>
        </ul>
    </nav>
    <div class="div_version">
        <small> Версия 1.9-10.06.21 </small>
    </div>
    <?php include "blocks/pb_progress.php" ?>
</header>

