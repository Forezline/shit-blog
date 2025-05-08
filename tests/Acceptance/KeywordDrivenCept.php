<?php

use Tests\Support\AcceptanceTester;

class KeywordDrivenCept
{
    public function login(AcceptanceTester $I): void
    {
        $I->amOnPage('/pages/login.php');
        $I->fillField('username', 'forezline@gmail.com');
        $I->fillField('password', '123456789');
        $I->click('Login');
        $I->see('Мій Блог');
    }

    public function openLatestPostAndComment(AcceptanceTester $I): void
    {
        $I->amOnPage('/');
        $I->seeElement('.post-item');
        $I->click(['css' => '.post-item:first-child a']);
        $I->see('Comments');
        $I->fillField('comment', 'Це тестовий коментар');
        $I->click('Submit Comment');
        $I->see('Це тестовий коментар');
    }

    public function createNewPost(AcceptanceTester $I): void
    {
        $I->amOnPage('/create_post.php');
        $I->fillField('title', 'Тестовий пост');
        $I->fillField('content', 'Це контент нового посту.');
        $I->click('submit');
        $I->see('Пост успішно створено');
    }

    public function logout(AcceptanceTester $I): void
    {
        $I->click('/includes/auth/logout.php');
        $I->see('Ви вийшли з акаунту');
    }
}
