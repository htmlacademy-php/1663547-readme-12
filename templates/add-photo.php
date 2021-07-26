<div class="adding-post__textarea-wrapper form__textarea-wrapper<?php if(array_key_exists("url", $errors)): ?> form__input-section--error <?php endif; ?>">
    <label class="adding-post__label form__label" for="post-text"> Ссылка из интернета <span class="form__input-required">*</span></label>
    <div class="form__input-section">
        <textarea class="adding-post__textarea form__input" id="post-text" name="url" value="<?=getPostVal('url'); ?>" placeholder="Введите текст публикации"></textarea>
        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Ошибка!</h3>
            <p class="form__error-desc"><?=$errors['url']; ?></p>
        </div>
    </div>
</div>
