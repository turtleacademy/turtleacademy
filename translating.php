<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title>

        </title>  
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
        <script  type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <script  type="text/javascript" src="ckeditor/adapters/jquery.js"></script>
        <script  type="text/javascript" src="alerts/jquery.alerts.js"></script>
        <script type="application/javascript" src="files/jquery.Storage.js"></script> <!-- Storage -->
        <link rel='stylesheet' href='./files/lessons.css' type='text/css' media='all'/>
        <link rel='stylesheet' href='./alerts/jquery.alerts.css' type='text/css' media='all'/>
        <script type="text/javascript">
            $.extend({
                getUrlVars: function(){
                    var vars = [], hash;
                    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                    for(var i = 0; i < hashes.length; i++)
                    {
                        hash = hashes[i].split('=');
                        vars.push(hash[0]);
                        vars[hash[0]] = hash[1];
                    }
                    return vars;
                },
                getUrlVar: function(name){
                    return $.getUrlVars()[name];
                }
            });
            
            function loadCKEditor()
            {
                //$( 'textarea' ).ckeditor( function() { /* callback code */ }, { skin : 'kama' , toolbar : [ [ 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike','-','Link', '-', 'MyButton' ] ] });
                var lang = $.getUrlVar('l');
                if (lang == null || lang.length < 2)
                {
                    lang = "en_US";
                }
                $( 'textarea.expTxtErea' ).ckeditor( function() { /* callback code */ }, { language : lang.value , contentsLangDirection : 'ltr' /*, skin : 'office2003' */});       
   
            }
            
            var showFirstStepIfExist = function showFirstStepIfExist()
            {
                if ($.Storage.get('lessonStepsValues'))
                {   
                    
                    if ($.Storage.get('active-step'))
                    {
                        var allsteps = JSON.parse($.Storage.get('lessonStepsValues'));
                        var currentSteps = allsteps[1];
                        $('#title').val(currentSteps[0]);
                        $('#action').val(currentSteps[1]);
                        $('#solution').val(currentSteps[2]);
                        $('#hint').val(currentSteps[3]);
                        $('#explanation').val(currentSteps[4]);  
                    }
                }
            };
            
            function getStepValues()
            {
                var stepTitle = $('#title').val();
                var stepAction = $('#action').val();
                var stepSolution = $('#solution').val();
                var stepHint = $('#hint').val();
                var stepExplanation = $('#explanation').val();   
                    
                var fullStep = new Array();
                    
                fullStep[0] = stepTitle;
                fullStep[1] = stepAction;
                fullStep[2] = stepSolution;
                fullStep[3] = stepHint;
                fullStep[4] = stepExplanation;
                    
                return fullStep;
            }
            
            //Removing a specific step from the steps array
            function removestep(stepNumber)
            {
      
                jConfirm('Are you sure you want to delete step number ' + stepNumber , 'Confirmation Dialog', function(r) {
                    jAlert('Confirmed: ' + r, 'Confirmation Results');
                    if (r)
                    {
                        var allSteps = JSON.parse($.Storage.get("lessonStepsValues"));
                        allSteps.splice(stepNumber ,1);
                        $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2));
                        var val = parseInt($.Storage.get("lesson-total-number-of-steps")) - 1;
                        $.Storage.set("lesson-total-number-of-steps" , val.toString());
                        var val = parseInt($.Storage.get("active-step-num")) - 1;
                        if (!val == 0)
                            {
                                $.Storage.set("active-step-num" , val.toString());
                            }
                        createStepNavVar(); 
                        populateInputsWithCurrentStep();
                        //Saving new data
                        window.saveLessonData();
                    }
                }); 
                
            }
            function populateInputsWithCurrentStep()
            {
                    var allSteps = JSON.parse($.Storage.get("lessonStepsValues"));
                    var currentSteps = allSteps[$.Storage.get('active-step-num')];
                    $('#title').val(currentSteps[0]);
                    $('#action').val(currentSteps[1]);
                    $('#solution').val(currentSteps[2]);
                    $('#hint').val(currentSteps[3]);
                    $('#explanation').val(currentSteps[4]);
            }
            //Remove a full lesson
            function removelesson()
            {
      
                jConfirm('Are you sure you want to delete step number ' + 1 , 'Confirmation Dialog', function(r) {
                    jAlert('Confirmed: ' + r, 'Confirmation Results');
                    if (r)
                    {
                        alert('hello');    
                    }
                }); 
                
            }
            var infoElementKeyUpEvent = function infoElementKeyUpEvent()
                {
                    if ($.Storage.get("lesson-total-number-of-steps") == 1)
                    {
                        
                        window.initializeSteps();
                        var firstStep = getStepValues();
                        addStepVar(1,firstStep,true);//Making replace
                    }
                    
                    if ($.Storage.get("active-step-num"))
                    {
                        var fullStep =  getStepValues();    
                        var allSteps;
                        if ($.Storage.get("lessonStepsValues"))  
                        {
                            allSteps = JSON.parse($.Storage.get("lessonStepsValues"));     
                        }
                        if ($.Storage.get('active-step-num'))
                        {
                            allSteps.splice(parseInt($.Storage.get("active-step-num")),1,fullStep);              
                            $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2))       
                        } else {
                            allSteps[0] =  fullStep;  
                            $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2))   
                        }
                    } 
                }
            var clearLocalStorage = function clearLocalStorage()
            {
                var lessonid = $.getUrlVar('lesson');
                if (lessonid == null || lessonid.length < 3)
                {
                    $.Storage.remove("lessonStepsValues");
                    $.Storage.remove("lesson-total-number-of-steps");
                    $.Storage.remove("active-step-num");
                    $.Storage.remove("active-step");
                    $.Storage.remove("ObjId");
                    $.Storage.remove("lessonTitle");
                    $.Storage.remove("locale");
                }


     
            }
            
            var saveLessonData = function saveLessonData()
                {
                    window.infoElementKeyUpEvent();
                    $.ajax({
                        type : 'POST',
                        url : 'saveLessonData.php',
                        dataType : 'json',
                        data: {
                            steps : $.Storage.get('lessonStepsValues') ,
                            numOfSteps : $.Storage.get('lesson-total-number-of-steps') ,
                            lessonTitle : $.Storage.get('lessonTitle'),
                            ObjId : $.Storage.get('ObjId'),
                            locale : $.Storage.get('locale')
                            
                            //steps : $.Storage.get('lessonStepsValues')
                        },
                        
                        success : function(data){
                            $('#waiting').hide(500);
                            $('#lessonObjectId').val(data.objID.$id);
                            $.Storage.set("ObjId" , data.objID.$id);
                               
                            $('#message').removeClass().addClass((data.error === true) ? 'error' : 'success').text(data.msg).show(500);
                            //  if (data.error === true)
                            //      $('#demoForm').show(500);
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#waiting').hide(500);
                            $('#message').removeClass().addClass('error')
                            .text('There was an error.').show(500);
                        }
                    });
                    return false;
                }
            
            //Adding new full step the the steps array
            //Replace flag will be set to true while replacing
            var addStepVar = function addStep(stepPosition , stepArray , replace)
            {
                window.initializeSteps();
                var allSteps = JSON.parse($.Storage.get("lessonStepsValues"));
                if (replace === true )
                {
                    allSteps.splice (stepPosition , 1 , stepArray);      
                }
                else
                {
                    allSteps.splice (stepPosition , 0 , stepArray); 
                }
                    
                $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2)) 
            };
            /**
             * Will use the get Variable lessons with his locale
             * will use ajax request to load lesson data
             * in order to get the lesson id and his locale
             * than will load the existing step
             */
            function loadExistingLessonSteps()
            {
                var lessonid = $.getUrlVar('lesson');
                var locale = $.getUrlVar('l');
                if (locale != null && locale.length > 2)
                {
                    $.Storage.set("locale" , locale);
                }
                if (lessonid != null && lessonid.length > 2)
                {
                    $.Storage.set("ObjId" , lessonid);
                }
                if (locale != null && locale.length > 2 && lessonid != null && lessonid.length > 2)
                    {
                        $.ajax({
                            url: 'files/loadLessonSteps.php?lesson=' + lessonid + '&l=' + locale,
                            success: function(data) {
                                var rdata;
                                rdata = JSON.parse(data);
                                $.Storage.set("lessonTitle" , rdata.title);
                                $('#lessonTitle').val(rdata.title);
                                $('.result').html(data);
                                //alert('Load was performed.');
                                var i = 1;
                            } ,
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert('en error occured');
                                }

                        });
                    }
                
            }
            
            var clearStep = function clearStep()
            {
                $('#title').val("");
                $('#action').val("");
                $('#solution').val("");
                $('#hint').val("");
                $('#explanation').val("");
            }

            /**
             * Creating the step navigator
             * each step containing the title , hint , solution explanation and etc 
             */
            var createStepNavVar = function createStepNav()
            {
                var lessonStepValuesStorage = new Array(new Array());
                if ($.Storage.get("lesson-total-number-of-steps"))
                {
                    //var currentNumOfLessonStep = $('.existing_step').length;
                    $('.existing_step').remove();
                    var numOfLessonSteps = $.Storage.get("lesson-total-number-of-steps");
                    var liElements = "";
                    for (i=1;i<=numOfLessonSteps;i++)
                    {
                        var id = 'lesson_step' + i ;
                        liElements += '<li class="existing_step" id='+ id + ">" + i + "</li>";
                    }
                    $("#lessonStepUl" ).append(liElements);
                      
                }
                else
                {
                    $("#lessonStepUl" ).append('<li class="existing_step"> 1 </li>');  
                    $.Storage.set('lesson-total-number-of-steps' , '1');
                    $.Storage.set('lessonStepsValues',JSON.stringify(lessonStepValuesStorage, null, 2))
                }
            };
            
            var initializeSteps = function initializeSteps()
            {
                if (! $.Storage.get('lessonStepsValues'))
                {
                    var lessonStepValuesStorage = new Array(new Array());
                    $.Storage.set('lessonStepsValues',JSON.stringify(lessonStepValuesStorage, null, 2))
                }
            }

            $(document).ready(function() {
                window.clearLocalStorage();
                loadExistingLessonSteps();
                loadCKEditor();
                createStepNavVar();
                showFirstStepIfExist();
                
                if (!$.Storage.get("ObjId"))
                {
                    $.Storage.set("ObjId" , "");
                }
                if (!$.Storage.get("locale")) //Setting default locale to en_US
                {
                    $.Storage.set("locale" , "en_US"); 
                    
                }
                if (!$.Storage.get("active-step-num"))
                {
                    $.Storage.set('active-step-num' , '1');    
                }
                $('#addStep').click(function () {
                    var val = parseInt($.Storage.get("lesson-total-number-of-steps")) + 1;
                    $.Storage.set("lesson-total-number-of-steps" , val.toString());
                    $.Storage.set('active-step-num' , val.toString());  
                    addStepVar(val , new Array() , false);
                    clearStep();
                    createStepNavVar();
                });
                $('#removeStep').click(function () {
                    if (parseInt($.Storage.get("lesson-total-number-of-steps")) > 1)
                    {
                        if ($.Storage.get('active-step-num'))
                        {
                            removestep($.Storage.get('active-step-num'));
                        }
                    }

                });
                function saveLessonData()
                {
                    window.infoElementKeyUpEvent();
                    $.ajax({
                        type : 'POST',
                        url : 'saveLessonData.php',
                        dataType : 'json',
                        data: {
                            steps : $.Storage.get('lessonStepsValues') ,
                            numOfSteps : $.Storage.get('lesson-total-number-of-steps') ,
                            lessonTitle : $.Storage.get('lessonTitle'),
                            ObjId : $.Storage.get('ObjId'),
                            locale : $.Storage.get('locale')                            
                        },
                        
                        success : function(data){
                            $('#waiting').hide(500);
                            $('#lessonObjectId').val(data.objID.$id);
                            $.Storage.set("ObjId" , data.objID.$id);
                               
                            $('#message').removeClass().addClass((data.error === true) ? 'error' : 'success').text(data.msg).show(500);
                            //  if (data.error === true)
                            //      $('#demoForm').show(500);
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#waiting').hide(500);
                            $('#message').removeClass().addClass('error')
                            .text('There was an error.').show(500);
                        }
                    });
                    return false;
                }
                
                function infoElementKeyUpEvent()
                {
                    if ($.Storage.get("lesson-total-number-of-steps") == 1)
                    {
                        
                        window.initializeSteps();
                        var firstStep = getStepValues();
                        addStepVar(1,firstStep,true);//Making replace
                    }
                    
                    if ($.Storage.get("active-step-num"))
                    {
                        var fullStep =  getStepValues();    
                        var allSteps;
                        if ($.Storage.get("lessonStepsValues"))  
                        {
                            allSteps = JSON.parse($.Storage.get("lessonStepsValues"));     
                        }
                        if ($.Storage.get('active-step-num'))
                        {
                            allSteps.splice(parseInt($.Storage.get("active-step-num")),1,fullStep);              
                            $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2))       
                        } else {
                            allSteps[0] =  fullStep;  
                            $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2))   
                        }
                    } 
                }
                
                $('.lessonInfoElement').live("keyup" , function() {
                    window.infoElementKeyUpEvent();
                });
                $('.cke_editor_explanation').live("keyup" , function() {
                    window.infoElementKeyUpEvent();
                });
                
                $('#lessonTitle').keyup(function() {       
                    var lessonTitle = $('#lessonTitle').val();           
                    $.Storage.set('lessonTitle' , lessonTitle);
                });
                
                
                //While clicking on other step .. saving step info
                $('.existing_step').live("click" , function() {
                    var fullStep =  getStepValues();         
                    window.clearStep();
                    var allSteps;
                    if ($.Storage.get("lessonStepsValues"))
                    
                    {
                        allSteps = JSON.parse($.Storage.get("lessonStepsValues"));     
                    }
                    else
                    {
                        allSteps = new Array();     
                    }
                    
                    if ($.Storage.get("active-step"))
                    {
                        //$.Storage.set($.Storage.get("active-step"), JSON.stringify(fullStep, null, 2));     
                        var name = '#' + $.Storage.get("active-step");
                        $(name).css('background-color' , 'white')
                    }

                    if ($.Storage.get('active-step-num'))
                    {
                        allSteps.splice(parseInt($.Storage.get("active-step-num")),1,fullStep);              
                        $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2))       
                    } else { //Case of first step
                        allSteps[0] =  fullStep;  
                        $.Storage.set('lessonStepsValues',JSON.stringify(allSteps, null, 2))   
                    }
                        
                    var pressed = $(this).attr('id');
                    $(this).css('background-color' , '#AAA');
                    $.Storage.set('active-step' , pressed);
                    $.Storage.set('active-step-num' , pressed.substring(11));
                    populateInputsWithCurrentStep();    
            
                });
                
                $('#btnDeleteLesson').click(function() {
                    //removelesson();
                    //need to remove from DB
                    jConfirm('Are you sure you want to delete the whole lesson '  , 'Confirmation Dialog', function(r) {
                    jAlert('Your lessson has been deleted' + r, 'confirm delete');
                    if (r)
                    {
                            $.ajax({
                                type : 'POST',
                                url : 'deleteLessonData.php',
                                dataType : 'json',
                                data: {
                                    ObjId : $.Storage.get('ObjId')
                                },

                                success : function(data){
                                   window.clearLocalStorage();
                                   window.clearStep();
                                   var url = document.URL ;
                                   var myUrl = url.split("?");
                                   alert('successfully deleted');
                                   window.location.replace(myUrl[0]);
                                },
                                error : function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert('en error occured');
                                }
                            });
                    }
                     }); 
                });     
                
                $('#btnSaveLesson').click(function() {           
                    window.saveLessonData();
                });
                
                $('#btnDel').attr('disabled','disabled');
            });
            
        </script>
    </head>
    <body>
        <header id="titleHeader">
            <h1><img src="files/turtles.png" alt="צב במשקפיים">
            <?php
                 echo _("Turtle Academy - Translate a lesson");
            //        אקדמיית הצב                    
             ?> 
            </h1>
        </header>
        <?php
        //session_start();
        require_once ("files/lessonsUtil.php");
        $m = new Mongo();
// select a database
        $db = $m->turtleTestDb;
// select a collection (analogous to a relational database's table)
        $lessons = $db->lessons;
        $locale = "en_US";
        $languageGet = "l";
        $localePrefix = "locale_";
        $lessonFinalTitle = "";
        if (isset($_GET[$languageGet]))
            $locale = $_GET[$languageGet];

//If we are in existing lesson we will enter editing mode 
        if (isset($_GET['lesson'])) {
            $lu = new lessonsUtil($locale, "locale_", $lessons, $_GET['lesson']);
            $theObjId = new MongoId($_GET['lesson']);
            $cursor = $lessons->findOne(array("_id" => $theObjId));
            $localSteps = $lu->getStepsByLocale($localePrefix . $locale);
            $lessonFinalTitle = $lu->getTitleByLocale($localePrefix . $locale);
        }
        

        function printElement($i, $flag, $step , $istranslate) {
            if ($flag) {
                $action = $step["action"];
                $solution = $step["solution"];
                $hint = $step["hint"];
            } else {
                $action = "";
                $solution = "";
                $hint = "";
            }
            $disabled = '';
            if (!$istranslate)
                $disabled = "disabled='disabled'";
      
                
            $baseInputText = "<div> <label class='lessonlables'> %%a: </label> <textarea class='lessonInfoElement'  $disabled type='text'  name='%%b' id='%%b' placeholder='Step %%a'>%%c</textarea> </div>";

            $toReplace = array("%%a", "%%b", "%%c");
            if (!$istranslate) {
                $replaceWithAction = array("Action ", "action", $action);
                $replaceWithSolution = array("Solution ", "solution", $solution);
                $replaceWithHint = array("Hint ", "hint", $hint);
            } else {
                $replaceWithAction = array("Action ", "action1", $action);
                $replaceWithSolution = array("Solution ", "solution1", $solution);
                $replaceWithHint = array("Hint ", "hint1", $hint); 
            }
            $elementAction = str_replace($toReplace, $replaceWithAction, $baseInputText);
            $elementSolution = str_replace($toReplace, $replaceWithSolution, $baseInputText);
            $elementHint = str_replace($toReplace, $replaceWithHint, $baseInputText);
            echo $elementAction;
            echo $elementSolution;
            echo $elementHint;
        }
        ?>
            <?php
            $i = 1; //Set default value to 1 in case there are no steps
            if (isset($cursor["steps"]) && count($cursor["steps"]) > 0) {
                $i = 0;
                //Init the loacl Storage
                ?>
                <script type='text/javascript'>
                    $.Storage.remove("lessonStepsValues");
                    $.Storage.remove("active-step-num");
                    $.Storage.remove("lesson-total-number-of-steps");
                    $.Storage.remove("active-step");
                    var lessonStepValuesStorage = new Array(new Array());
                    $.Storage.set('lessonStepsValues',JSON.stringify(lessonStepValuesStorage, null, 2))
                    $.Storage.set("active-step" , "1");
                    $.Storage.set("lesson-total-number-of-steps" ,"0");
                </script>
                <?php
                foreach ($localSteps as $step) {
                    $i++;
                    ?>
                    <script type='text/javascript'>
                        //Here I am parsing the response from the ajax request regarding loading an existing lesson
                        var stepNumber = parseInt($.Storage.get("lesson-total-number-of-steps")) + 1;
                        $.Storage.set("lesson-total-number-of-steps" , stepNumber.toString());
                                 
                        var stepExplanation = <?php echo json_encode($step["explanation"]); ?>;
                        var stepTitle= <?php echo json_encode($step["title"]); ?>;
                        var stepAction = <?php echo json_encode($step["action"]); ?>;
                        var stepSolution = <?php echo json_encode($step["solution"]); ?>;
                        var stepHint = <?php echo json_encode($step["hint"]); ?>;
                                 
                        $.Storage.set("lesson-total-number-of-steps" , stepNumber.toString());
                                                     
                        var fullStep = new Array();        
                        fullStep[0] = stepTitle;
                        fullStep[1] = stepAction;
                        fullStep[2] = stepSolution;
                        fullStep[3] = stepHint;
                        fullStep[4] = stepExplanation;                  
                        //adding the step
                        window.addStepVar(stepNumber , fullStep , false);         
                               
                    </script>
                    <?php
                } //End of for each loop
                ?>  
                <div id="stepSection" style="margin-bottom:4px;" class="stepsSection">
                    <div>
                        
                            <lable class="lessonHeader"> Lesson Title : </lable> 
                            <input type="text" name="lessonTitle"  id="lessonTitle" class="lessonInput" placeholder="Lesson Title"/>
                            <! Object ID: --!> 
                            <input type="text" name="ObjId" style="display:none" id="lessonObjectId" class="lessonInput" value="<?php
                                if (isset($cursor["_id"]))
                                    echo $cursor["_id"]; else {
                                    echo "";
                                }
                                ?>"/>
                            
                    </div>   
                    </br>
                    <?php
                    echo "<div id='lessonStep'>";
                    // echo "<lable id='lessonStepLabel'> Lesson Step Title </lable>";
                    echo "<div id='stepNev'>";
                    echo "<lable class='lessonHeader'>lesson Steps </lable>";
                    echo "<ul id='lessonStepUl'>";
                    echo "</ul>";
                    //Inserting the step div
                    echo "<div>";

                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    ?>
                    <div class="leftLessonElem"> 
                        <lable class='lessonlables' > Title :  </lable> 
                        <textarea type="text" disabled='disabled'  name="title" id="title" placeholder="Step Title" class="lessonInfoElement" >
                        </textarea>
                        <?php
                            printElement($i, false, null ,false);
                        ?>
                        <lable class='lessonlables' > Explanation </lable> 
                        </br>
                        <textarea type="text"  name="explanation" id="explanation" class="expTxtErea"></textarea>
                    </div>
                    
                    <div class="rightLessonElem"> 
                        <!-- here I should do something equal to left for translating -->
                        <lable class='lessonlables' > Title :  </lable> 
                        <textarea type="text"  name="title1" id="title1" placeholder="Step Title" class="lessonInfoElement" >
                        </textarea>
                        <?php
                            printElement($i, false, null , true);
                        ?>
                        <lable class='lessonlables' > Explanation </lable> 
                        </br>
                        <textarea type="text"  name="explanation1" id="explanation1" class="expTxtErea"></textarea>

                    </div>     
                
                     <div>
                        <input type="button" id="btnSaveLesson" class="lessonInputButton" name="formSave" value="Save" />
                    </div>
                </div> 
                <script type='text/javascript'>
                                                
                    //Print Nav  
                    window.createStepNavVar();
                        
                    window.showFirstStepIfExist();
                    
                </script>
                <?php
            } //end of if
            else {
                ?>
                <div id="stepSection" style="margin-bottom:4px;" class="stepsSection">
                    <div>
                           
                            <lable class="lessonHeader"> Lesson Title : </lable>
                           
                       
                        
                            <input type="text" name="lessonTitle"  id="lessonTitle" class="lessonInput" placeholder="Lesson Title"/>
                            <! Object ID: --!> 
                            <input type="text" name="ObjId" style="display:none" id="lessonObjectId" class="lessonInput" value="<?php
                                if (isset($cursor["_id"]))
                                    echo $cursor["_id"]; else {
                                    echo "";
                                }
                                ?>"/>
                            
                        
                    </div>  
                    </br>
                    
                    <?php
                    echo "<div id='lessonStep'>";
                    //    echo "<lable id='lessonStepLabel'> Lesson Step Title </lable>";
                    echo "<div id='stepNev'>";
                    echo "<lable class='lessonHeader'>lesson Steps </lable>";
                    echo "<ul id='lessonStepUl'>";
                    echo "</ul>";
                    echo "</div>";
                    //Inserting the step div

                    echo "</div>";
                    ?>
                    <div class="leftLessonElem"> 
                        <lable class='lessonlables' > Title :  </lable> 
                        <textarea type="text"  name="title" id="title" placeholder="Step Title" class="lessonInfoElement">
                        </textarea>
                        <?php
                        printElement($i, false, null , true);
                        ?>
                    </div> 
                    <div class="rightLessonElem">
                        <lable class='lessonlables' > Explanation : </lable>
                        </br>
                        <textarea type="text"  name="explanation" id="explanation" class="expTxtErea"></textarea>
                    </div>     
                    <div>
                        <input type="button" id="btnSaveLesson" class="lessonInputButton" name="formSave" value="Save" />
                        <input type="button" id="btnDeleteLesson" class="lessonInputButton" name="formDelete" value="Delete Lesson" />
                    </div>
                   
                </div>     
                <?php
            } //end of else
            ?>
        <div id="message" style="display: none;">
            <div id="waiting" style="display: none;">
                Please wait<br />
            </div>
    </body>
</html>