<html>
    <head>
        <title>UnitTest <?php echo basename($sut); ?></title>
    </head>
    <style>
        .status-bar{
            padding:15px;
        }
        .win {
            background-color:green;
            color:white;
        }
        .fail {
            background-color:red;
            color:white;
        }
        .unknown {
            background-color:yellow;
            color:black;
        }
    </style>
    <body>

    <h1><?php echo basename($sut); ?></h1>
    <div class='status-bar <?php echo $result_type; ?>'>
            <?php
                if($result_type == 'unknown'){
                    echo 'An unknown issue was encountered.';
                } else {
                    echo $status_bar_text;
                }
            ?>
    </div>
        <?php
        if($result_type != 'win'){
            echo '<pre>' . implode("\n", $raw_output) . '</pre>';
        }
        ?>
    </body>
</html>