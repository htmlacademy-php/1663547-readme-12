<!-- пост-ссылка -->
<?php
/**
 * @var array $content
 */
?>
<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="<?= $content['link']?>" title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?domain=<?= $content['link']?>" alt="Иконка">
                </div>
                <div class="post-link__info">
                    <h3><?= $content['heading'] ?></h3>
                </div>
            </div>
        </a>
    </div>
</div>
