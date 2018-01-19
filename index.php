<!DOCTYPE html>
<html>
    <head>
        <title>  Code Videos! </title>
        <style>
            td{
                border: 1px solid;
                margin: auto;
                padding: 5px 5px 5px 5px;
                align-content: center;
                text-align: center;
            }
        </style>
        
        <script type='text/javascript' src='https://code.jquery.com/jquery-1.11.0.min.js'></script>
        <script>
'use strict';
var lastTime = -1;
var tsArray = [];
            
document.addEventListener('keydown', (event) => {
  const keyName = event.key;
var curTime = parseFloat(document.getElementById("myVideo").currentTime);
    if (event.keyCode === 32){
        play_pause();
    } else if (event.keyCode === 65){ // a
        document.getElementById("myVideo").pause();
        document.getElementById("play-pause").innerHTML = "Pause";
        back1sec();
    } else if (event.keyCode === 83){ //s
        document.getElementById("myVideo").pause();
        document.getElementById("play-pause").innerHTML = "Pause";
        back0_1sec();
    } else if (event.keyCode === 69){ // e
      document.getElementById("export").click();
    } else if (event.keyCode === 81){ // q
      next_video();
    } else if (event.keyCode === 84){ // t
    document.getElementById("myVideo").pause();
    document.getElementById("myVideo").currentTime = 0.0;
    document.getElementById("play-pause").innerHTML = "Pause";
    } else if (event.keyCode === 90){ // z
        var v = document.getElementById("myVideo");
        v.playbackRate = 0.9*v.playbackRate;
	document.getElementById("vidSpeed").value = v.playbackRate;
        document.getElementById("slow-fast").innerHTML = "Speed = " + (Math.round(v.playbackRate*100)/100.0).toString();
    }else if (event.keyCode === 88){ // x
        var v = document.getElementById("myVideo");
        v.playbackRate = v.playbackRate/0.9;
	document.getElementById("vidSpeed").value = v.playbackRate;
        document.getElementById("slow-fast").innerHTML = "Speed = " + (Math.round(v.playbackRate*100)/100.0).toString();
    }
    else if(curTime > lastTime+0.02){
      var row = $("<tr></tr>");
      var col1 = $("<td></td>");
      lastTime = parseFloat(document.getElementById("myVideo").currentTime);
      col1.append(document.getElementById("myVideo").currentTime);
      var col2 = $("<td></td>");
      col2.append(keyName);
      row.append(col1,col2).prependTo("#mytable");  
      tsArray.push(['f',lastTime,keyName,document.getElementById('myVideo').currentSrc]);
    }
}, false);

function next_video(){
  localStorage.setItem('tsArray',JSON.stringify(tsArray));
  var v2exp = parseInt(localStorage.getItem('v2exp'));
  localStorage.setItem('v2exp',v2exp+1);
  document.getElementById("nv").click();
}

function telltime(){
    var x = document.getElementById("mytable").rows[0].cells;
    alert(x[0].innerHTML);
}
function forward1sec(){
    var curTime = document.getElementById("myVideo").currentTime
    document.getElementById("myVideo").currentTime = parseFloat(curTime) + 1.0;
}
function forward0_1sec(){
    var curTime = document.getElementById("myVideo").currentTime
    document.getElementById("myVideo").currentTime = parseFloat(curTime) + 0.1;
}
function back1sec(){
    var curTime = parseFloat(document.getElementById("myVideo").currentTime);
    while (lastTime > curTime - 1.0){
        document.getElementById("mytable").deleteRow(0);
        // var x = tsArray.pop();
        if (document.getElementById("mytable").rows.length > 0){
            lastTime = parseFloat((document.getElementById("mytable").rows[0].cells)[0].innerHTML);
        } else {
            lastTime = -1;
        }
    }
    document.getElementById("myVideo").currentTime = parseFloat(curTime) - 1.0;
}
function back0_1sec(){
    var curTime = parseFloat(document.getElementById("myVideo").currentTime);
    while (lastTime > curTime - 0.1){
        document.getElementById("mytable").deleteRow(0);
        // var x = tsArray.pop();
        if (document.getElementById("mytable").rows.length > 0){
            lastTime = parseFloat((document.getElementById("mytable").rows[0].cells)[0].innerHTML);
        } else {
            lastTime = -1;
        }
    }
    document.getElementById("myVideo").currentTime = parseFloat(curTime) - 0.1;
}
function play_pause(){
    var v = document.getElementById("myVideo");
    v.playbackRate = parseFloat(document.getElementById("vidSpeed").value);
    if(v.paused){
        var curTime = v.currentTime;
        var i = 1;
        var n = tsArray.length+1
        while(i < n){
            if (tsArray[n-i-1][1] < curTime){
                break;
            }
            tsArray[n-i-1][0] = 'r';
            i = i + 1;
        }
        v.play();
        document.getElementById("play-pause").innerHTML = "Pause";
    }else{
        v.pause();
        document.getElementById("play-pause").innerHTML = "Play "
    }
}
function slow_fast(){
    var v = document.getElementById("myVideo");
    if(v.playbackRate == 1.0){
        v.playbackRate = 0.5;
        document.getElementById("slow-fast").innerHTML = "Fast";
    } else {
        v.playbackRate = 1.0;
        document.getElementById("slow-fast").innerHTML = "Slow";
    }
}

function exportToCsv(filename, rows) {
  var processRow = function (row) {
    var finalVal = '';
    for (var j = 0; j < row.length; j++) {
      var innerValue = row[j] === null ? '' : row[j].toString();
      if (row[j] instanceof Date) {
	innerValue = row[j].toLocaleString();
      };
      var result = innerValue.replace(/"/g, '""');
            if (result.search(/("|,|\n)/g) >= 0)
    result = '"' + result + '"';
    if (j > 0)
      finalVal += ',';
    finalVal += result;
}
return finalVal + '\n';
};

var csvFile = 'username,repeat_ind,time,code,video\n';
var uname = document.getElementById('username').value;
    for (var i = 0; i < rows.length; i++) {
        csvFile += uname + ',' + processRow(rows[i]);
    }

    var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, filename);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
if (document.getElementById("autoplay").checked){
  var start = new Date().getTime();
  var end = start;
  while(end < start + 2000) {
    end = new Date().getTime();
  }
  localStorage.setItem('v2exp',0);
  next_video();
}
}


$(document).ready(function(){
    tsArray = JSON.parse(localStorage.getItem('tsArray')) || new Array();
    var v = document.getElementById('myVideo');
    v.oncanplay = function(){
      v.pause();
      setTimeout(function(){v.play();},2000); };
    //setTimeout(function(){play_pause();},2000);
    document.getElementById("v2exp").innerHTML = parseInt(localStorage.getItem('v2exp')) || 0;
  });    



        </script>
    </head>
    <body>
<?php
	$list_of_players = 

	$list_of_mp4 = array_filter(explode("\n",file_get_contents('videos.txt')), create_function('$value', 'return $value !== "";'));
	if(!isset($_POST['vid'])){
	  $i = 0;
	  print '<form action="index.php" method="post" id="myform">'."\n";
	  print 'Name: <input type="text" name="username" id="user"></input><br>'."\n";
	  print '<input type="hidden" id="vidSpeed" name="vidSpeed" value="1.0" />'."\n";
	  foreach ($list_of_mp4 as $mp4){
	    if ($mp4 != ""){
	      print '<input type="radio" name="vid" value="'. $i . '" onclick="this.form.submit();">'. "\t" . strval($i+1) . ".\t". $mp4  . "<br>" . "\n";
	      $i = $i + 1;
	    }
	  }
	  print '</form>'."\n";
	  print '<script type="text/javascript">
	     localStorage.setItem("v2exp",0);
	     localStorage.setItem("tsArray",JSON.stringify(new Array()));
	  </script>' . "\n";

	} else {
	  print  '<video width="640" id="myVideo">' . "\n";
          print '<source src="'. $list_of_mp4[(int)$_POST['vid']] .'" type="video/mp4">' . "\n";
	  print '</video>' . "\n";
	  print '<div id="controls">' . "\n";
	  print '<button id="back1sec" onclick="back1sec()">&lt; 1 sec</button>' . "\n";
	  print '<button id="back0_1sec" onclick="back0_1sec()">&lt; 0.1 sec</button>' . "\n";
	  print '<button id="play-pause" onclick="play_pause()">Play </button>' . "\n";
	  print '<button id="slow-fast">Speed = ' . round((float)$_POST['vidSpeed'],2) . '</button>' . "\n";
	  print '<button id="export" onclick="exportToCsv(\'' . $_POST['username']  . '.csv\',tsArray)">Export</button>' . "\n";
	  print '<input type="checkbox" id="autoplay" name="autoplay" value="0" checked>Autoplay'. "\n"; 
	  print '#Videos to export: <button id="v2exp">0</button><br>'."\n";
	  print '<form name="nextVid" action="index.php" method="post">'."\n";
	  print '<input type="hidden" id="vidSpeed" name="vidSpeed" value="' . $_POST['vidSpeed'] . '" />'."\n";
	  print '<input type="hidden" id="username" name="username" value="' . $_POST['username'] . '" />'."\n";
	  print 'Next Video <input type="submit" id="nv" name="vid" value="'. ((int)$_POST['vid']+1) . '">' . "<br></form>" . "\n";
	  print '</div>' . "\n";
	  print '<table id="mytable" cellpadding="2" cellspacing="1">' . "\n";
	  print '</table>' . "\n";
	  
	}
?>        
    </body>
</html>
