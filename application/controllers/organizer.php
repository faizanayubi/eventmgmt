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

        if (isset($id)) { $this->switchorg($id);}

        
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
                $view->set("success", "You are registered! Login to continue");
            } else {
                $view->set("error", "You are already registered! Login to continue");
            }
        }
        $view->set("url", "http://myeventgroup.com/");
    }

    protected function login($info = array()) {
        $this->user = $info["user"];
        $session = Registry::get("session");
        $session->set("organizer", Framework\ArrayMethods::toObject($info["members"][0]));
        $session->set("member", Framework\ArrayMethods::toObject($info["members"]));
    }

    /**
     * @before _secure, changeLayout
     */
    public function members() {
        $view = $this->getActionView();
        $allmembers = array();
        $session = Registry::get("session");
        $this->seo(array("title" => "Members", "keywords" => "dashboard", "description" => "Contains all realtime stats", "view" => $this->getLayoutView()));

        $employees = Member::all(array("organization_id = ?" => $this->organizer->organization->id, "live = ?" => true), array("user_id", "designation", "created"));
        foreach ($employees as $emp) {
            $user = User::first(array("id = ?" => $emp->user_id), array("name"));
            $allmembers[] = [
                "id" => $emp->id,
                "user_id" => $emp->user_id,
                "name" => $user->name,
                "designation" => $emp->designation,
                "created" => \Framework\StringMethods::datetime_to_text($emp->created)
            ];
        }

        $view->set("allmembers", \Framework\ArrayMethods::toObject($allmembers));
        $view->set("memberOf", $session->get("member"));
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

    protected function switchorg($organization_id) {
        $session = Registry::get("session");
        $member = $session->get("member");

        foreach ($member as $mem) {
            if ($organization_id == $mem->organization->id) {
                $session->set("organizer", $mem);
                self::redirect("/organizer");
            }
        }
    }

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
