<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));

function sqlConnect($database = "") {
    global $configuration;

    if ($database == "") $database = $configuration->db->gabeorama->dbname;
    //Open a new connection to specified database
    $db = $configuration->db->gabeorama;
    $mysqli = new mysqli($db->dbhost, $db->dbuser, $db->dbpass, $database);
    
    //Check for errors
    if ($mysqli->connect_errno) {
        echo "ERROR: " . $mysqli->connect_error . "\n";
        return false;
    }
    
    return $mysqli;
}
function createTable($table_name, $mysqli, $table_type = "") {
    $alterQuery = "";
    if ($table_type == "") $table_type = $table_name;
    
    switch($table_name) {
        case "users":
            prepareAndSendQuery($mysqli, "CREATE TABLE IF NOT EXISTS $table_name
              (ID int PRIMARY KEY AUTO_INCREMENT,
              registerDate dateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              username text NOT NULL,
              email text NOT NULL,
              passwordHash text NOT NULL,
              permissionsGroup varchar(32) NOT NULL DEFAULT 'unverified',
              fullName text,
              phoneNumber int,
              address text)");
            break;
        case "threads":
            prepareAndSendQuery($mysqli, "CREATE TABLE IF NOT EXISTS $table_name
                (ID int PRIMARY KEY AUTO_INCREMENT,
                PublishTime dateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                Author text NOT NULL,
                Title text NOT NULL,
                Content text NOT NULL,
                Type text)");
            break;
        case "surveys":
            prepareAndSendQuery($mysqli, "CREATE TABLE IF NOT EXISTS $table_name
                (Survey_ID int PRIMARY KEY AUTO_INCREMENT,
                Random_ID varchar(16) UNIQUE NOT NULL,
                CreationTime dateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                StartTime dateTime DEFAULT CURRENT_TIMESTAMP,
                ExpirationTime dateTime DEFAULT NULL,
                Title text NOT NULL,
                Author_ID int)");

            prepareAndSendQuery($mysqli, "ALTER TABLE $table_name ADD FOREIGN KEY (Author_ID) REFERENCES users(ID)");
            break;
        case "surveyQuestions":
            prepareAndSendQuery($mysqli, "CREATE TABLE IF NOT EXISTS $table_name
                (Question_ID int PRIMARY KEY AUTO_INCREMENT,
                Survey_ID int NOT NULL,
                QuestionText text NOT NULL,
                QuestionType text NOT NULL,
                SortPosition int NOT NULL)");

            prepareAndSendQuery($mysqli, "ALTER TABLE $table_name ADD FOREIGN KEY (Survey_ID) REFERENCES surveys(Survey_ID)");
            break;
        case "surveyOptions":
            prepareAndSendQuery($mysqli, "CREATE TABLE IF NOT EXISTS $table_name
                (Option_ID int PRIMARY KEY AUTO_INCREMENT,
                Question_ID int NOT NULL,
                OptionText text NOT NULL
                OptionValue text)");

            prepareAndSendQuery($mysqli, "ALTER TABLE $table_name ADD FOREIGN KEY (Question_ID) REFERENCES surveyQuestions(Question_ID)");
            break;
        case "surveyResponses":
            prepareAndSendQuery($mysqli, "CREATE TABLE IF NOT EXISTS $table_name
                (Response_ID int PRIMARY KEY AUTO_INCREMENT,
                Survey_ID int NOT NULL,
                Respondant_ID int NOT NULL,
                ResponseDate dateTime NOT NULL DEFAULT CURRENT_TIMESTAMP)");

            prepareAndSendQuery($mysqli, "ALTER TABLE $table_name ADD FOREIGN KEY (Survey_ID) REFERENCES surveys(Survey_ID)");
            prepareAndSendQuery($mysqli, "ALTER TABLE $table_name ADD FOREIGN KEY (Respondant_ID) REFERENCES users(ID)");
            break;
        default:
            return;
    }
}

function prepareAndSendQuery($mysqli, $query)
{
    $mysqli->query($query) or var_dump($mysqli->error);
}
?>