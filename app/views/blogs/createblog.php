        <script type="text/javascript" src="<?= __BASEURL__ ?>/js/jquery.richtext.js"></script>
        <link rel="stylesheet" href="<?= __BASEURL__ ?>/js/richtext.min.css">

        <h1>Nieuwe blog</h1>

        <br />

        <?php

        // als er errors zijn ontstaan tijdens de field validatie dan zal $formerrors gevuld zijn met data. Onderstaande zorgt er voor dat de errors worden weergegeven.
        if (isset($formerrors)) {
        ?>
            <div class="formerrors">

                <p><i class="fa-solid fa-circle-exclamation"></i> De onderstaande fouten zijn opgetreden.</p>

                <div class="errorlist">
                    <ul>
                        <?php
                        foreach ($formerrors as $ferror) {
                            foreach ($ferror as $fe) {
                                echo "<li>{$fe}</li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        <?php

        }

        ?>
        <form action="<?= _FULLURI__; ?>" method="post">

            <ul>
                <li><label for="blogtitle">Blog titel</label><input id="blogtitle" name="blog_title" type="text" value="<?= $blog_title; ?>"></li>
                <li>
                    <label>Inhoud</label>
                    <textarea class="content" name="blog_content">

        <?= $blog_content; ?>

            </textarea>
                </li>
                <li><input type="submit" name="submit" value="Opslaan"></li>
            </ul>
        </form>
        </br>
        <script>
            $(document).ready(function() {
                $('.content').richText({

                    // text formatting
                    bold: true,
                    italic: true,
                    underline: true,

                    // text alignment
                    leftAlign: true,
                    centerAlign: true,
                    rightAlign: true,
                    justify: true,

                    // lists
                    ol: true,
                    ul: true,

                    // title
                    heading: true,

                    // fonts
                    fonts: true,
                    fontList: ["Arial",
                        "Arial Black",
                        "Comic Sans MS",
                        "Courier New",
                        "Geneva",
                        "Georgia",
                        "Helvetica",
                        "Impact",
                        "Lucida Console",
                        "Tahoma",
                        "Times New Roman",
                        "Verdana"
                    ],
                    fontColor: true,
                    backgroundColor: true,
                    fontSize: true,

                    // uploads
                    imageUpload: false,
                    fileUpload: false,
                    videoEmbed: false,
                    // link
                    urls: true,

                    // tables
                    table: true,

                    // code
                    removeStyles: false,
                    code: false,

                    // colors
                    colors: [],

                    // dropdowns
                    fileHTML: '',
                    imageHTML: '',

                    // privacy
                    youtubeCookies: false,

                    // preview
                    preview: false,

                    // placeholder
                    placeholder: '',

                    // dev settings
                    useSingleQuotes: false,
                    height: 0,
                    heightPercentage: 0,
                    adaptiveHeight: false,
                    id: "",
                    class: "",
                    useParagraph: false,
                    maxlength: 0,
                    maxlengthIncludeHTML: false,
                    callback: undefined,
                    useTabForNext: false,
                    save: false,
                    saveCallback: undefined,
                    saveOnBlur: 0,
                    undoRedo: true

                });
            });
        </script>