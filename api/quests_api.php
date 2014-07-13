<?php

require_once 'abstract_api.php';
require_once 'db_utils.php';

class QuestsAPI extends API
{
    const DB_ERROR_MSG = "Failed to query database";

    public function __construct($request, $origin) {
        parent::__construct($request);
    }

    private function _result($status, $message, $data = false) {
        return array("status" => $status, "message" => $message, "data" => $data);
    }

    /*
    *   API "quests" endpoint
    */
    protected function quests() {
        switch ($this->verb) {
            case "get":
                if ($this->method != "GET") {
                    return $this->_result(false, "This method only accepts GET requests! (quests.get)");
                }
                return $this->getQuests();
                break;
            case "add":
                if ($this->method != "POST" && $this->method != "PUT") {
                    return $this->_result(false, "This method accepts POST or PUT requests! (quests.add)");
                }
                return $this->addQuest();
                break;
            case "edit":
                if ($this->method != "POST" && $this->method != "PUT") {
                    return $this->_result(false, "This method accepts POST or PUT requests! (quests.edit)");
                }
                return $this->editQuest();
                break;
            case "remove":
                if ($this->method != "POST" && $this->method != "DELETE") {
                    return $this->_result(false, "This method accepts POST or DELETE requests! (quests.remove)");
                }
                return $this->removeQuest();
                break;
            default:
                break;
        }
    }

    /*
    *   DB Queries and Whatnot
    */

    private function getQuests() {
        $params = array();
        $sql = "SELECT * FROM quests ORDER BY";
        if (count($this->args) > 0 && $this->args[0] == "random") {
            $sql .= " RAND()";
        }
        else {
            $sql .= " ts ASC";
        }
        if (array_key_exists("limit", $this->request)) {
            $params["limit"] = $this->request["limit"];
            $sql .= " LIMIT :limit";
            if (array_key_exists("offset", $this->request)) {
                $params["offset"] = $this->request["offset"];
                $sql .= ", :offset";
            }
        }
        $query = db_select($sql, $params);
        if ($query) {
            return $this->_result(true, "Success (quests.get)", $query);
        }
        return $this->_result(false, self::DB_ERROR_MSG . " (quests.get)");
    }

    private function addQuest() {
        $params = array();
        $sql = "INSERT INTO quests (name, info) VALUES (:name, :info)";
        if (!array_key_exists("name", $this->request) || !array_key_exists("info", $this->request)) {
            return $this->_result(false, "Must include quest 'name' and 'info'! (quests.add)");
        }
        $params["name"] = $this->request["name"];
        $params["info"] = $this->request["info"];
        $query = db_insert($sql, $params);
        if ($query) {
            return $this->_result(true, "Success (quests.add)", true);
        }
        return $this->_result(false, self::DB_ERROR_MSG . " (quests.add)");
    }

    private function editQuest() {
        $params = array();
        $sql = "UPDATE quests SET";
        if (!array_key_exists("id", $this->request)) {
            return $this->_result(false, "Must include quest id! (quests.edit)");
        }
        if (array_key_exists("name", $this->request)) {
            $sql .= " name = :name,";
            $params["name"] = $this->request["name"];
        }
        if (array_key_exists("info", $this->request)) {
            $sql .= " info = :info,";
            $params["info"] = $this->request["info"];
        }  
        if (count($params) == 0) {
            return $this->_result(false, "Must include quest 'name' and/or 'info'! (quests.edit)"); 
        }
        $sql = substr($sql, 0, -1);
        $sql .= " WHERE id = :id";
        $params["id"] = $this->request["id"];
        $query = db_update($sql, $params);
        if ($query) {
            return $this->_result(true, "Success (quests.edit)", true);
        }
        return $this->_result(false, self::DB_ERROR_MSG . " (quests.edit)");
    }

    private function removeQuest() {
        $params = array();
        if (!array_key_exists("id", $this->request)) {
            return $this->_result(false, "Must include quest id! (quests.remove)");
        }
        $sql = "DELETE FROM quests WHERE id = :id";
        $params["id"] = $this->request["id"];
        $query = db_delete($sql, $params);
        if ($query) {
            return $this->_result(true, "Success (quests.remove)", true);
        }
        return $this->_result(false, self::DB_ERROR_MSG . " (quests.remove)");
    }

    /*
     * Example of an Endpoint
     */
     protected function example() {
        switch ($this->verb) {
            case "get":
                if ($this->method == 'GET') {
                    return array("status" => "success", "endpoint" => $this->endpoint, "verb" => $this->verb, "args" => $this->args, "request" => $this->request);
                } 
                else {
                    return "Only accepts GET requests";
                }
                break;
            case "post":
                if ($this->method == 'POST') {
                    return array("status" => "success", "endpoint" => $this->endpoint, "verb" => $this->verb, "args" => $this->args, "request" => $this->request);
                } 
                else {
                    return "Only accepts POST requests";
                }
                break;
            case "delete":
                if ($this->method == 'PUT') {
                    return array("status" => "success", "endpoint" => $this->endpoint, "verb" => $this->verb, "args" => $this->args, "request" => $this->request);
                } 
                else {
                    return "Only accepts PUT requests";
                }
                break;
            case "put":
                if ($this->method == 'DELETE') {
                    return array("status" => "success", "endpoint" => $this->endpoint, "verb" => $this->verb, "args" => $this->args, "request" => $this->request);
                } 
                else {
                    return "Only accepts DELETE requests";
                }
                break;
            default:
                break;
        }
        
     }
 }

?>