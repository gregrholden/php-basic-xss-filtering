<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <title>XSS Example</title>
        <script type='text/javascript'>
            window.onload = function(){
                document.getElementById("signature").focus();
            };        
        </script>
    </head>
    <body>
        <div id="wrapper">
            <h1>The XSS Blog</h1> 
            <h3><em>Learning about website vulnerabilities</em></h3>
            <!-- Form reloads current directory path, not index.php -->
            <form action="." method="post" name="guestbook">
                <fieldset>
                    <legend>Latest Post</legend>
                    <p>
                        Cross-Site Scripting (XSS) occurs when the software does 
                        not neutralize or incorrectly neutralizes user-controllable 
                        input before it is placed in output that is used as a 
                        web page that is served to other users.
                    </p>
                    <p>
                        <a href="index.php">Continue reading...</a>
                    </p>
                </fieldset>
                <fieldset>
                    <p>
                        Like what you're reading? Sign the guest book!
                    </p>
                    <input type='text' name='signature' id='signature' size='62' maxlength='1000'>
                    <input type='submit' name='submit' value='Submit'>
                    <input type='reset' name='reset' value='Reset'>
                </fieldset>
            </form>
            <fieldset>
                <legend>Guest Book</legend>
                <?php
                    if(isset($_POST["signature"])){
                        $guest = filter_input(INPUT_POST, "signature", FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                    if (empty($guest)){
                        echo '';
                    } else {
                        // Identify file
                        $file = "guestbook.txt";

                        // Get current contents of file
                        $current = file_get_contents($file);

                        // Add new entry
                        $current .= $guest . "\n";

                        // Write contents back to file
                        file_put_contents($file, $current);

                        // Message that signature was added
                        echo '<p><span class="red">Signature added</span></p><br>';

                        // Unset Post Data
                        unset($_POST['signature']);
                    }

                    // DISPLAY SIGNATURES

                    // "fopen" opens a file
                    // "r" means to get the data as "read only"
                    $fileOpen = fopen("guestbook.txt", "r");
                    // feof = "file end of file"; checks if line is the end of the file
                    while(!feof($fileOpen)){
                        // "fgets" reads file line by line
                        $line = fgets($fileOpen);
                        echo htmlspecialchars($line, ENT_COMPAT, 'UTF-8') . 
                                "<br>";
                    }
                    // close the file
                    fclose($fileOpen);
                ?>
            </fieldset>
        </div>
    </body>
</html>