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

class Organizer extends Controller {

    /**
     * @readwrite
     */
    protected $_organizer;

    /**
     * @before _secure, changeLayout
     */
    public function index($id = NULL) {
        $this->seo(array("title" => "Dashboard","keywords" => "dashboard","description" => "Contains all realtime stats","view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $events = Event::count(array("user_id = ?" => $this->user->id));
        $view->set('events', $events);

        // if (isset($id)) { $this->switchorg($id);}
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

    public function logout() {
        $this->setUser(false);
        self::redirect("/login");
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

    /**
     * @before _secure, changeLayout
     */
    public function resources() {
        $this->seo(array("title" => "organizer Resources", "keywords" => "faq", "description" => "Frequently asked Questions", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
    }

    public function widget($organization_id = NULL) {
        $this->willRenderLayoutView = false;
        $view = $this->getActionView();
        if ($organization_id != NULL) {
            $organization = Organization::first(array("id = ?" => $organization_id), array("id", "name", "website", "type", "image"));
            $view->set("organization", $organization);
        }
    }

    public function about() {
        $this->seo(array(
            "title" => "Why use our platform?",
            "keywords" => "event management",
            "description" => "Description of our platform",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
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

    /*
    protected function switchorg($organization_id) {
        $session = Registry::get("session");
        $member = $session->get("member");

        foreach ($member as $mem) {
            if ($organization_id == $mem->organization->id) {
                $session->set("organizer", $mem);
                self::redirect("/organizer");
            }
        }
    }*/

    /**
     * @protected
     */
    public function _secure() {
        $user = $this->getUser();
        $session = Registry::get("session");
        // $member = $session->get("member");
        $member = true;

        if (!$user || !$member) {
            header("Location: /home");
            exit();
        }
    }

}
