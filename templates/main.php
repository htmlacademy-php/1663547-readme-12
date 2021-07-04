<?php
/**
 * @var string $title
 */
?>
<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular"><?= $title ?></h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list filters__button--active">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all filters__button--active" href="#">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach ($types as $type_content){
                        $size = [];
                        switch ($type_content['class_name']) {
                            case 'photo':
                                $size['width'] = 22;
                                $size['height'] = 18;
                                break;
                            case 'video':
                                $size = [
                                    'width' => 24,
                                    'height' => 16
                                ];
                                break;
                            case 'text':
                                $size = [
                                    'width' => 20,
                                    'height' => 21
                                ];
                                break;
                            case 'quote':
                                $size = [
                                    'width' => 21,
                                    'height' => 20
                                ];
                                break;
                            case 'link':
                                $size = [
                                    'width' => 21,
                                    'height' => 18
                                ];
                                break;
                            default:
                                break;
                        } ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--<?= $type_content['class_name'] ?> button" href="#">
                                <span class="visually-hidden"><?= $type_content['name'] ?></span>
                                <svg class="filters__icon" width="<?= $size['width'] ?>" height="<?= $size['height'] ?>">
                                    <use xlink:href="#icon-filter-<?= $type_content['class_name'] ?>"></use>
                                </svg>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($posts as $key =>$elem) {?>
                <article class="popular__post post post-<?= $elem['class_name'] ?>">
                    <header class="post__header">
                        <h2><a href="post.php?post_id=<?= $elem['id']?>"><?=$elem['heading']?></a></h2>
                    </header>
                    <div class="post__main">
                        <?php
                        switch ($elem['class_name']){
                            case 'quote':
                                ?>
                                <blockquote>
                                    <p>
                                        <?= $elem['content']?>
                                    </p>
                                    <cite>Неизвестный Автор</cite>
                                </blockquote>
                                <?php break;?>
                            <?php case'text':?>
                                <?php
                                $long_text = $elem['content'];
                                $short_text = cropText($long_text, 270);
                                echo $short_text;?>
                            <?php break;?>
                        <?php case'photo': ?>
                            <div class="post-photo__image-wrapper">
                                <img src="<?= $elem['image']?>" alt="Фото от пользователя" width="360" height="240">
                            </div>
                            <?php break;?>
                        <?php case'link':?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="<?= $elem['link'] ?>" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= $elem['heading'] ?></h3>
                                        </div>
                                    </div>
                                    <span><?= $elem['link'] ?></span>
                                </a>
                            </div>
                            <?php break;
                            default:
                                break;
                        }
                        ?>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="#" title="
                                <?php
                                $randomDate = generate_random_date($key);
                                $unixRandomDate = strtotime($randomDate);
                                echo date('d.m.Y H:i:s',$unixRandomDate);
                                ?>">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="<?= $elem['avatar_path']?>" alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= $elem['name'] ?></b>
                                        <time class="post__time" datetime="">
                                            <?php
                                            date_default_timezone_set('Europe/Moscow');
                                            $general = time() - $unixRandomDate;
                                            echo get_time_ago($general).' '.'назад';
                                            ?></time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                             height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span>0</span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span>0</span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </div>
                        </footer>
                </article>
            <?php }?>
        </div>
</section>
