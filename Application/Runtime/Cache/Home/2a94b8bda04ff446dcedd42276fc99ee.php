<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html ng-app="tlApp">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <link rel="stylesheet" href="/newsVis/Application/Home/Common/css/style.css">
    <link rel="icon" type="image/x-icon" href="/newsVis/Application/Home/Common/img/favicon.ico" />


    <script src="/newsVis/Application/Home/Common/js/lib/jquery-latest.min.js"></script>
    <script src="/newsVis/Application/Home/Common/js/lib/d3.min.js" charset="utf-8"></script>
    <script src="/newsVis/Application/Home/Common/js/lib/d3-timeline.js" charset="utf-8"></script>

    <script src="/newsVis/Application/Home/Common/js/lib/angular.js"></script>
    <script src="/newsVis/Application/Home/Common/js/dataWrangling.js"></script>
    <script src="/newsVis/Application/Home/Common/js/domScript.js"></script>
    <script src="/newsVis/Application/Home/Common/js/setupTLels.js"></script>
    <script src="/newsVis/Application/Home/Common/js/downloadFctns.js"></script>
    <script src="/newsVis/Application/Home/Common/js/angularScript.js"></script>

    <script type="text/javascript" src="/newsVis/Application/Home/Common/js/lib/jszip.min.js"></script>

    <title>时间轴可视化系统</title>

</head>


<body ng-controller="MainCtrl" >
    
<div id='helpBox'>
    <h1>Steps:</h1>
    <p>1.拷贝你的新闻内容至Document View内，并提交 
    <p>2.点击Control Panel中的加号按钮，提供一个初始为空的事件供你写入
    <p>3.根据Document View内的新闻，逐一向Control Panel的空事件写入事件，并提交
    <p>4.点击Control Panel中的向下按钮，下次此次你写入的所有事件文件，以备下次使用
    <p>5.若需重新加载新的新闻，点击Control Panel中的文本按钮
    <p>6.若需载入已经下载的事件文件，点击Control Panel中的向上按钮
    <br>
    <br>
    <br>
    <br>
    <button class='btn' onclick="hideHelp()">已经知道，如需再次查看，请点击右下角</button>
</div>
    <div id="topBox">

    <h1 id="tlc"><b>时间轴可视化系统</b></h1>

        <div id="timeline">

            <p style="text-align:center; margin-top:10%; font-size:1.5em">
            
            </p>
        </div>

    </div>
    <!-- <div id="topBox2">
        tangxiang
    </div> -->
    <div id="descriptionBox">
        <table><tr>
            <td>List View</td>
            <td>Document View</td>
            <td>Control Panel</td>
        </tr></table>
    </div>
    <div id="bottomBox">


        <div id="leftBox" ng-click='addtext'>

         <!-- List Viewer -->
            <div id="tablehead">
                <!--table><tr>
                <td-->
                Sort list by:
                <select
                    ng-model="sortval"
                    ng-change="orderByVal=sortval"
                    ng-options="item.val as item.title for item in sortBy"
                    ng-init="sortval=sortBy[1].val">
                </select>
                <span style="cursor:pointer;" ng-click="orderReverse=!orderReverse">
                <span ng-if="orderReverse">&#9650;</span>
                <span ng-if="!orderReverse">&#9660;</span>
                </span>
            </div>

            <div id="listData">
                <table>
                    <tr id="Listid_{{tx.id}}" ng-repeat="tx in timexes | orderBy: orderByVal:orderReverse" class="listEl" ng-click="clickList(tx)" ng-class="{deleted: tx.deleted}">
                        <td>
                            <span class="bgColor_2 symbol_{{tx.typ}}">
                            <!-- <p ng-if="tx.touched"> &#10002; </p> -->
                            </span>
                        </td>
                        <td>{{ tx.times }}</td>
                        <td>{{ tx.content }}</td>
                    </tr>
                </table>
                <div class="statusBox" style="left:0;"><b>Events: {{timexes.length}}</b>, vague expressions:  0</div>
            </div>
        </div>

        <div id="centerBox">

            <!-- <div id="docSwitcher" >
                <div id="addDoc" title="Add a new document"ng-click= "openInput()" ></div>

                <ul id="docList">
                    <li ng-repeat="file in fileNames track by $index" ng-click="switchView($index)" id="doc{{ $index }}" class="docBtn docBtn_{<?php echo ($index); ?>}" style="background-color : #222" >
                        <div class="doctitle"> {{ file.title }} </div>
                        <div class="doctools">{{ $index+1 }}/{{ fileNames.length }}</div>
                    </li>
                </ul>
            </div> -->

            <div id="docText">
               <!--  <div id="txtData" ng-repeat="doc in fileContent" class="txtData" ng-show = "$index == currentfile" >
                    <span ng-repeat="sent in doc" >
                        <span ng-if="sent.tx=='Tx'" id="timeSent_{{sent.sentNr}}" class="timex" ng-click="clickingSent(sent.sentNr,sent.id)" ng-bind-html="sent.sent" style="font-weight:bold"></span>
                        <span ng-if="sent.tx=='NoTx'" ng-bind-html="sent.sent" ></span>
                    </span>
                </div> -->
                <textarea id='pasteBox' style="width: 100%;height: 400px" autofocus placeholder="You can paste your news here!"></textarea>
            </div>
            <button style="left: 54%;top: 90%;position: absolute;" ng-show='pasteBoxShow' ng-click="paste()">submit</button>
        </div>

        <div id="rightBox">
        <div class="display">
            
        </div>
        <button style="left: 54%;top: 90%;position: absolute;" ng-show='addDateShow' ng-click="submitDate()">submit</button>
        <div id="toolBox" ng-click="editDate=false">


            <!-- Save current state -->

            <div id="pasteShow" class="ctlBtns toolBoxBtn" title="Paste from clipboard" ng-click="pasteShow()"></div>

            <div id="saveState" class="ctlBtns toolBoxBtn" title="Save this state of current events" ng-click='saveData()'></div>
            

            <!-- Import Data-->
            <div id="loadData" class="ctlBtns toolBoxBtn" title="Load in previously saved data" ng-click='loadData()'></div>
                <input id="uploadFile" onchange="angular.element(this).scope().sendPath(this)" type="file" accept="text/plain" style="display: none"/>
                <!-- <input id="saveData" onchange="angular.element(this).scope().saveState(this)" type="file" accept="text/plain" style="display: none"/> -->
            <div id="addDate" class="ctlBtns toolBoxBtn" title="Add a single event" ng-click="addDate()"></div>

            <!-- <div id="submitDate" class="ctlBtns toolBoxBtn" title="Submit a single event" ng-click="submitDate()"></div> -->

            



            <div id="mergeDates" class="ctlBtns toolBoxBtn" ng-if="severalSelected" title="Merge these dates" ng-click="mergeDates()"></div>

            <div id="deleteAll" class="ctlBtns toolBoxBtn" ng-if="severalSelected" title="Delete these dates" ng-click="deleteDate()"></div>

        </div>

        <!-- <input id='pasteBox' type="textarea" ng-show='pasteBoxShow'></input> -->

       <!--  <div id="tooltipbox" class="tt">Show timeline description</div>
            <div ng-show="isAddDate||(dateSelected&&(!timexes[currentid].deleted))" class="display">
                <div style="margin-bottom:10px;">
                    <ng-switch on="editDate" id="displayDate" class="editorfield">
                        <span class="label">Time:</span>
                        <div ng-switch-when="true" ng-model="$parent.dateInfo[0].times" onkeypress='validate(event,this)' unfocus="disableEdit('t')">
                            <input title="day in month" maxlength="2" onkeypress='return validate(event)' size="2" ng-if="dateInfo[0].dateArray[2]!='xx'" ng-model="$parent.dateInfo[0].dateArray[2]" ></input>
                            <span class="addDimension" ng-click="addDay('start')" ng-if="dateInfo[0].dateArray[2]=='xx'" ng-model="$parent.dateInfo[0].dateArray[2]" >day</span>

                            <input title="Month" maxlength="2" onkeypress='return validate(event)' size="2" ng-if="dateInfo[0].dateArray[1]!='xx'" ng-model="$parent.dateInfo[0].dateArray[1]" ></input>
                            <span class="addDimension" ng-click="addMonth('start')" ng-if="dateInfo[0].dateArray[1]=='xx'" ng-model="$parent.dateInfo[0].dateArray[1]" >month</span>

                            <input id="startdateYear" title="Year" maxlength="4" onkeypress='return validate(event)' size="4" ng-if="dateInfo[0].dateArray[0]!='xxxx'" ng-model="$parent.dateInfo[0].dateArray[0]"></input>
                            
                         <span ng-if="dateInfo[0].typ=='duration'">
                            <span> - </span>
                            <input maxlength="2" onkeypress='return validate(event)' size="2" ng-if="dateInfo[0].dateArray[5]!='xx'" ng-model="$parent.dateInfo[0].dateArray[5]" ></input>
                            <span class="addDimension" ng-click="addDay('end')" ng-if="dateInfo[0].dateArray[5]=='xx'" ng-model="$parent.dateInfo[0].dateArray[5]" >day</span>

                            <input maxlength="2" onkeypress='return validate(event)' size="2" ng-if="dateInfo[0].dateArray[4]!='xx'" ng-model="$parent.dateInfo[0].dateArray[4]" ></input>
                            <span class="addDimension" ng-click="addMonth('end')" ng-if="dateInfo[0].dateArray[4]=='xx'" ng-model="$parent.dateInfo[0].dateArray[4]" >month</span>

                            <input maxlength="4" onkeypress='return validate(event)' size="4" ng-if="dateInfo[0].dateArray[3]!='xxxx'" ng-model="$parent.dateInfo[0].dateArray[3]"></input>
                        </span>
                        <button ng-click = "disableEdit('t')">submit</button>
                        </div>

                        <div ng-switch-default>
                        <span class="datetitle ctl" ng-click="enableEdit('t')">{{dateInfo[0].times}}</span>
                    </div>

                    </ng-switch>
                </div>
                <ng-switch on="editTitle" id="displaySubtitle" class="editorfield">
                    </br>
                    <span class="label">Title</span>
                    <div ng-switch-when="true">
                        <button ng-click="disableEdit('d')">submit</button>
                        <input ng-model="$parent.dateInfo[0].subtitle" onkeypress='validate(event,this)'></input>
                    </div>
                    <div ng-switch-default ng-click="enableEdit('d')">
                        <span class='subtitle ctl'>{{ dateInfo[0].subtitle }}</span>
                    </div>
                </ng-switch>
                <ng-switch on="editContent" id="displayContent" class="editorfield">
                    <span class="label">content</span>
                    <div ng-switch-when="true">
                        <button ng-click="disableEdit('c')">submit</button>
                        <input  rows="5" ng-model="$parent.dateInfo[0].sent" onkeypress='validate(event,this)' ></input>
                    </div>
                    <div ng-switch-default ng-click="enableEdit('c')">
                        <span class='content ctl'>{{dateInfo[0].sent}}</span>
                    </div>
                </ng-switch>

                <button style="left: 84%;top: 90%;position: absolute;" ng-show='pasteBoxShow' ng-click="paste()">submit</button>
            </div>
        </div> -->

    </div>

    


<div id="inputOverlay">Or choose from an example file
    <div id="closeInput" onclick="closeInput()"></div>
    <div id="inputContainer">


        <div id="insertText">

            <h1>Get your text by entering the URL</h1>
            <form accept-charset="UTF-8" style="width:100%; height:40px;">
                <!--p style="float:left; padding:0; width:auto;">
                  <span class="btn" onclick="showURL()" style="padding: 5px 10px; margin: 0 10px 0 0;">Fetch from URL</span>
                </p-->
                <div id="url-form">
                    <label for="URL" style="float:left;padding: 5px 10px 0 0;">URL </label>
                    <input type="text" style="float:left; width:255px; margin: 5px 10px 0 0;" name="URL" placeholder="Enter URL of news article, Wikipedia page, ...">
                    <p><span class="btn" ng-click="scrapeURL()" style="padding: 5px 10px; margin: 0;">Scrape!</span></p>
                </div>
            </form>
            <hr>
            <h1 style="margin-top:10px">Or copy & paste your text here</h1>
            <form style="height:100%; margin-top:5px;" accept-charset="UTF-8">
                <label for="title" style="float:left; padding-right:10px;">Title </label>
                <input type="text" style="float:left; width:255px; margin:2px 10px 0 0;" name="title" placeholder="Document Title">

                <!-- Doc Choice -->
                <div id="chooseTrack_0" class="chooseTrack bgColor_0">1</div>
                <div id="chooseTrack_1" class="chooseTrack bgColor_1">2</div>
                <div id="chooseTrack_2" class="chooseTrack bgColor_2">3</div>
                <div id="chooseTrack_3" class="chooseTrack bgColor_3">4</div>
                <div id="chooseTrack_4" class="chooseTrack bgColor_4">5</div>
                <div id="chooseTrack_5" class="chooseTrack bgColor_5">6</div><br>

                <!--label for="content">Content</label><br-->
                <textarea name="content" id="content" placeholder="Drop the content here."></textarea>


                <label class="optional" for="date">Which date does "today" refer to in the document (optional):</label>
                <input id="todayInput" type="date" size="50" name="date">

                <!--label class="optional" for="source">Source</label> <input type="text" size="50" name="source"-->
                <p style="text-align:right; margin:-30px 0px 0 0; width:100%;">
                    <span class="btn" ng-click="getTimexes()" style="padding: 5px 10px;"><b>GO!</b></span>
                </p>
            </form>
        </div>

        <div id="insertFile">
            <p style="margin:18px 0 0 0">Or choose from an example file
                <select
                        ng-model="selectedFile"
                        ng-change="addDocument(selectedFile)"
                        ng-options="file as file for file in tempFiles">
                    <option> </option>
                    <!--option  ng-repeat="file in tempFiles" ng-click="addDocument(file)" value="{{file}}" ng>{{file}}</option-->
                </select>
            </p>
        </div>



        <!--div id="addDoc" class="ctlBtns" title="Add a new document" ng-click="uploadDoc=!uploadDoc"></div>
        <div id="selectBox" ng-if="uploadDoc">
        <ul><li ng-repeat="file in tempFiles" ng-click="addDocument(file)"> {{file}} </li></ul>
        </div-->
    </div>
</div>

<div id="guide"><a onclick="showHelp()">Show Help!</a></div>

</body>

<script type="text/javascript">
    function showHelp(){
        $('#helpBox').fadeIn(500)
    }
    function hideHelp(){
        $('#helpBox').fadeOut(500)
    }

    var app = angular.module('tlApp', []);

    app
    .controller('MainCtrl', function($scope, $http, $sce) {
        //var for centre box
        $scope.openInput=function(){    
            $("#inputOverlay").fadeIn(300);
            $(".chooseTrack").removeClass("chosen")
            $("#chooseTrack_"+(trackNr)).addClass("chosen")
            var today = getToday()
            $("#todayInput").val(today)
            if(!openedInput){
              $(".chooseTrack").on( "click" , function(){
                $(".chooseTrack").removeClass("chosen")
                // CONTINUE HERE
                trackNr = $(this).attr("id").split("_")[1]
                $("#"+$(this).attr("id")).addClass("chosen")
              })
              openedInput = true;
            }
            $(document).on("keydown" , exitOverlay )
        }
        $scope.docNr = -1;
        $scope.fileNames = [
            {title : "China, ASEAN expected to make fortune together" , trackNr : "1"},
            {title : "China's flight punctuality rate rises in October" , trackNr : "2" }
        ];
        $scope.uploadFile='';
        $scope.currentfile = -1;
        $scope.currentsent = -1;
        $scope.currentid = -1;
        $scope.fileContent=[
            [{ sent : $sce.trustAsHtml("\"Eighteen\" is considered a lucky number in Chinese tradition - a good sign that the China-ASEAN ties will make a big stride toward a broader space for development, "), tx : "NoTx" , sentNr : 0  },
                { sent : $sce.trustAsHtml("Chinese Premier Li Keqiang said here Saturday at the 18th China-ASEAN (10+1) leaders\' meeting."), tx : "Tx" , sentNr : 1 , id :0  },
                { sent :$sce.trustAsHtml("In Chinese, \"18\" is pronounced similar to \"make a fortune for sure.\" To make a fortune together with ASEAN, Li pledged the 10-member bloc infrastructure loans totaling 10 billion US dollars, and proposed railway and production capacity cooperation amid closer partnership with the economically converging region.He proposed acceleration of economic integration of East Asia.") , tx : "NoTx" , sentNr :2  },
                { sent :$sce.trustAsHtml(" China is ready to join efforts with relevant parties to conclude negotiations of the RCEP in 2016, he noted.") , tx : "Tx" , sentNr : 3 , id : 1 },
                { sent :$sce.trustAsHtml(" Relevant parties should raise the level of inter-connectivity, Li said,  noting that China has been accelerating the construction of a maritime cooperation platform in East Asia so as to boost cooperation in maritime inter-connectivity, research and personnel training."), tx : "NoTx" , sentNr : 4  },
                { sent : $sce.trustAsHtml("Meanwhile, Li urged the parties to carry out international production capacity cooperation and deepen cooperation in agriculture and poverty mitigation."), tx : "NoTx" , sentNr :5  }
                //{sent: $sce.trustAsHtml(), tx:"Tx",sentNr:,id:}


            ],
            [ { sent :$sce.trustAsHtml("BEIJING - China's flight punctuality rate rose more than 10 percentage  oints year on year in October,") , tx : "Tx" , sentNr : 5 , id :  2},
                { sent :$sce.trustAsHtml( " the civil aviation authority said Saturday."), tx : "Tx" , sentNr :6 , id : 3 },
                { sent : $sce.trustAsHtml("Last month, the average punctuality ratio of domestic airlines stood at 81.94 percent, up by 11.04 percentage points from a year earlier, data from the Civil Aviation Administration of China (CAAC) showed."), tx : "Tx" , sentNr :7  , id :4  },
                { sent : $sce.trustAsHtml("The flight punctuality ratio for major Chinese airports was up by 10.72 percentage points to 83.28 percent."), tx : "NoTx" , sentNr : 8  },
                { sent : $sce.trustAsHtml("Air China, Shandong Airlines and Sichuan Airlines had the highest punctuality rates last month, the data showed."), tx : "Tx" , sentNr :9  , id : 5 },
                { sent : $sce.trustAsHtml("The CAAC has moved to raise the punctuality ratio of airlines and airports."), tx : "NoTx" , sentNr : 10 },
                { sent : $sce.trustAsHtml("Last month, the authority penalized three airports with"), tx : "Tx" , sentNr : 11 , id : 6 },
                { sent : $sce.trustAsHtml(" lowest punctuality ratios in September "), tx : "Tx" , sentNr : 12 , id : 7 },
                { sent : $sce.trustAsHtml("by not granting any new routes or flights in November."), tx : "NoTx" , sentNr : 13 },
            ]
        ];

        $scope.isAddDate=false


        $scope.saveState=function() {
            var xhr = new XMLHttpRequest();
             
            xhr.open("POST", "Home/Index/save", true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhr.onreadystatechange = function(){
                var XMLHttpReq = xhr;
                if (XMLHttpReq.readyState == 4) {
                    if (XMLHttpReq.status == 200) {
                        var text = XMLHttpReq.responseText;
                        window.open(text)
                    }
                }
            };
            // console.log($scope.timexes)
            xhr.send('data='+JSON.stringify($scope.timexes));
        }

        $scope.loadData=function(){
            $('#uploadFile').click();
        }

        $scope.saveData=function(){
            $scope.saveState()
        }

        $scope.updateList=function(){
            $('#leftBox').click()
        }


        $scope.sendPath=function(path){
            $scope.pasteShow();
            $scope.timexes=[]
            var readPath=path.value;
            readPath=readPath.slice(readPath.lastIndexOf('\\')+1);
            var xhr = new XMLHttpRequest();
             
            xhr.open("POST", "Home/Index/readPath", true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhr.onreadystatechange = function(){
                var XMLHttpReq = xhr;
                if (XMLHttpReq.readyState == 4) {
                    if (XMLHttpReq.status == 200) {
                        var text = XMLHttpReq.responseText;
                        $scope.timexes=JSON.parse(eval(text));
                        console.log($scope.timexes)
                        // console.log(JSON.parse(text))
                        $scope.updateList();
                        $scope.updateD3('newDoc',$scope.timexes);
                    }
                }
            };

            // console.log($scope.timexes)
            xhr.send('data='+readPath);
            $('#leftBox').innerHTML= $('#leftBox').innerHTML
        }
        //var for left box
        $scope.sortBy = [
            { title : "Order By id" , val : "id" },
            { title : "Order By time" , val : "time" },
            { title : "Order By content" , val : "content"},
        ];
        
        $scope.orderByVal='time';
        $scope.orderReverse=false;
        $scope.dateInfo = [];
        // $scope.timexes=[];
        $scope.timexes=[]
        // $scope.timexes = [
        //     { times:'2015-10-10',content:'Chinese Premier Li Keqiang said',id:0,typ:'date',touched:true,deleted:false,textId:0,sentId:1,sent:"Chinese Premier Li Keqiang said here Saturday at the 18th China-ASEAN (10+1) leaders\' meeting.",area:'36.0992900000,118.5276630000'},
        //     { times:"2016-01-01",content:'He proposed acceleration of economic',id:1,typ:'date',touched:true,deleted:true,textId:0,sentId:3,sent:"China is ready to join efforts with relevant parties to conclude negotiations of the RCEP in 2016, he noted.",area:'36.0992900000,118.5276630000'},
        //     { times:'2015-10-01',content:"BEIJING - China's flight punctuality",id:2,typ:'date',touched:true,deleted:false,textId:1,sentId:5,sent:"BEIJING - China's flight punctuality rate rose more than 10 percentage  oints year on year in October,",area:'36.0992900000,118.5276630000'},
        //     { times:'2015-10-10',content:' the civil aviation authority said Saturday',id:3,typ:'date',touched:false,deleted:false,textId:1,sentId:6,sent:"the civil aviation authority said Saturday.",area:'36.0992900000,118.5276630000'},
        //     { times:'2015-09-01,2015-09-30',content:'Last month, the average punctuality',id:4,typ:'duration',touched:false,deleted:false,textId:1,sentId:7,sent:"Last month, the average punctuality ratio of domestic airlines stood at 81.94 percent, up by 11.04 percentage points from a year earlier, data from the Civil Aviation Administration of China (CAAC) showed.",area:'36.0992900000,118.5276630000'},
        //     { times:'2005-09-01,2015-09-30',content:'Air China, Shandong Airlines and',id:5,typ:'duration',touched:false,deleted:false,textId:1,sentId:9,sent:"Air China, Shandong Airlines and Sichuan Airlines had the highest punctuality rates last month, the data showed.",area:'36.0992900000,118.5276630000'},
        //     { times:'2015-09-01,2015-09-30',content:'Last month, the authority penalized',id:6,typ:'duration',touched:false,deleted:false,textId:1,sentId:11,sent:"Last month, the authority penalized three airports with",area:'36.0992900000,118.5276630000'},
        //     { times:'2015-09-01,2015-09-30',content:'lowest punctuality ratios in September',id:7,typ:'duration',touched:false,deleted:false,textId:1,sentId:12,sent:"lowest punctuality ratios in September ",area:'36.0992900000,118.5276630000'}
        // ];
    
        //var for right box
        $scope.editDate=false;
        $scope.editTitle=false;
        $scope.editContent=false;

        //drawing time line
        $scope.itemHeight=20;
        
        $scope.openMap=function(){
            $("#inputOverlay").fadeIn(300);
        }

        var chart = d3.timeline();
            chart
            .itemHeight(itemHeight)
            .margin({ left: puffer/2, right:puffer*2, top: $("#topBox").height()-80, bottom:puffer })
            .tickFormat({ tickTime: d3.time.years, tickInterval: 5, tickSize: 11 })
            .click(function (d, i, datum) { $scope.clickingCircle(datum) })
            

            var myTl = d3.select("#timeline").html("").append("svg")
                    .attr("width", $("#topBox").width() - 20)
                    .attr("height", $("#topBox").height() - 20)
                    .attr("fill" , "none")
            
            myTl.append("g").attr("class", "ref") // Add group for reference lines
            myTl.datum($scope.timexes).call(chart)
            $scope.scaleFactor = scaleFactor;       
            $scope.chart = chart;
            $scope.pasteBoxShow=true
            $scope.addDateShow=false

            var repeat=[];
            var d=updateTime($scope.timexes)
            var action="newDoc"
            var minTime = 1420070400000;  // 2015
            var maxTime = -62135596800000; // year 1
            minTime=getMinTime($scope.timexes);
            maxTime=getMaxTime($scope.timexes);
            var beginning = minTime;
            var ending = maxTime;

            // If only one date on TL, readjust beginning and ending
            if(beginning == ending){
                beginning = beginning - 157784630000
                ending = ending + 157784630000
            }
            
            var width = $("#topBox").width();
            
            var xScale = d3.time.scale()
                    .domain([beginning, ending])
                    .range([puffer/2, width - puffer*2]);   
            
              var xAxis = d3.svg.axis().scale(xScale).ticks(15).tickSize(15)
            
              // READJUSTING PATHS
              if(beginning == 0) beginning = 1
              if(ending == 0) ending = 1

              scaleFactor = (1/(ending - beginning)) * (width - (puffer*2.5));
            var x = $("#timeline").width();
                    var timexElements = d3.select("svg").select("g.allthedates").selectAll(".timelineItem").data(d).enter();
                    timexElements
                    .append('path')
                    .attr("d", function(d,i){
                        if(!d.deleted){
                            if(d.typ=="date"){
                                var zuo=xScale(d.times[0].starting_time)-10;
                                var you=zuo+20; 
                                var xia=$("#timeline").height()-70;
                                var shang=xia-20;
                                var obj={shang:shang,xia:xia,zuo:zuo,you:you}
                                obj=getFinalDraw(repeat,obj);
                                repeat.push(obj)
                                zuo=obj.zuo;
                                you=obj.you;
                                shang=obj.shang;
                                xia=obj.xia;
                                return "M "+zuo+" "+xia+" m -10, 0 a 10,10 0 1,0 20,0 a 10,10 0 1,0 -20,0"
                                // console.log(obj)
                                // console.log(repeat[1])
                            }
                            else if(d.typ=="duration") {
                                var zuo=xScale(d.times[0].starting_time)-10;
                                var you=xScale(d.times[0].ending_time)+10;
                                var xia=$("#timeline").height()-70;
                                var shang=xia-20;
                                var obj={shang:shang,xia:xia,zuo:zuo,you:you}
                                obj=getFinalDraw(repeat,obj);
                                repeat.push(obj)
                                zuo=obj.zuo;
                                you=obj.you;
                                shang=obj.shang;
                                xia=obj.xia;
                                // console.log(zuo,you,shang,xia)
                                return "M "+zuo+" "+xia+" l0 -20 l10 11 L"+(you-10)+" "+(shang+11)+
                                " l10 -11 l0 20 l-10 -11 L"+(zuo+10)+" "+(xia-11)+" Z"
                                // M329 155 L329 135 L339 146 L425.851015047636 146 L435.851015047636 135 L435.851015047636 155 L425.851015047636 144 L339 144 Z
                            }
                        }
                    })
                    .attr("class" , function(d){
                        if(action=="newDoc" || action=="loadData"){ var classes = "timelineItem_sent_"+d.sentId }
                        else{ var classes = ""}
                        return "timelineItem " + d.typ + " " + classes
                    })
                    .attr("id", function(d){ return "timelineItem_"+ d.id })
                    .attr("fill" , function(d){ return "#4daf4a" })
                    .on("click", function(d){ 
                        var id=d.id;
                        var sentnr=d.sentId;
                        var currenttext = d.textId;
                        if($("#Listid_"+id).hasClass('highlighted')){
                            $scope.gohome();
                        }
                        else{
                            $scope.dateSelected=true;
                            console.log($scope.currentfile);
                            console.log(currenttext);
                            if($scope.currentfile != currenttext){                               
                                
                                $scope.switchView(currenttext);
                            }
                            $scope.makeSelection(id,sentnr);
                            console.log($scope.currentfile);
                        }
                     })
                    .on("mouseover", function(d) { showlabel(d) })
                    .on("mouseout", function(d) { $("#eventlabel").css("display","none") })

       // var tip=' Steps:\n1.拷贝你的新闻内容至Document View内，并提交 \n2.点击Control Panel中的加号按钮，提供一个初始为空的事件供你写入\n3.根据Document View内的新闻，逐一向Control Panel的空事件写入事件，并提交\n4.点击Control Panel中的向下按钮，下次此次你写入的所有事件文件，以备下次使用\n5.若需重新加载新的新闻，点击Control Panel中的文本按钮    \n6.若需载入已经下载的事件文件，点击Control Panel中的向上按钮'
       //  alert(tip);

         $scope.paste=function(){
            if($('#pasteBox')[0].value){
                $('#docText')[0].innerHTML=$('#pasteBox')[0].value;
                $scope.pasteBoxShow=!$scope.pasteBoxShow
            }
            
         }

         $scope.pasteShow=function(){
            $('#docText')[0].innerHTML='<textarea id="pasteBox" autofocus style="width:100%;height:400px" placeholder="You can paste your news here!"></textarea>'
            $scope.pasteBoxShow=true
         }



        $scope.addDate=function(){
            $scope.currentid=$scope.timexes.length
            $('.display')[0].innerHTML='<span class="label">Time:</span><br><br><input id="sDate" type="date"><br><br><span class="label">Title:</span><br><br><textarea id="sTitle" class="bigTextarea"></textarea><br><br><span class="label">Content:</span><br><br><textarea class="bigTextarea" id="sContent"></textarea>'
            $scope.addDateShow=true
            $scope.isAddDate=true
            // console.log($('.display')[0].innerHTML)
        }

        $scope.submitDate=function(){
            if($scope.currentid<$scope.timexes.length){
                $scope.timexes[$scope.currentid].times=$('#sDate')[0].value;
                $scope.timexes[$scope.currentid].content=$('#sTitle')[0].value;
                $scope.timexes[$scope.currentid].sent=$('#sContent')[0].value;
            }else{
                if($('#sDate')[0].value&&$('#sTitle')[0].value&&$('#sContent')[0].value){
                    var thisEvent={ times:$('#sDate')[0].value,content:$('#sTitle')[0].value,id:$scope.timexes.length,typ:'date',touched:true,deleted:false,textId:0,sentId:1,sent:$('#sContent')[0].value,area:'36.0992900000,118.5276630000'};
                    $scope.timexes.push(thisEvent)
                }
            }
            // console.log($scope.timexes)
            $scope.updateD3("newDoc",$scope.timexes);
            
            // console.log($('#sTitle')[0].value)
            // console.log($('#sContent')[0].value)
        }
        //update timeline
         $scope.updateD3=function(action,t){
            var tt=updateTime(t);
            var minTime = 1420070400000;  // 2015
            var maxTime = -62135596800000; // year 1
            repeat=[];
            // d.forEach(function (time, i) {
            //     if(time.visible){
            //         var sT = time.times[0].starting_time;
            //         if (!isNaN(sT) && sT < minTime){ minTime = time.times[0].starting_time; }
            //         var eT = time.times[0].ending_time;
            //         if (!isNaN(eT) && eT > maxTime) maxTime = time.times[0].ending_time;
            //         }
            // });
            minTime=getMinTime($scope.timexes);
            maxTime=getMaxTime($scope.timexes);
            var beginning = minTime;
            var ending = maxTime;
            if(beginning == ending){
                beginning = beginning - 157784630000
                ending = ending + 157784630000
            }
            
            var width = $("#topBox").width();
            
            var xScale = d3.time.scale()
                    .domain([beginning, ending])
                    .range([puffer/2, width - puffer*2]);   
            
              var xAxis = d3.svg.axis().scale(xScale).ticks(15).tickSize(15)
            
              // READJUSTING PATHS
              if(beginning == 0) beginning = 1
              if(ending == 0) ending = 1

              scaleFactor = (1/(ending - beginning)) * (width - (puffer*2.5));
              if(tt.length!=0){
                    var newHeight = $("#topBox").height()
                    // d.forEach( function(tx){
                    //     var elTop = tx.yIndex*itemHeight + 100
                    //     if(tx.visible && newHeight<elTop){ newHeight = elTop }
                    // })
                    
                    d3.select("svg").attr("height",newHeight-10)
                    d3.select("svg").selectAll("g.axis")
                    .transition().duration(500)
                        .attr("transform","translate(0,"+ (parseInt(newHeight)-55) +")")
                        .call(xAxis);
                    $("#timeline").scrollTop(newHeight);
              }
            if(action=="add" || action == "merge" || action=="newDoc" || action=="loadData"){
                    var x = $("#timeline").width();
                    // console.log(tt)
                    var update = d3.select("svg").select("g.allthedates").selectAll(".timelineItem").data(tt);
                    update.transition().duration(500).attr("d", function(d,i){
                        console.log()
                        if(!d.deleted){
                            if(d.typ=="date"){
                                var zuo=xScale(d.times[0].starting_time)-10;
                                var you=zuo+20; 
                                var xia=$("#timeline").height()-70;
                                var shang=xia-20;
                                var obj={shang:shang,xia:xia,zuo:zuo,you:you}
                                obj=getFinalDraw(repeat,obj);
                                repeat.push(obj)
                                zuo=obj.zuo;
                                you=obj.you;
                                shang=obj.shang;
                                xia=obj.xia;
                                return "M "+zuo+" "+xia+" m -10, 0 a 10,10 0 1,0 20,0 a 10,10 0 1,0 -20,0"
                                // console.log(obj)
                                // console.log(repeat[1])
                            }
                            else if(d.typ=="duration") {
                                var zuo=xScale(d.times[0].starting_time)-10;
                                var you=xScale(d.times[0].ending_time)+10;
                                var xia=$("#timeline").height()-70;
                                var shang=xia-20;
                                var obj={shang:shang,xia:xia,zuo:zuo,you:you}
                                obj=getFinalDraw(repeat,obj);
                                repeat.push(obj)
                                zuo=obj.zuo;
                                you=obj.you;
                                shang=obj.shang;
                                xia=obj.xia;
                                // console.log(zuo,you,shang,xia)
                                return "M "+zuo+" "+xia+" l0 -20 l10 11 L"+(you-10)+" "+(shang+11)+
                                " l10 -11 l0 20 l-10 -11 L"+(zuo+10)+" "+(xia-11)+" Z"
                                // M329 155 L329 135 L339 146 L425.851015047636 146 L435.851015047636 135 L435.851015047636 155 L425.851015047636 144 L339 144 Z
                            }
                        }
                    })
                    .attr("class" , function(d){
                        if(action=="newDoc" || action=="loadData"){ var classes = "timelineItem_sent_"+d.sentId }
                        else{ var classes = ""}
                        return "timelineItem " + d.typ + " " + classes
                    })
                    .attr("id", function(d){ return "timelineItem_"+ d.id })
                    .attr("fill" , function(d){ return "#4daf4a" });

                    update.exit().remove();

                    update.enter().append('path')
                    .attr("d", function(d,i){
                        console.log()
                        if(!d.deleted){
                            if(d.typ=="date"){
                                var zuo=xScale(d.times[0].starting_time)-10;
                                var you=zuo+20; 
                                var xia=$("#timeline").height()-70;
                                var shang=xia-20;
                                var obj={shang:shang,xia:xia,zuo:zuo,you:you}
                                obj=getFinalDraw(repeat,obj);
                                repeat.push(obj)
                                zuo=obj.zuo;
                                you=obj.you;
                                shang=obj.shang;
                                xia=obj.xia;
                                return "M "+zuo+" "+xia+" m -10, 0 a 10,10 0 1,0 20,0 a 10,10 0 1,0 -20,0"
                                // console.log(obj)
                                // console.log(repeat[1])
                            }
                            else if(d.typ=="duration") {
                                var zuo=xScale(d.times[0].starting_time)-10;
                                var you=xScale(d.times[0].ending_time)+10;
                                var xia=$("#timeline").height()-70;
                                var shang=xia-20;
                                var obj={shang:shang,xia:xia,zuo:zuo,you:you}
                                obj=getFinalDraw(repeat,obj);
                                repeat.push(obj)
                                zuo=obj.zuo;
                                you=obj.you;
                                shang=obj.shang;
                                xia=obj.xia;
                                // console.log(zuo,you,shang,xia)
                                return "M "+zuo+" "+xia+" l0 -20 l10 11 L"+(you-10)+" "+(shang+11)+
                                " l10 -11 l0 20 l-10 -11 L"+(zuo+10)+" "+(xia-11)+" Z"
                                // M329 155 L329 135 L339 146 L425.851015047636 146 L435.851015047636 135 L435.851015047636 155 L425.851015047636 144 L339 144 Z
                            }
                        }
                    })
                    .attr("class" , function(d){
                        if(action=="newDoc" || action=="loadData"){ var classes = "timelineItem_sent_"+d.sentId }
                        else{ var classes = ""}
                        return "timelineItem " + d.typ + " " + classes
                    })
                    .attr("id", function(d){ return "timelineItem_"+ d.id })
                    .attr("fill" , function(d){ return "#4daf4a" })
                    .on("click", function(d){ 
                        var id=d.id;
                        var sentnr=d.sentId;
                        var currenttext = d.textId;
                        if($("#Listid_"+id).hasClass('highlighted')){
                            $scope.gohome();
                        }
                        else{
                            $scope.dateSelected=true;
                            console.log($scope.currentfile);
                            console.log(currenttext);
                            if($scope.currentfile != currenttext){                               
                                
                                $scope.switchView(currenttext);
                            }
                            $scope.makeSelection(id,sentnr);
                            console.log($scope.currentfile);
                        }
                    })
                    .on("mouseover", function(d) { showlabel(d) })
                    .on("mouseout", function(d) { $("#eventlabel").css("display","none") })
            }
            return tt;
            }

        

        
        $('body').keydown(function(d){
            var key=d.keyCode;
            // console.log(window.localStorage); 
            if (!$("input, textarea").is(":focus")) {
                if(key==8||key==46||key==189||key==109){
                    event.preventDefault();
                    var thisid=parseInt($('.highlighted').attr('id').slice(7));
                    $scope.timexes[thisid].deleted=true;
                    $('.highlighted').addClass('deleted');
                    // $scope.makeSelection(thisid+2);
                    // console.log($scope.timexes[thisid].deleted);
                }
                if(key==107||key==187){
                    var thisid=parseInt($('.highlighted').attr('id').slice(7));
                    $scope.timexes[thisid].deleted=false;
                    $('.highlighted').removeClass('deleted');
                }
                $scope.updateD3("newDoc",$scope.timexes);
                // alert(key);
                }
            })

        $scope.clickList=function(d){
            // console.log(tx);
            $scope.currentid = d.id;
            // alert($scope.currentid)
            $scope.currentsent = d.sentId;
            var currenttext= d.textId;
            var id=d.id;
            var sentnr=d.sentId;
            if($("#Listid_"+id).hasClass('highlighted')){
                $scope.addDateShow=false
                $('.display')[0].innerHTML='';
                $scope.gohome();
            }
            else{
                    $scope.dateSelected=true;
                    $scope.addDateShow=true;

                    if($scope.currentfile != currenttext){
                        //$scope.currentfile = currenttext;
                        $scope.switchView(currenttext);
                    //console.log("switch") ;
                }
                $scope.makeSelection(id,sentnr);
            }
        }

        $scope.makeSelection=function(id,sentnr){
            //List中的高亮
            var thisid="#Listid_"+id;
            var drawid="#timelineItem_"+id;
            if($(thisid).hasClass('highlighted')){
                $(thisid).removeClass('highlighted');
                $(drawid).attr("class",$(drawid).attr("class").replace("selected",""))
            }else{
                $('.highlighted').removeClass('highlighted');
                $('.selected').attr("class",$(drawid).attr("class").replace("selected",""))
                $(thisid).addClass('highlighted');  
                $(drawid).attr("class",$(drawid).attr("class")+" selected")
            }
            $scope.rollList(id);
            //Sent中的高亮
            // $scope.highlightSent(sentnr);
            //句子跳转
            // $scope.rollSent(sentnr);
            var newDate = $scope.showDateInfo($scope.timexes[id]);
            $scope.showSelectedEvent(id)
            $scope.dateInfo = [];
            $scope.dateInfo.push(newDate);
        }
        
        // $scope.addedText=[
        // {textId:'1',content:'',title:''}
        // ]
        $scope.showSelectedEvent=function(id){
            var selectedEvent=$scope.timexes[id]
            $('.display')[0].innerHTML='<span class="label">Time:</span><br><br><input id="sDate" type="date" value='+selectedEvent['times'].substring(0,10)+'><br><br><span class="label">Title:</span><br><br><textarea id="sTitle" class="bigTextarea">'+selectedEvent['content']+'</textarea><br><br><span class="label">Content:</span><br><br><textarea class="bigTextarea" id="sContent">'+selectedEvent['sent']+'</textarea>'
        }
        //function for centre box
        $scope.switchView= function(v){ 
            $scope.currentfile = v;
            //console.log($scope.currentfile);

            if($("#doc"+v).hasClass("activeBtn")){
                if($("#docSwitcher").css("overflow") == "visible"){
                    $("#docSwitcher").css("overflow" , "hidden" )
                }
                else{ $("#docSwitcher").css("overflow", "visible") }
            }
            else{
                //$(".txtData").removeClass("activetab")
                $(".docBtn").removeClass("activeBtn")
                $("#doc"+v).addClass("activeBtn")
                //$("#txtData_"+v).addClass("activetab")
                $("#docList").animate({ "top" : (v*27*(-1)) }, 200)
                $("#docSwitcher").css("overflow" , "hidden" )
            }   
        }
        $scope.clickingSent = function(sentnr,eventid){
            if ($("#timeSent_"+sentnr).hasClass("highlighted")){
                $scope.gohome();
                }
            else {
                $scope.dateSelected = true;
                $scope.currentsent=sentnr;
                $scope.currentid=eventid;
                $scope.makeSelection(eventid,sentnr);
            }
        }

        $scope.highlightSent = function(sentnr){
            $(".timex").removeClass("highlighted");
    
            $("#timeSent_"+sentnr).addClass("highlighted");
        }
  

        $scope.rollSent = function(sentnr){    // Text View
                var thisTextEl = "#timeSent_"+sentnr;
                var topTextPos = $("#centerBox").scrollTop() + $(thisTextEl).position().top- $("#centerBox").height()/2 + $(thisTextEl).height()/2;

                $("#centerBox").animate({ scrollTop: topTextPos }, 300);
        }

        $scope.rollList = function(eventid){
            var thisList = "#Listid_"+eventid;
            var topTextPos = $("#listData").scrollTop() + $(thisList).position().top- $("#listData").height()/2 + $(thisList).height()/2;
                $("#listData").animate({ scrollTop: topTextPos },100);
        }

        $scope.gohome = function(){
            $scope.dateSelected = false;
            $scope.currentid = -1;
            $scope.currentsent=-1;
            //d3.selectAll(".timelineItem").classed("selected", false).classed("selectedSec", false);
            $(".tx").removeClass("activeTx")
            $(".timex, .listEl").removeClass("highlighted");
            $(".selected").attr("class",$('.selected').attr("class").replace("selected",""))
            $scope.dateInfo = []
        }


        //function for right box
        $scope.showDateInfo = function(date){
            var dateInfo={};
            
            dateInfo.dateArray=[];
            if(date.typ=="date"){
                dateInfo.dateArray[0] = date.times.substring(0,4);
                dateInfo.dateArray[1] = date.times.substring(5,7);
                dateInfo.dateArray[2] = date.times.substring(8,10);
            }
            else{
                dateInfo.dateArray[0] = date.times.substring(0,4);
                dateInfo.dateArray[1] = date.times.substring(5,7);
                dateInfo.dateArray[2] = date.times.substring(8,10);
                dateInfo.dateArray[3] = date.times.substring(11,15);
                dateInfo.dateArray[4] = date.times.substring(16,18);
                dateInfo.dateArray[5] = date.times.substring(19,21);  
            }
            dateInfo.times = date.times;
            dateInfo.subtitle = date.content;
            dateInfo.sent =date.sent;
            dateInfo.typ = date.typ;
            //console.log(dateInfo.sent);
            return dateInfo;
        }

        $scope.enableEdit = function(el){
            if(el=="t"){
                $scope.editDate = true;
            }
            else if(el=="d"){ 
                $scope.editTitle = true; 
                //var thisid = "#displaySubtitle input"
            }
            else if(el=="c"){ $scope.editContent = true; }

            //if(thisid) setTimeout( function(){ $(thisid).select(); } , 50 )
        }

        $scope.disableEdit = function(el){
            if(el=="t"){
                $scope.editDate = false;
                $scope.times = $scope.dateInfo.dateArray[0]+"-"+$scope.dateInfo.dateArray[1]+"-"+$scope.dateInfo.dateArray[2]+","+$scope.dateInfo.dateArray[3]+"-"+$scope.dateInfo.dateArray[4]+"-"+$scope.dateInfo.dateArray[5];

            }
            if(el=="d"){
                $scope.editTitle = false;
                if($scope.dateInfo.length>0){
                    if($scope.dateInfo[0].subtitle.length>0){
                        if($scope.timexes[$scope.currentid].content!=$scope.dateInfo[0].subtitle){
                            //$scope.markAsTouched($scope.timexes,$scope.currIndex);
                            $scope.timexes[$scope.currentid].content = $scope.dateInfo[0].subtitle;
                        }
                    }   
                    else{
                        $scope.dateInfo[0].subtitle = " "
                        $scope.timexes[$scope.currentid].content= " " }
                }
            }
            else if(el=="c"){
                $scope.editContent = false;
                if($scope.dateInfo.length>0){
                    if($scope.timexes[$scope.currentid].sent!=$scope.dateInfo[0].sent){
                        //$scope.markAsTouched($scope.timexes,$scope.currIndex);
                        if($scope.dateInfo.length>0){
                            $scope.timexes[$scope.currentid].sent = $scope.dateInfo[0].sent;
                        }
                    }
                }
            }
        }
    })
</script>
</html>