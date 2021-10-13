<?php
include "conn.php"; //establish connection

if (isset($_POST["del"])) {
    mysqli_query($conn, "DELETE FROM codes");
}

//We will generate the codes here
function generateRandomString($length = 13)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//process submission
if (isset($_POST["submitB"])) {
    $amount = $_POST["amount"];
    for ($i = 0; $i < $amount; $i++) {
        $stmt = $conn->prepare("INSERT INTO codes (CODE) VALUES (?)");
        $randString = generateRandomString();
        $stmt->bind_param("s", $randString);
        $stmt->execute();
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Codes</title>

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.7.6/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.7.6/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.7.6/dist/js/uikit-icons.min.js"></script>
    <link rel="stylesheet" href="styles/style.css">
    <title>Simple Verification</title>
    <!-- Stop php resubmission on refresh -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body>
    <div style="margin-top: 100px;" class="uk-container">
        <form action="" method="POST">
            <div style="width: 440px;" class="uk-inline">
                <div>
                    <a class="uk-form-icon uk-form-icon-flip" href="" uk-icon="icon: forward"></a>
                    <input type="number" name="amount" placeholder="Enter the desired amount of codes to be generated" class="uk-input">
                </div>
            </div>
            <button type="submit" name="submitB" class="uk-button uk-button-secondary">GENERATE</button>
        </form>


        <h5>List of working codes:</h5>
        <table class="uk-table">
            <caption></caption>
            <thead>
                <tr>
                    <th>Codes</th>
                    <th>
                        <form action="" method="POST">
                            <button type="submit" name="del" class="uk-button uk-button-danger uk-button-small">Delete All Codes</button>
                        </form>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo "<tr>";
                $all_codes = mysqli_query($conn, "SELECT * FROM codes");
                while ($row = mysqli_fetch_object($all_codes)) {

                    echo "<td>" . $row->code . "</td></tr>";
                }
                echo "</tr>";
                ?>
            </tbody>
        </table>
</body>

</html>