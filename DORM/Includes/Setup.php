<?php
namespace DORM\Includes;

use DORM\Config\Config;
use DORM\Database\DBHandler;

class Setup
{
    private $connection = null;

    public function __construct()
    {
        if (isset($_GET["dbConfig"]) && $_GET["dbConfig"] != null) {
            $this->connection = new DBHandler($_GET["dbConfig"]);
        } else {
            $this->connection = new DBHandler();
        }
        $this->render();
    }

    public function render() {

        if (isset( $_POST["init-dortdb"])) {
            $this->connection->setDormDB();
        }
        
        if (isset($_POST["generate-models"]) && TableToModel::writeAccess()) {

            $this->connection->setDormDB();
            // TODO: check have write acces
            // TODO: Refactor
            if (isset($_POST["selectedTables"])) {

                echo 'generated new models: ';
                echo '<br>';

                foreach ($_POST['selectedTables'] as $value) {
                    // TODO: Refactor TableToModel class
                    $model = (new TableToModel(
                        $value,
                        $this->connection->getColumns($value),
                        $this->connection->getTableReferences($value),
                    ))
                    ->writeFile();
                    $this->connection->insertModel($model['tableName'], $model['className']);

                    echo $value;
                    echo '<br>';
                }
            }
        }

        if (isset($_POST["restapi-request"])) {
            echo 'REST-API Requested: ';
        }

        $webRoot = realpath(dirname(__FILE__));
        $serverRoot = realpath($_SERVER['DOCUMENT_ROOT']);
        $pathToWebRoot = "";

        if ($webRoot === $serverRoot) {
            $pathToWebRoot = "";
        } else {
            $pathToWebRoot = substr($webRoot, strlen($serverRoot) + 1);
        }

        // TODO: Refactor/remove
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '') ? 'https://' : 'https://';

        // TODO: Create concept for MVC Setup Page ...
        // include_once dirname(__FILE__) . '/UI/views/playground.php';
?>
        <link rel="stylesheet" href="<?php echo $protocol . $_SERVER['HTTP_HOST'] . '/' . $pathToWebRoot . '/UI/assets/setup.css' ?>">
        <!-- TODO: Download CDN to local -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <style type="text/css">
            @font-face {
                font-family: "Roboto";
                src: url(<?php echo $protocol . $_SERVER['HTTP_HOST'] . '/' . $pathToWebRoot . '/UI/assets/Roboto-Regular.ttf' ?>) format("truetype");
            }

            * {font-family: Roboto, Helvetica;}
        </style>

        <div id="dorm-setup">
            <div id="dorm-content">
                <h1> Setup the DORM </h1>

                <form>
                    <select name="dbConfig">
                        <!-- TODO: Refactor/Move -->
                        <?php foreach (Config::$database as $key => $value): ?>
                            <option value="<?php echo $key ?>"><?php echo $key ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" value="Select Config">
                </form>

                <div class="tab">
                    <button id="defaultOpen" class="tablinks" onclick="openTab(event, 'requests')">API Request</button>
                    <button class="tablinks" onclick="openTab(event, 'generator')">Model Generator</button>
                </div>

                <div id="requests" class="box tabcontent">
                    <div>
                        <select id="apiprotocol">
                            <option value="https://">https://</option>
                            <option value="http://">http://</option>
                        </select>
                        <input id="apiurl" type="text">
                    </div>

                    <h2>Response:</h2>
                    <textarea id="response" rows="20"></textarea>
            	    <h2>Request:</h2>
                    <textarea id="requestJob" rows="20">
                        {
                            "schema": "DORM 0.0.3",
                            "token": "1234556",
                            "jobs": [
                                {
                                    "job": "read",
                                    "from": "person",
                                    "columns": [
                                        { "column": "surname"},
                                        { "column": "name"}
                                    ],
                                    "where": [
                                        {
                                            "column": "person_id",
                                            "value": 80,
                                            "condition": "="
                                        }
                                    ]  
                                }   
                            ]
                        }
                        </textarea>
                    <button class="btn" onclick="request()">Request</button>

                </div>

                <div id="generator" class="box tabcontent">
                    <h2> Tables found in the databes </h2>
                    <form id="model-generator" method="POST">
                        <div>
                            <?php foreach ($this->connection->getTables() as $value): ?>
                                <div>
                                    <input type="checkbox" name="selectedTables[]" 
                                        id="<?php echo $value ?>" value="<?php echo $value ?>">
                                    <label for="<?php echo $value ?>"><?php echo $value ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="btn-container">
                            <input class="btn" type="submit" name="generate-models" value="Re-/Create model classes">
                            <input class="btn" type="submit" name="init-dortdb" value="DORM DB INIT">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="toast">

        </div>

        <script src="<?php echo $protocol . $_SERVER['HTTP_HOST'] . '/' . $pathToWebRoot . '/UI/assets/setup.js' ?>"></script>
<?php
    }
}
?>