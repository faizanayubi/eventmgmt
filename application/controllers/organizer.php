<?php

/**
 * The Organizer Controller
 *
 * @author Faizan Ayubi, Hemant Mann
 */
use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;
use Shared\Markup as Markup;

class Organizer extends Auth {

    /**
     * @before _secure, changeLayout
     */
    public function index($id = NULL) {
        $this->seo(array("title" => "Dashboard","keywords" => "dashboard","description" => "Contains all realtime stats","view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $events = Event::count(array("user_id = ?" => $this->user->id));
        $view->set('events', $events);
    }

    public function register() {
        $this->seo(array(
            "title" => "Register with EventGroup",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        if (RequestMethods::post("action") == "register") {
            $email = RequestMethods::post("email");

            $user = User::first(array("email = ?" => $email));
            if (!$user) {
                $password = RequestMethods::post("password");
                $user = new User(array(
                    "name" => RequestMethods::post("name"),
                    "email" => RequestMethods::post("email"),
                    "phone" => RequestMethods::post("phone"),
                    "password" => Markup::encrypt($password),
                    "admin" => false
                ));

                $user->save();

                $this->notify(array(
                    "template" => "userRegister",
                    "subject" => "Welcome to MyEventGroup.com",
                    "user" => $user
                ));

                $this->setUser($user);
                self::redirect("/organizer");
            } else {
                $view->set("error", "You are already registered! Login to continue");
            }
        }
    }

    public function login() {
        $this->seo(array(
            "title" => "Login to EventGroup",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        if (RequestMethods::post("action") == "login") {
            $email = RequestMethods::post("email");
            $password = RequestMethods::post("password");

            $user = User::first(array("email = ?" => $email));
            if ($user) {
                if (Markup::checkHash($password, $user->password)) {
                    $this->setUser($user);
                    self::redirect("/organizer");
                } else {
                    $view->set("error", "Invalid email/password");    
                }
            } else {
                $view->set("error", "Invalid email/password");    
            }
        }
    }

    /**
     * @before _secure, changeLayout
     */
    public function settings() {
        $this->seo(array(
            "title" => "Settings",
            "keywords" => "edit",
            "description" => "Edit your profile",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        if (RequestMethods::post('action') == 'saveUser') {
            $user = User::first(array("id = ?" => $this->user->id));
            $user->phone = RequestMethods::post('phone');
            $user->name = RequestMethods::post('name');
            $user->save();
            $view->set("success", true);
            $view->set("user", $user);
        }
    }
    
    public function fbLogin() {
        $this->noview();
        $session = Registry::get("session");
        if ((RequestMethods::post("action") == "fbLogin") && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
            // process the registration
            $email = RequestMethods::post("email");
            $user = User::first(array("email = ?" => $email));
            if (!$user) {
                $user = new User(array(
                    "name" => RequestMethods::post("name"),
                    "email" => $email,
                    "password" => sha1(rand(1000, 99999)),
                    "phone" => "",
                    "admin" => false
                ));
                $user->save();

                $this->notify(array(
                    "template" => "userRegister",
                    "subject" => "Welcome to MyEventGroup.com",
                    "user" => $user
                ));
            }
            $this->setUser($user);
            echo "Success";
        } else {
            self::redirect("/404");
        }
    }

    public function changeLayout() {
        $this->defaultLayout = "layouts/organizer";
        $this->setLayout();
    }

}
