
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>אקדמיית הצב - למד תכנות לוגו היישר מתוך הדפדפן</title>
        <script type='application/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js'></script> 
        <script type='application/javascript' src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js'></script>         
        <script type="application/javascript" src="files/compat.js"></script> <!-- ECMAScript 5 Functions -->
        <script type="application/javascript" src="files/lang/he.js"></script> <!-- Logo translated -->
        <script type="application/javascript" src="files/logo.js"></script> <!-- Logo interpreter -->
        <script type="application/javascript" src="files/turtle.js"></script> <!-- Canvas turtle -->
        <script type="application/javascript" src="files/jquery.tmpl.js"></script> <!-- jquerytmpl -->
        <script type="application/javascript" src="readMongo.php"></script> <!-- Lessons scripts -->
        <script type="application/javascript" src="files/interface.js"></script> <!-- Interface scripts -->
        <script type="application/javascript" src="files/jqconsole.js"></script> <!-- Console -->
        <script type="application/javascript" src="files/jquery.Storage.js"></script> <!-- Storage -->
        <link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css' type='text/css' media='all'/>
        <link rel='stylesheet' href='./files/interface.css' type='text/css' media='all'/>
        <script type="application/javascript"> <!-- Google Analytics Tracking -->

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-26588530-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </head>
    <body> 
        <header id="title">
            <h1><img src="files/turtles.png" alt="צב במשקפיים">
                אקדמיית הצב</h1>
        </header>
        <div id="main">
            <div id="header" class="menu">
                <div id="progress">
                </div>
            </div>
            <div id="logoer">
                <div id="display">
                    <canvas id="sandbox" width="660" height="350" class="ui-corner-all ui-widget-content" style="position: absolute; z-index: 0;">
                        <span style="color: red; background-color: yellow; font-weight: bold;">הדפדפן שלך אינו תומך בקנבס - מומלץ להשתמש בדפדפן עדכני יותר</span>
                    </canvas>
                    <canvas id="turtle" width="660" height="350" style="position: absolute; z-index: 1;"></canvas>
                </div>

                <div id="console" class="ui-corner-all ui-widget-content"></div>
            </div>

            <div id="accordion">
            </div>
            <div id="lessonnav">
                <button id="nextlesson">לשיעור הבא &larr;</button>
                <button id="prevlesson">לשיעור הקודם &rarr;</button>
            </div>
        </div>
        <footer id="footer">
            &copy; TurtleAcademy, <a id="doc" title="תעוד הפרוייקט" href="doc.html">תעוד הפרוייקט</a>
        </footer>

    </body></html>