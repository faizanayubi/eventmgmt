<?php

/**
 * The Organizer Controller
 *
 * @author Faizan Ayubi, Hemant Mann
 */
use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;
use Shared\Controller as Controller;
use Shared\Markup as Markup;

class Users extends Controller {

	// public function register() {
	// 	$this->seo(array(
 //            "title" => "Register with EventGroup",
 //            "keywords" => "post events, share events, create events",
 //            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
 //            "view" => $this->getLayoutView()
 //        ));
 //        $view = $this->getActionView();

 //        if (RequestMethods::post("action") == "register") {
 //            $email = RequestMethods::post("email");

 //            $user = User::first(array("email = ?" => $email));
 //            if (!$user) {
 //                $password = RequestMethods::post("password");
 //                $user = new User(array(
 //                    "name" => RequestMethods::post("name"),
 //                    "email" => RequestMethods::post("email"),
 //                    "phone" => RequestMethods::post("phone"),
 //                    "password" => Markup::encrypt($password),
 //                    "admin" => false
 //                ));

 //                $user->save();
 //                $this->setUser($user);
 //                self::redirect("/organizer");
 //            } else {
 //                $view->set("error", "You are already registered! Login to continue");
 //            }
 //        }
	// }

	public function signin() {
		// $this->noview();
		$view = $this->getActionView();
		$lview = $this->getLayoutView();
	}

}