<?php

namespace system\lib;

class FormValidation
{
    private $_validations = array();
    private $_errorMessage = [];

    /**
     * setValidationRule
     * Met deze functie kan per veld (field) regels waaraan het moet voldoen opgegeven worden. 
     * De run functie voert deze regels later uit.
     * @param  mixed $field - dit is het formulier veldnaam
     * @param  mixed $label - dit is de gebruiksvriendelijke naam. Veelal gelijk aan de label naam
     * @param  mixed $rules - dit zijn de regels waarop het veld (field) gecontroleerd moet worden
     * @return void
     */
    public function setValidationRule($field, $label, $rules)
    {
        $this->_validations[$field]["label"] = $label;
        $this->_validations[$field]["rules"] = $rules;
    }

    /**
     * run
     * Deze functie voert alle veld (field) validaties uit die eerder met setValidationRule stuk voor stuk zijn
     * ingesteld.
     * @return void
     */
    public function run()
    {
        //Doorloop alle validatie regels zoas die zijn ingesteld bij setValidatienRule
        foreach ($this->_validations as $key => $val) {

            // De explode functie hieronder split de rules op in losse voorwaarden. 
            $rules = explode('|', $val["rules"]);

            //Hieronder wordt de input waarde opgehaald.
            $input = Request::input($key);

            //Doorloop alles regels zoals
            foreach ($rules as $rule) {

                $this->required($rule, $key, $val["label"], $input);
                $this->min_length($rule, $key, $val["label"], $input);
                $this->max_length($rule, $key, $val["label"], $input);
                $this->matches($rule, $key, $val["label"], $input);
                $this->valid_email($rule, $key, $val["label"], $input);
                $this->valid_password($rule, $key, $val["label"], $input);
                $this->isUnique($rule, $key, $val["label"], $input);
                $this->login($rule, $key, $input);
            }
        }
    }

    /**
     * hasErrors
     * Deze functie controleerd of er errors zijn ontstaan bij het valideren van de velden. 
     * In dien ja dan stuurt de functie true terug. Indien nee dan stuurt de functie false terug.
     * @return boolean stuurt true of false terug
     */
    public function hasErrors()
    {
        if (count($this->_errorMessage) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Errors
     * De functie stuurt een verzameling van error meldingen terug
     * @return array
     */
    public function Errors()
    {
        return $this->_errorMessage;
    }

    public function addCustomErrorMessage($key, $message)
    {
        $this->_errorMessage[$key][] = $message;
    }


    /**
     * required
     * Deze functie controleerd of er een waarde is ingevuld. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde required bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $label - dit is de gebruiksvriendelijke naam van in het formulier 
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function required($rule, $key, $label, $input)
    {

        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if ($rule == "required") {

            //controleren of $input gelijk is aan null. Zo ja dasn zal er een regel aan $_errorMessage
            //toegevoegd worden.
            if ($input == null) $$this->_errorMessage[$key][][$key][] = "Het veld " . $label . " moet inhoud bevatten.";
        }
    }

    /**
     * min_length
     * Deze functie controleerd of de input aan de minimale lengte voldoet. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde min_length bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $label - dit is de gebruiksvriendelijke naam van in het formulier 
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function min_length($rule, $key, $label, $input)
    {
        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if (preg_match('/(min_length)\[(.*)\]/i', $rule)) {

            //Deze filter zorgt er voor dat alleen de waarde getal van min_length[getal] overblijft.
            $min_length = preg_filter('/(min_length)\[(.*)\]/i', '$2', $rule);

            //controleren of $input niet gelijk is aan null. 
            if ($input != null) {

                //Controleren of $input aan de minimale string lengte voldoet. Indien niet het geval
                //dan zal er een regel aan errorMessage toegevoegd worden.
                if (mb_strlen($input) < $min_length) $this->_errorMessage[$key][] = "Het veld " . $label . "moet minimaal " . $min_length . " karakters bevatten";
            } else {

                $this->_errorMessage[$key][] = "Het veld " . $label . " moet minimaal " . $min_length . " karakters bevatten";
            }
        }
    }

    /**
     * max_length
     * Deze functie controleerd of de input aan de maximale lengte voldoet. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde max_length bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $label - dit is de gebruiksvriendelijke naam van in het formulier 
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function max_length($rule, $key, $label, $input)
    {

        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if (preg_match('/(max_length)\[(.*)\]/i', $rule)) {

            //Deze filter zorgt er voor dat alleen de waarde getal van max_length[getal] overblijft.
            $max_length = preg_filter('/(max_length)\[(.*)\]/i', '$2', $rule);

            //controleren of $input niet gelijk is aan null. 
            if ($input != null) {

                //Controleren of $input aan de maximale string lengte voldoet. Indien niet het geval
                //dan zal er een regel aan errorMessage toegevoegd worden.
                if (mb_strlen($input) > $max_length) $this->_errorMessage[$key][] = "Het veld " . $label . " mag niet meer dan " . $max_length . " karakters bevatten";
            } else {

                $this->_errorMessage[$key][] = "Het veld " . $label . "  mag niet meer dan  " . $max_length . " karakters bevatten";
            }
        }
    }

    /**
     * matches
     * Deze functie controleerd of de input overeenkomt met het andere veld. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde matches bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $label - dit is de gebruiksvriendelijke naam van in het formulier 
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function matches($rule, $key, $label, $input)
    {

        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if (preg_match('/(matches)\[(.*)\]/i', $rule)) {

            //Deze filter zorgt er voor dat alleen de waarde matchfield van matches[matchfield] overblijft.
            $matchingfield = preg_filter('/(matches)\[(.*)\]/i', '$2', $rule);

            //value ophalen van $matchfield.
            $matchingfieldvalue = Request::input($matchingfield);

            //Controleren of $input en $matchfieldvalue gelijk zijn. 
            //Indien de input niet aan de voorwaarde voldoet dan wordt er een regel aan errorMessage toegevoegd.
            if ($input !== $matchingfieldvalue) {

                //Ophalen van label van het matching field zodat dit netjes vermeld kan worden inde errorMessage. 
                //Indien er geen label gevonden kan worden omdat er geen ValidationRule voor dit veld is ingesteld dan
                //wordt matchingfield de matchinglabel naam.
                (isset($this->_validations[$matchingfield]["label"])) ? $matchingLabel = $this->_validations[$matchingfield]["label"] : $matchingLabel = $matchingfield;
                $this->_errorMessage[$key][] = "Het veld " . $label . " komt niet overeen met veld" . $matchingLabel;
            }
        }
    }

    /**
     * valid_email
     * Deze functie controleerd of de input een geldig e-mail adres bevat. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde valid_email bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $label - dit is de gebruiksvriendelijke naam van in het formulier 
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function valid_email($rule, $key, $label, $input)
    {

        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if ($rule == "valid_email") {

            //Controleren of $input een geldig e-mail adres bevat. 
            //Indien de input niet aan de voorwaarde voldoet dan wordt er een regel aan errorMessage toegevoegd.
            if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $this->_errorMessage[$key][] = "Het veld " . $label . " moet een geldig e-mail adres bevatten.";
            }
        }
    }

    /**
     * valid_password
     * Deze functie controleerd of de input een geldig wachtwoord bevat. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde valid_password bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $label - dit is de gebruiksvriendelijke naam van in het formulier 
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function valid_password($rule, $key, $label, $input)
    {

        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if ($rule == "valid_password") {

            //Controleren of het aan onderstaande voorwaarde voldoet
            $containsLetter  = preg_match('/[a-zA-Z]/',    $input);
            $containsDigit   = preg_match('/\d/',          $input);
            $containsSpecial = preg_match('/[^a-zA-Z\d]/', $input);

            //Indien de input niet aan de voorwaarde voldoet dan wordt er een regel aan errorMessage toegevoegd.
            if ((($containsLetter == true) and ($containsDigit == true) and ($containsSpecial)) != true) {
                $this->_errorMessage[$key][] = "Het " . $label . " moet uit letters bestaan. Daarnaast moet het minimaal 1 cijfer en 1 speciale teken bevatten .";
            }
        }
    }

    /**
     * isUnique
     * Deze functie controleerd of de input een unieke waarde bevat. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde isUnique bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $label - dit is de gebruiksvriendelijke naam van in het formulier 
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function isUnique($rule, $key, $label, $input)
    {
        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if (preg_match('/(is_unique)\[(.*)\]/i', $rule)) {

            //Deze filter zorgt er voor dat alleen de waarde table.column van is_unique[table.column] overblijft.
            $TableAndColumn = preg_filter('/(is_unique)\[(.*)\]/i', '$2', $rule);

            // de string table.column verder scheiden 
            [$table, $column] = explode(".", $TableAndColumn, 2);

            //controleren of $input ook echt uniek is. 
            $d = new Database();
            $numberOfResults = $d->from($table)->where($column . "=" . $input)->count();

            //Indien niet uniek dan errorMessage schrijven
            if ($numberOfResults > 0) {
                $this->_errorMessage[$key][] = "Het {$label} {$input} bestaat al.";
            }
        }
    }

    /**
     * login
     * Deze functie controleerd of het een geldige login is. Voordat deze functie dit 
     * controleerd zal het eerst controleren of de regel ook uitgevoerd moet worden. 
     * Als de parameter $rule de waarde login bevat dan wordt de rest uitgevoerd.
     * Deze functie geeft een waarde aan $_errorMessage mee als niet aan de voorwaarde 
     * voldaan wordt.
     * @param  mixed $rule - deze parameter bevat de voorwaarde waaraan de input moet voldoen
     * @param  mixed $key - dit is de veldnaam van het formulier
     * @param  mixed $input - dit is de waarde van het betreffende veld ofwel Value;
     * @return void
     */
    protected function login($rule, $key, $input)
    {
        //controleerd of $rule de waarde login bevat. Indien ja dan zal onder de voorwaarde if 
        //verder uitgevoerd worden
        if (preg_match('/(login)\[(.*)\]/i', $rule)) {

            //Deze filter zorgt er voor dat alleen de waarde gebruikersnaam van login[gebruikersnaam] overblijft.
            $username = preg_filter('/(login)\[(.*)\]/i', '$2', $rule);

            // Controleerd of de login correct is. Indien niet het geval dan zal onder deze voorwaarde een 
            // errorMessage aangemaakt worden.

            if (AuthorisedAccess::checkLogin(Request::input($username), $input) != true) {
                $this->_errorMessage[$key][] = "Deze combinatie van gebruikersnaam en wachtwoord is bij ons niet bekend.";
            }
        }
    }
}
