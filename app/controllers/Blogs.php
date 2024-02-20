<?php

namespace app\controllers;

use system\Controller;
use system\lib\AuthorisedAccess;
use system\lib\Database;
use system\lib\FileHandler;
use system\lib\FormValidation;
use system\lib\Request;
use system\lib\Segments;

class Blogs extends Controller
{

    public static function index()
    {


        $viewData["myblogs"] = [];
        $viewData["AuthorisedAccess"] = AuthorisedAccess::LoggedIn();

        //Verzamel andere of alle blogs uit de database. Als de bezoeker is ingelogd dan zal onder de if voorwaarde gefilterd worden
        $dbOtherBlogs = new Database();
        $dbOtherBlogs->select("blogId, title, createdAt")->from("dfr_blogs");

        if (AuthorisedAccess::LoggedIn()) {
            //Indien bezoeker is ingelogd dan worden zijn blogs uit deze array gefilterd worden
            $dbOtherBlogs = $dbOtherBlogs->where("userAccountsId!=" . $_SESSION["UserId"]);

            //Indien bezoeker is ingelogd dan zal de onderstaande array gevuld worden mijn blogs van deze bezoeker zodat deze appart weergegeven worden
            //op de website.
            $dbMyBlogs = new Database();
            $dbMyBlogs->select("blogId, title, createdAt")->from("dfr_blogs")->where("userAccountsId=" . $_SESSION["UserId"]);
            $viewData["myblogs"] = $dbMyBlogs->get();
        }
        $viewData["otherblogs"] = $dbOtherBlogs->get();


        parent::View("blogs/blogsoverzicht", $viewData);
    }

    public static function create()
    {


        $viewData = [];

        //hieronder worden enkele variable alvast ingevuld. Indien er geen post input is verzonden dan zullen de variable null meekrijgen.
        $viewData["blog_file"] = Request::input("blog_file");
        $viewData["blog_title"] = Request::input("blog_title");
        $viewData["blog_content"] = Request::input("blog_content");


        //Als er op opslaan is gedrukt dan wordt onderstaande uitgevoerd
        if (Request::input("submit") == "Opslaan") {

            // start formulier validatie
            $fv = new FormValidation();

            $fv->setValidationRule("blog_title", "Blog Titel", "required|min_length[2]");
            $fv->setValidationRule("blog_content", "Blog inhoud", "required|min_length[5]");

            $fv->run();

            // als er fouten zijn ontdekt tijdens de validatie dan zal onderstaande if uitgevoerd worden. 
            // Indien alles goed dan mag else uitgevoerd worden
            if ($fv->hasErrors() == true) {
                $viewData["formerrors"] = $fv->Errors();
            } else {
                if (self::createBlog($viewData)) { //createblog returns true als insert goed is uitgevoerd
                    parent::View("blogs/blogopgeslagen");
                }
            }
        }

        parent::View("blogs/createblog", $viewData);
    }

    public static function readAndedit()
    {
        $id = Segments::getSegment(2);

        //Check of het betreffende segment een cijfer is. 
        if (is_numeric($id)) {

            //Check of de bezoeker is ingelogd
            $viewData["AuthorisedAccess"] = (AuthorisedAccess::LoggedIn()) ?  true : false;

            //Check of de blog pagina in read of edit mode moet staan.
            $viewData["editblog"] = (Segments::getSegment(1) == "edit") ? true : false;

            //Blog data ophalen en toevoegen aan de viewData array zodat het een geheel vormt.
            $viewData =  array_merge($viewData, self::getBlogData($id));

            //Check of bezoeker de eigenaar is van deze blog. 
            $viewData["blog_owner"] = ($viewData["blog_userAccountsId"] == $_SESSION["UserId"]) ? true : false;

            //Als er op opslaan is gedrukt en segment 1 bevat edit dan wordt onderstaande uitgevoerd
            if (Request::input("submit") == "Opslaan" && $viewData["editblog"] = true) {

                $fv = new FormValidation();

                $fv->setValidationRule("blogtitle", "Blog Titel", "required|min_length[2]");
                $fv->setValidationRule("blogcontent", "Blog inhoud", "required|min_length[5]");

                $fv->run();

                // Eerder is vastgesteld of de actie door de eigenaar van deze blog is verzonden of iemand anders. Indien dit niet de eigenaar is
                //dan mag deze persoon het bestand niet aan kunnen passen. Er verschijnt dan een foutmelding
                if ($viewData["blog_owner"] != true) {
                    $fv->addCustomErrorMessage("Autorisatie", "U bent beschikt niet over de juiste rechten om deze blog aan te passen.");
                }

                if ($fv->hasErrors() == true) {
                    $viewData["formerrors"] = $fv->Errors();
                } else {
                    self::updateBlog($id, Request::input("blogtitle"), Request::input("blogcontent"), Request::input("blogfile"));
                    $viewData["successful"] = true;
                }
            }



            //De content ophalen
            $viewData["blog_content"]  = self::getBlogContent($viewData["blog_file"]);

            //De naam van de auteur ophalen
            $viewData["blog_author"] = self::getBlogAuthor($viewData["blog_userAccountsId"]);



            parent::View("blogs/blog", $viewData);
        } else {
            header("Location: " . __BASEURL__ . "/blogs");
        }
    }


    private static function getBlogAuthor($AuthorId): string
    {

        $dbBlogAuthor = new Database();
        $dbBlogAuthor->select("name")->from("dfr_userAccounts")->where("id=" . $AuthorId);
        $dbBlogAuthorData = $dbBlogAuthor->get();

        if (isset($dbBlogAuthorData[0]["name"])) {
            return $dbBlogAuthorData[0]["name"];
        }
        return "anoniem";
    }

    private static function getBlogData($id): array
    {
        $returnvalue = [];
        $dbBlog = new Database();
        $dbBlog->select("*")->from("dfr_blogs")->where("blogId=" . $id);
        $blogViewData = $dbBlog->get();

        foreach ($blogViewData[0] as $key => $val) {

            $returnvalue["blog_" . $key] = $val;
        }

        return $returnvalue;
    }

    private static function getBlogContent($file): string
    {
        $returnvalue = "Geen inhoud gevonden.";
        $blogfilepath = _UPLOADDIR_ . "/blogs/blog_" . $file . ".html";
        if (file_exists($blogfilepath)) {
            ob_start();
            require_once($blogfilepath);
            $returnvalue = ob_get_contents();
            ob_end_clean();
        }
        return $returnvalue;
    }

    private static function createBlog($viewData)
    {
        $filename = "blog_" . date("Ymdhis") . "_" . self::generateRandomString();

        if (!file_exists(_UPLOADDIR_ . "/blogs/" . $filename . ".html")) {

            self::writeContentFile($viewData["blog_content"], $filename);

            $createDB = new Database();
            $createDB->table("dfr_blogs");
            $dbdata = [
                "title" => html_entity_decode($viewData["blog_title"]),
                "userAccountsId" => $_SESSION["UserId"],
                "file" => $filename
            ];

            if ($createDB->insert($dbdata) == 1) {
                return true;
            }
        }
        return false;
    }

    private static function updateBlog($id, $title, $content, $filename)
    {

        $updatedb = new Database();
        $dataToUpdate = [
            "title" => html_entity_decode($title),
        ];
        $updatedb->from("dfr_blogs")->where("blogId=" . $id)->update($dataToUpdate);

        self::writeContentFile($content, $filename);
    }

    private static function writeContentFile($content, $file)
    {
        FileHandler::write(_UPLOADDIR_ . "/blogs/", $file . ".html", html_entity_decode($content), "w");
    }

    public static function generateRandomString($numberOfCharacters = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $numberOfCharacters; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
