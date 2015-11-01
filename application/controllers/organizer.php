<?php

/**
 * The Organizer Controller
 *
 * @author Faizan Ayubi, Hemant Mann
 */
use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;
use Shared\Controller as Controller;

class Organizer extends Controller {

    /**
     * @readwrite
     */
    protected $_organizer;

    /**
     * @before _secure, changeLayout
     */
    public function index($title = NULL, $id = NULL) {
        $this->seo(array("title" => "Dashboard","keywords" => "dashboard","description" => "Contains all realtime stats","view" => $this->getLayoutView()));
        $view = $this->getActionView();

        if (isset($id)) { $this->switchorg($id);}

        $opportunities = Opportunity::all(array("organization_id = ?" => $this->employer->organization->id), array("id"));
        $messages = "";$applicants = "0";
        foreach ($opportunities as $opportunity) {
            $applicants += Application::count(array("opportunity_id = ?" => $opportunity->id));
        }

        $view->set("opportunities", count($opportunities));
        $view->set("applicants", $applicants);
        $view->set("messages", $messages);
    }

    public function register() {
        $this->seo(array(
            "title" => "Register with EventGroup",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
        $view->set("url", "http://myeventgroup.com/");
    }

    protected function member($social) {
        $li = Registry::get("linkedin");
        $companies = $li->isCompanyAdmin('/companies');
        $membersof = array();

        if ($companies["_total"] == 0) {
            return FALSE;
        }
        //add all its company on our platform
        foreach ($companies["values"] as $key => $value) {
            $organization = Organization::first(array("linkedin_id = ?" => $value["id"]));
            if (!$organization) {
                $company = $li->get("/companies/{$value['id']}:(id,name,website-url,description,industries,logo-url,employee-count-range,locations)");
                $photo = new Photograph();
                $photoId = "";

                if (!empty($company["logoUrl"])) {
                    $photo->linkedinphoto($company["logoUrl"]);
                    $photo->save();
                    $photoId = $photo->id;
                }

                $organization = new Organization(array(
                    "photo_id" => $photoId,
                    "name" => $company["name"],
                    "country" => "",
                    "website" => $this->checkData($company["websiteUrl"]),
                    "sector" => $this->checkData($company["industries"]["values"]["0"]["name"]),
                    "type" => "company",
                    "account" => "basic",
                    "about" => $this->checkData($company["description"]),
                    "fbpage" => "",
                    "linkedin_id" => $this->checkData($company["id"]),
                    "validity" => "1",
                    "updated" => ""
                ));
                $organization->save();
            }

            $member = Member::first(array("user_id = ?" => $social->user_id, "organization_id = ?" => $organization->id));
            if (!$member) {
                $member = new Member(array(
                    "user_id" => $social->user_id,
                    "organization_id" => $organization->id,
                    "designation" => "Member",
                    "authority" => "admin",
                    "validity" => "1",
                    "updated" => ""
                ));
                $member->save();
            }
            $membersof[] = array(
                "id" => $member->id,
                "organization" => $organization,
                "designation" => $member->designation,
                "authority" => $member->authority
            );
        }
        return $membersof;
    }

    protected function login($info = array()) {
        $this->user = $info["user"];
        $session = Registry::get("session");
        $session->set("employer", Framework\ArrayMethods::toObject($info["members"][0]));
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

        $employees = Member::all(array("organization_id = ?" => $this->employer->organization->id, "validity = ?" => true), array("user_id", "designation", "authority", "created"));
        foreach ($employees as $emp) {
            $user = User::first(array("id = ?" => $emp->user_id), array("name"));
            $allmembers[] = [
                "id" => $emp->id,
                "user_id" => $emp->user_id,
                "name" => $user->name,
                "designation" => $emp->designation,
                "authority" => $emp->authority,
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

        if (RequestMethods::post("action") == "saveAccount") {
            $organization = Organization::first(array("id = ?" => $this->employer->organization->id));
            $organization->account = RequestMethods::post("account", "basic");
            $organization->save();
            $view->set("success", true);
        }
    }

    /**
     * @before _secure, changeLayout
     */
    public function reach() {
        $this->seo(array("title" => "Internship Reach", "keywords" => "reach", "description" => "opportunity internshipy reach posted on linkedin", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $opportunities = Opportunity::all(array("organization_id = ?" => $this->employer->organization->id, "type = ?" => "internship"), array("id,title"));
        $view->set("opportunities", $opportunities);
    }

    /**
     * @before _secure
     */
    public function reachstats($updatekey, $startdate, $enddate) {
        $li = Registry::get("linkedin");
        $session = Registry::get("session");
        $employer = $session->get("employer");
        $data = array();
        if ($li->hasAccessToken()) {
            $info = $li->get('/companies/' . $employer->organization->linkedin_id . '/historical-status-update-statistics', array(
                "start-timestamp" => strtotime($startdate) * 1000,
                "time-granularity" => "day",
                "end-timestamp" => strtotime($enddate) * 1000,
                "update-key" => $updatekey
            ));
            foreach ($info["values"] as $key => $value) {
                $t = strftime("%Y-%m-%d", $value["time"] / 1000);
                $data[$t] = $value["impressionCount"];
            }
            $chart = new PHPChart\Chart($data);
            $chart->drawBar(800, 400);
        }
    }

    /**
     * @before _secure, changeLayout
     */
    public function followers() {
        $this->seo(array("title" => "Company Followers on linkedin", "keywords" => "followers", "description" => "Your company followers on linkedin", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
    }

    /**
     * @before _secure
     */
    public function followerstats($startdate, $enddate) {
        $li = Registry::get("linkedin");
        $session = Registry::get("session");
        $employer = $session->get("employer");

        $totalFollowerCount = array();
        $time = array();
        $data = array();
        if ($li->hasAccessToken()) {
            $info = $li->get('/companies/' . $employer->organization->linkedin_id . '/historical-follow-statistics', array(
                "start-timestamp" => strtotime($startdate) * 1000,
                "time-granularity" => "day",
                "end-timestamp" => strtotime($enddate) * 1000
            ));
            foreach ($info["values"] as $key => $value) {
                array_push($totalFollowerCount, $value["totalFollowerCount"]);
                array_push($time, $value["time"]);
                $t = strftime("%Y-%m-%d", $value["time"] / 1000);
                $data[$t] = $value["totalFollowerCount"];
            }
            $chart = new PHPChart\Chart($data);
            $chart->drawBar(800, 400);
        }
    }

    /**
     * @before _secure, changeLayout
     */
    public function resources() {
        $this->seo(array("title" => "Employer Resources", "keywords" => "faq", "description" => "Frequently asked Questions", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
    }

    public function widget($organization_id = NULL) {
        $this->willRenderLayoutView = false;
        $view = $this->getActionView();
        if ($organization_id != NULL) {
            $organization = Organization::first(array("id = ?" => $organization_id), array("id", "name", "website", "type", "linkedin_id", "photo_id"));
            $opportunities = Opportunity::all(array("organization_id = ?" => $organization->id), array("id", "title"));
            $view->set("organization", $organization);
            $view->set("opportunities", $opportunities);
        }
    }

    public function about() {
        $this->seo(array(
            "title" => "Why Hire Interns with Us?",
            "keywords" => "hire interns, post internship, company register",
            "description" => "Hire experienced interns who require very little, if any, training. But this dream conflicts with reality. How can organizations meet the needs of today and prepare the workforce of the future? One solution is to develop a quality internship program. We will assist you in doing just that.",
            "view" => $this->getLayoutView()
        ));
    }

    protected function shareupdate($opts, $meta) {
        $li = Registry::get("linkedin");
        if ($li->hasAccessToken()) {
            $info = $li->post('/companies/' . $this->employer->organization->linkedin_id . '/shares', $opts);
            foreach ($info as $key => $value) {
                $linkedin = new Meta(array(
                    "property" => "company_share_opportunity",
                    "property_id" => $meta->id,
                    "meta_key" => $key,
                    "meta_value" => $value
                ));
                $linkedin->save();
            }
            return $info;
        }
        return FALSE;
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
        $this->defaultLayout = "layouts/employer";
        $this->setLayout();

        $session = Registry::get("session");
        $employer = $session->get("employer");
        $member = $session->get("member");

        $this->_employer = $employer;

        $this->getActionView()->set("employer", $employer);
        $this->getLayoutView()->set("employer", $employer);
        $this->getActionView()->set("member", $member);
        $this->getLayoutView()->set("member", $member);
    }

    protected function switchorg($organization_id) {
        $session = Registry::get("session");
        $member = $session->get("member");

        foreach ($member as $mem) {
            if ($organization_id == $mem->organization->id) {
                $session->set("employer", $mem);
                self::redirect("/employer");
            }
        }
    }

    /**
     * @protected
     */
    public function _secure() {
        $user = $this->getUser();
        $session = Registry::get("session");
        $member = $session->get("member");

        if (!$user || !$member) {
            header("Location: /home");
            exit();
        }
    }

}
