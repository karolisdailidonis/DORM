<?php

namespace DORM\Includes;

class Setup
{

    function __construct()
    {
        $this->render();
    }

    public function render()
    {
        if (isset($_POST["generate-models"])) {
            echo 'generated new models: ';
            // foreach ($variable as $key => $value) {
                // ( new TableToModel() )->writeFile();
            // }
        }

        if (isset($_POST["restapi-request"])) {
            echo 'REST-API Requested: ';
        }

?>
        <style>
            #dorm-setup {
                display: flex;
                justify-content: center;
            }

            #dorm-content {
                max-width: 500px;
            }

            #dorm-setup form {
                border: thin solid grey;
            }
        </style>

        <div id="dorm-setup">
            <div id="dorm-content">
                <h1> Setup the DORM </h1>
                <h2> Tables found in the databes </h2>
                <form id="model-generator" method="POST">
                    <div>
                        <div>
                            <input type="checkbox" name="table" id="real_table_name" value="real_table_name">
                            <label for="vehicle1">real_table_name</label>
                        </div>
                        <div>
                            <input type="checkbox" name="table" id="real_table_name" value="real_table_name">
                            <label for="vehicle1">real_table_name</label>
                        </div>
                        <div>
                            <input type="checkbox" name="table" id="real_table_name" value="real_table_name">
                            <label for="vehicle1">real_table_name</label>
                        </div>
                        <div>
                            <input type="checkbox" name="table" id="real_table_name" value="real_table_name">
                            <label for="vehicle1">real_table_name</label>
                        </div>
                    </div>
                    <div>
                        <input class="btn" type="submit" name="generate-models" value="Re-/Create model classes">
                    </div>
                </form>

                <h2>REST-API Request</h2>
                <form id="rest-request" method="POST">
                    <div>
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
                        <div>
                            <input class="btn" type="submit" name="restapi-request" value="Request">
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php
    }
}
?>