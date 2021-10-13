<?php
include "conn.php"; //establish the connection


session_start();
//session_destroy(); <- you should enable this for testing purposes!

//Turn off errors (optional)
error_reporting(0);

if ($session_life > $inactive) {
    session_destroy();
    header("Location:index.php");
}


//backend control
if (isset($_POST["submitB"])) {
    $code = $_POST["code"]; //store the verification code once submitted

    $stmt = $conn->prepare("SELECT * FROM codes WHERE code = ?");
    /* Bind parameters */
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->store_result();

    /* Fetch the value */
    $stmt->fetch;
    $rows = $stmt->num_rows;

    /* Close statement */
    $stmt->close;

    if (strlen($code) > 12) {
        if ($rows > 0) {
            $response = "Verification Successful!";
            $responseC = "green";

            $inactive = 60 * 60 * 24; //saves session for one day
            if (!isset($_SESSION['timeout'])) {
                $_SESSION['timeout'] = time() + $inactive;

                $session_life = time() - $_SESSION['timeout'];

                $_SESSION['timeout'] = time();
            }

            mysqli_query($conn, "DELETE FROM codes WHERE code = '$code'");
        } else {
            $response = "Verification Failed - wrong code entered";
            $responseC = "red";
        }
    } else {
        $response = "Verification Failed - code needs to be 13 characters long!";
        $responseC = "red";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

<body class="uk-countdown uk-animation-scale-up uk-transform-origin-bottom-right">
    <div style="margin-top: 100px;" class="uk-container">
        <?php if (!isset($_SESSION['timeout'])) { ?>
            <ul class="uk-breadcrumb">
                <li><span>Introduction</span></li>
            </ul>
            <div class="uk-column">
                <p>Welcome to my little fun project! In which you are able to generate a desired number of codes and save them in the database. The codes mentioned can then be queried in the registration system and are then deleted so that no further access is possible. My highest priority is lightweight, security, and responsivness. Star this project on <a href="https://github.com/xyba1337/Temporary-website-whitelist-using-an-invite-code">GitHub</a>!</p>
            </div>


            <hr>
            <br>
            <!-- Start of security section -->

            <ul class="uk-breadcrumb">
                <li><span>The verification system</span></li>
            </ul>



            <article class="uk-article">

                <h1 class="uk-article-title"><a class="uk-link-reset" href="">Enter code to register:</a></h1>

                <p class="uk-article-meta">Please enter your verification/invitation code in order to visit the main page</p>


                <form action="" method="POST">
                    <div class="uk-inline">
                        <span class="uk-form-icon uk-form-icon-flip uk-icon" uk-icon="icon: lock"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <rect fill="none" stroke="#000" height="10" width="13" y="8.5" x="3.5"></rect>
                                <path fill="none" stroke="#000" d="M6.5,8 L6.5,4.88 C6.5,3.01 8.07,1.5 10,1.5 C11.93,1.5 13.5,3.01 13.5,4.88 L13.5,8"></path>
                            </svg></span>
                        <input required minlength="13" name="code" class="uk-input" type="text">
                    </div>

                    <button type="submit" name="submitB" class="uk-button uk-button-secondary">SUBMIT</button>
                </form>

                <div class="uk-grid-small uk-child-width-auto uk-grid" uk-grid="">
                    <div class="uk-first-column">
                        <a class="uk-button uk-button-text" href="#">My code doesn't work</a>
                    </div>
                    <div>
                        <a class="uk-button uk-button-text" href="#">Where to get a code?</a>
                    </div>
                </div>

                <p style="color: <?php echo $responseC; ?>;"><?php echo $response; ?></p>

            </article>

        <?php } else {  ?>
            <h3 style="color: green;">Welcome verified user!</h3>
            <p>Star this project on <a href="https://github.com/xyba1337/Temporary-website-whitelist-using-an-invite-code">GitHub</a> if you found it helpful. ⭐❤️</p>


        <?php } ?>
    </div>

</body>

</html>
