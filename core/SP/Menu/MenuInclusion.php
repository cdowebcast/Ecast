<?php

namespace SP\Menu;



use core\sp_special\growl;


class MenuInclusion
{


    public function MenuInclude ($app){

        if(isset($_SESSION['group'])){
            $app->flash('success', _('Login erfolgreich'));
            $Users = \DB::query("SELECT * FROM accounts");

            if($_SESSION['group'] == 'adm'){
                $app->render('menu/admin.phtml', compact('Users'));
            }elseif($_SESSION['group']== 'user' ){

                #START  News Aus lesen die gelesen werden müssen
                $news_to_read = '';
                $results = \DB::query("SELECT * FROM news WHERE have_to_read=%s", '1');
                foreach ($results as $row) {
                  # $row['id'] ID der News die gelesen werden muss
                    $eintrag_set = \DB::queryFirstRow("SELECT * FROM news_to_read WHERE user_id=%s AND news_id=%s", $_SESSION['account_id'], $row['id']);

                    if(empty($eintrag_set['id'])){
                        $news_to_read[] = $row['id'];  # Ausgabe der Nachrichten die noch nicht gelesen wurden
                    }
                 }
                if(is_array($news_to_read)){
                    #NEWS Ausgeben
                    $app->render('header.phtml');
                    $app->render('news/startnews.phtml', compact('news_to_read'));
                    die();
                }else{# Menü laden
                    $app->render('menu/user.phtml', compact('Users'));
                }
                #ENDE News die gelesen werden müssen

            }elseif($_SESSION['group'] == 'dj' ){
                $app->render('menu/dj.phtml', compact('Users'));
            }
        }else{
            $app->redirect('/logout', 303);
        }
    }
}