<?php

namespace DORM\Includes;

use DORM\Database\DBHandler;

class Setup
{

    private $connection = null;

    function __construct()
    {

        $this->connection = new DBHandler();
        $this->render();
    }

    public function render()
    {

        $this->connection->setDormDB();

        if (isset($_POST["generate-models"])) {

            if (isset($_POST["selectedTables"])) {

                echo 'generated new models: ';
                echo '<br>';

                foreach ($_POST['selectedTables'] as $value) {

                    $model = (new TableToModel(
                                $value, 
                                $this->connection->getColumns($value),
                                $this->connection->getTableReferences($value),
                                )
                            )->writeFile();
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

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '') ? 'https://' : 'http://';

?>
        <link rel="stylesheet" href="<?php echo $protocol . $_SERVER['HTTP_HOST'] . '/' . $pathToWebRoot . '/assets/setup.css' ?>">
        <style type="text/css">
            @font-face {
                font-family: "Roboto";
                src: url(<?php echo $protocol . $_SERVER['HTTP_HOST'] . '/' . $pathToWebRoot . '/assets/Roboto-Regular.ttf' ?>) format("truetype");
            }

            * {
                font-family: "Roboto";
            }
        </style>

        <div id="dorm-setup">
            <div id="dorm-content">
                <h1> Setup the DORM </h1>
                <div class="box">
                    <h2> Tables found in the databes </h2>
                    <form id="model-generator" method="POST">
                        <div>
                            <?php foreach ($this->connection->getTables() as $value) { ?>
                                <div>
                                    <input type="checkbox" name="selectedTables[]" id="<?php echo $value ?>" value="<?php echo $value ?>">
                                    <label for="<?php echo $value ?>"><?php echo $value ?></label>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="btn-container">
                            <input class="btn" type="submit" name="generate-models" value="Re-/Create model classes">
                        </div>
                    </form>
                </div>
                <!-- <div class="box">
                    <h2>REST-API Request</h2>
                    <form id="rest-request" method="POST">
                        <div class="code">
                            {
                            json output
                            }
                            {
                            json request
                            }
                        </div>
                        <div>
                            <div>
                                <input type="text" placeholder="Table name">
                                <input type="text" placeholder="columns">
                                <input type="text" placeholder="where">
                            </div>
                            <div class="btn-container">
                                <input class="btn" type="submit" name="restapi-request" value="Request">
                            </div>
                        </div>
                    </form>
                </div> -->
            </div>
    <?php

    
    }

}
    ?>