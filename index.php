<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="A clean and modern responsive spades scorekeeper">
  <meta name="keywords" content="Spades">

  <title>Spades Scorekeeper</title>
  <link rel="stylesheet" href="css/foundation.css" />
  <script src="js/vendor/modernizr.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
  <style type="text/css">
    body{
      padding: 1em 0em 1em 0em;
      background-color: rgb(50,50,50);
      font-size: 1.5em;
      font-family: 'Open Sans', sans-serif;
      font-style: normal;
      font-weight: 400;
    }
    input[type="text"]{
      font-family: 'Open Sans', sans-serif;
      text-align: center;
    }
    input[type="text"]:focus{
      outline: none;
    }
    input[type="text"].nametag{
      display: inline-block;
      background-color: rgba(50,50,50,0);
      border: none;
      border-bottom: thin solid;
      font-size: .5em;
      width: 12%
    }
    input[type="text"].empty{
      background-color: rgba(30,30,30,1);
    }
    input[type="text"].nametag:focus{
      box-shadow: none;
    }
    #p1,.p1{
      color: #D7DB56;
      border-color: #D7DB56;
    }
    #p2,.p2{
      color: #56DB8F;
    }
    #p3,.p3{
      color: #DB5682;
    }
    #p4,.p4{
      color: #56ACDB
    }
    .bid,.take,input[type="text"].scores,input[type="text"].totalscores{
      display: inline-block;
      background-color: rgba(50,50,50,0);
      border: none;
      box-shadow: none;
      width: 5%;
      font-size: .75em;
    }
    input[type="text"].take{
      font-weight: 700;
    }
    .bid{
      font-weight: 200;
    }
    input[type="text"].take,input[type="text"].bid{
      border-bottom:thin solid ;
    }
    input[type="text"].scores,input[type="text"].totalscores{
      padding: 0px;
      width: 9%
    }
    .vs, input[type="text"].scores,input[type="text"].totalscores{
      text-align: center;
      font-size: 1em;
      color: rgb(200,200,200);
    }
    .vs{
      width: 18%;
      display: inline-block;
    }
    @media only screen { } /* Define mobile styles */

    @media only screen and (max-width: 40em) { } /* max-width 640px, mobile-only styles, use when QAing mobile issues */

    @media only screen and (min-width: 40.063em) { } /* min-width 641px, medium screens */

    @media only screen and (min-width: 40.063em) and (max-width: 64em) { } /* min-width 641px and max-width 1024px, use when QAing tablet-only issues */

    @media only screen and (min-width: 64.063em) { } /* min-width 1025px, large screens */

    @media only screen and (min-width: 64.063em) and (max-width: 90em) { } /* min-width 1025px and max-width 1440px, use when QAing large screen-only issues */

    @media only screen and (min-width: 90.063em) { } /* min-width 1441px, xlarge screens */

    @media only screen and (min-width: 90.063em) and (max-width: 120em) { } /* min-width 1441px and max-width 1920px, use when QAing xlarge screen-only issues */

    @media only screen and (min-width: 120.063em) { } /* min-width 1921px, xxlarge screens */

    #chart_div{
      width: 100%
    }
  </style>

</head>
<body>
  <div class="row">gfgdg
    <div class="small-12 medium-6 columns">
      <input id='p1' class="nametag p1" type="text">    
      <input id='p2' class="nametag p2" type="text">
      <div class="vs">vs</div>
      <input id='p3' class="nametag p3" type="text">
      <input id='p4' class="nametag p4" type="text">
      <?php if (!isset($_GET['game'])){
        ?>
        <input type="submit" onclick="createGame()">
        <?php
      }
      ?>
      <div id="hands" class="row">
      </div>

    </div>
    <div class="small-12 medium-6 columns">
      <div id="chart_div"></div>
    </div>
  </div>
  <?php
  if(isset($_GET['game'])){
    ?>
    <script type="text/javascript">

       window.onload = function() { // this will be run when the whole page is loaded
        LoadGame('<?php echo $_GET['game'];?>');
      };
    </script>
    <?php
  }
  ?>

  <script type="text/javascript">
    function createGame() {
      console.log("creating Game");
      AjaxRequest('create');
    }
    function autoAdvanace(element){
      var player = element.getAttribute("player");
      var hand = element.getAttribute("hand");
      var type = element.getAttribute("kind");
      switch(Number(player)) {
        case 1:
        document.getElementById("hand"+hand+type+"3").focus()
        break;
        case 2:
        document.getElementById("hand"+hand+type+"4").focus()
        break;
        case 3:
        document.getElementById("hand"+hand+type+"2").focus()
        break;
        case 4:
        document.getElementById("hand"+hand+type+"1").focus()
        break;
      }

    }
    function LoadGame(id){
      console.log("Loading Game: "+id);
      AjaxRequest('load',id);
    }
    <?php if (isset($_GET['game'])){?>
      function update(hand){
        console.log("Updating: "+hand);
        var updatedata = "&"+hand+"bid1="+document.getElementById(hand+"bid1").value;
        updatedata += "&"+hand+"bid2="+document.getElementById(hand+"bid2").value;
        updatedata += "&"+hand+"bid3="+document.getElementById(hand+"bid3").value;
        updatedata += "&"+hand+"bid4="+document.getElementById(hand+"bid4").value;
        updatedata += "&"+hand+"take1="+document.getElementById(hand+"take1").value;
        updatedata += "&"+hand+"take2="+document.getElementById(hand+"take2").value;
        updatedata += "&"+hand+"take3="+document.getElementById(hand+"take3").value;
        updatedata += "&"+hand+"take4="+document.getElementById(hand+"take4").value;

        AjaxRequest('update','<?php echo $_GET['game'];?>','game=<?php echo $_GET['game'];?>'+updatedata);

      }
      <?php 
    } ?>
    function AjaxRequest(type,gameID,data) {
      var xmlhttp;
      if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
      else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          console.log(xmlhttp.responseText);
          if (type == "create"){
            window.location.href = './'+xmlhttp.responseText;
          }
          if (type == "load"){
            data = JSON.parse(xmlhttp.responseText);
            console.log(data);
            document.getElementById('p1').value=data.players[0]
            document.getElementById('p2').value=data.players[1]
            document.getElementById('p3').value=data.players[2]
            document.getElementById('p4').value=data.players[3]
            document.getElementById("hands").innerHTML= '';
            for (i = 1; i <= data.hands.length; i++) { 
              var addendum = '\
              <div class="small-12 columns">\
                <input type="text" id="hand'+i+'bid1" onkeypress=autoAdvanace(this); player=1 kind="bid" hand='+i+' placecholder="hand'+i+'bid1" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].bids[0] )+'" onchange="update(\'hand'+i+'\')" class="bid p1 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'take1" onkeypress=autoAdvanace(this); player=1 kind="take" hand='+i+' placecholder="hand'+i+'take1" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].takes[0] )+'" onchange="update(\'hand'+i+'\')" class="take p1 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'bid2"  onkeypress=autoAdvanace(this); player=2 kind="bid" hand='+i+' placecholder="hand'+i+'bid2" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].bids[1] )+'" onchange="update(\'hand'+i+'\')" class="bid p2 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'take2"  onkeypress=autoAdvanace(this); player=2 kind="take" hand='+i+' placecholder="hand'+i+'take2" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].takes[1] )+'" onchange="update(\'hand'+i+'\')" class="take p2 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'scores1" class="scores" >\
                <input type="text" id="hand'+i+'totalscores1" class="totalscores" >\
                <input type="text" id="hand'+i+'bid3" onkeypress=autoAdvanace(this); player=3 kind="bid" hand='+i+' placecholder="hand'+i+'bid3" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].bids[2] )+'" onchange="update(\'hand'+i+'\')" class="bid p3 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'take3" onkeypress=autoAdvanace(this); player=3 kind="take" hand='+i+' placecholder="hand'+i+'take3" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].takes[2] )+'" onchange="update(\'hand'+i+'\')" class="take p3 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'bid4" onkeypress=autoAdvanace(this); player=4 kind="bid" hand='+i+' placecholder="hand'+i+'bid4" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].bids[3] )+'" onchange="update(\'hand'+i+'\')" class="bid p4 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'take4" onkeypress=autoAdvanace(this); player=4 kind="take" hand='+i+' placecholder="hand'+i+'take4" value="'+ ((typeof data.hands[i] === 'undefined') ? '' : data.hands[i].takes[3] )+'" onchange="update(\'hand'+i+'\')" class="take p4 '+((typeof data.hands[i] === 'undefined') ? 'empty' : '')+'">\
                <input type="text" id="hand'+i+'scores2" class="scores">\
                <input type="text" id="hand'+i+'totalscores2" class="totalscores" >\
              </div>\
              '

              document.getElementById('hands').innerHTML+=addendum;
            }
            UpdateScores();
          }
          if (type == "update"){
            UpdateScores();
          }
        }
      }
      if (type == 'load'){
        xmlhttp.open("GET","games/"+gameID+".spades",true);
        xmlhttp.send();
      }
      else if (type == 'create'){
        var postrequest = "&p1="+document.getElementById('p1').value+"&p2="+document.getElementById('p2').value+"&p3="+document.getElementById('p3').value+"&p4="+document.getElementById('p4').value;

        xmlhttp.open("POST","api.php");
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send("type="+type+"&"+postrequest);
      }
      else if (type == 'update'){

        xmlhttp.open("POST","api.php");
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send("type="+type+"&"+data);
      }

    }
    function UpdateScores(){
      var obj = {};
      for (i=1; i <= document.getElementsByClassName("scores").length/2; i++){
        for (j = 0; j < 3 ; j=j+2){
          var score = 0;
          var bags =0;
          //debugger;
          obj["h"+i+"b"+(1+j)] = Number(document.getElementById('hand'+i+'bid'+(1+j)).value);
          obj["h"+i+"b"+(2+j)] = Number(document.getElementById('hand'+i+'bid'+(2+j)).value);
          obj["h"+i+"t"+(1+j)] = Number(document.getElementById('hand'+i+'take'+(1+j)).value);
          obj["h"+i+"t"+(2+j)] = Number(document.getElementById('hand'+i+'take'+(2+j)).value);
          if (( obj["h"+i+"b"+(1+j)] || obj["h"+i+"b"+(1+j)] == 0) && (obj["h"+i+"t"+(1+j)] || obj["h"+i+"t"+(1+j)] == 0) && ( obj["h"+i+"b"+(2+j)] || obj["h"+i+"b"+(2+j)] == 0) &&  (obj["h"+i+"t"+(2+j)] || obj["h"+i+"t"+(2+j)] ==0)&& document.getElementById('hand'+i+'take'+(1+j)).value!=''){
            if(obj["h"+i+"b"+(1+j)] == 0 ){
              if(obj["h"+i+"t"+(1+j)]> 0 ){
                score -= 100;
              }
              if ( obj["h"+i+"t"+(1+j)] == 0){
                score += 100;
              }
            }
            if(obj["h"+i+"b"+(2+j)] == 0 ){

              if(obj["h"+i+"t"+(2+j)] > 0 ){
                score -= 100;
              }
              if ( obj["h"+i+"t"+(2+j)] == 0 ){
                score += 100;
              }
            }
            if(obj["h"+i+"b"+(1+j)]+ obj["h"+i+"b"+(2+j)] > obj["h"+i+"t"+(1+j)]+ obj["h"+i+"t"+(2+j)]){
              console.log('Team '+(1+j/2)+' was set on hand '+i);
            //team did not make bid
            score -= (obj["h"+i+"b"+(1+j)] + obj["h"+i+"b"+(2+j)]) * 10;
          }
          if(obj["h"+i+"b"+(1+j)] + obj["h"+i+"b"+(2+j)] == obj["h"+i+"t"+(1+j)] + obj["h"+i+"t"+(2+j)]){
            console.log('Team '+(1+j/2)+' made bid on hand '+i);
            //team makes bid
            score += (obj["h"+i+"b"+(1+j)] + obj["h"+i+"b"+(2+j)]) * 10;
          }
          if ( obj["h"+i+"b"+(1+j)] + obj["h"+i+"b"+(2+j)] < obj["h"+i+"t"+(1+j)] + obj["h"+i+"t"+(2+j)]){
            //team bags
            score += (obj["h"+i+"b"+(1+j)] + obj["h"+i+"b"+(2+j)]) * 10;
            bags = ((obj["h"+i+"t"+(1+j)] + obj["h"+i+"t"+(2+j)]) - (obj["h"+i+"b"+(1+j)] + obj["h"+i+"b"+(2+j)])) ;
            console.log('team '+(j/2+1)+' bags '+bags+' on hand '+i);

          }
          document.getElementById('hand'+i+'scores'+(j/2+1)).setAttribute("score",score) 
          document.getElementById('hand'+i+'scores'+(j/2+1)).setAttribute("bags",bags)
          if (score<0){
            console.log('scores '+score+' less than 0')
            document.getElementById('hand'+i+'scores'+(j/2+1)).value = score - bags;

          }else{
            document.getElementById('hand'+i+'scores'+(j/2+1)).value = score + bags;
          }
        }
      }//end of four loop
    }//end of four loop
    //start updateing the total scores
    //      
    for (j = 1; j < 3 ; j++){
      var totalScore=0;
      for (i=1; i <= document.getElementsByClassName("scores").length/2; i++){
        var currenthandscore = Number(document.getElementById("hand"+i+"scores"+j).getAttribute("score"));
        var currenthandbags = Number(document.getElementById("hand"+i+"scores"+j).getAttribute("bags"));

        if(i==1){
          document.getElementById("hand"+(i)+"totalscores"+j).value=Number(document.getElementById("hand"+i+"scores"+j).value);
          document.getElementById("hand"+(i)+"totalscores"+j).setAttribute("bags",currenthandbags);
          document.getElementById("hand"+(i)+"totalscores"+j).setAttribute("score",currenthandscore);
          continue;
        }


        if (document.getElementById("hand"+i+"scores"+j).value != ''){
          var previoustotalscore = Number(document.getElementById("hand"+(i-1)+"totalscores"+j).getAttribute("score"));
          var previoustotalbags = Number(document.getElementById("hand"+(i-1)+"totalscores"+j).getAttribute("bags"));
          var newtotal = previoustotalscore+currenthandscore;

          if(previoustotalbags+ currenthandbags >=10){

            if ((newtotal + currenthandbags + previoustotalbags - 110)>0) {
              document.getElementById("hand"+(i)+"totalscores"+j).value = newtotal + currenthandbags + previoustotalbags - 110;
            }else{
              document.getElementById("hand"+(i)+"totalscores"+j).value = newtotal - currenthandbags - previoustotalbags - 90;
            }

            document.getElementById("hand"+(i)+"totalscores"+j).setAttribute("bags",currenthandbags+previoustotalbags-10);
            document.getElementById("hand"+(i)+"totalscores"+j).setAttribute("score",newtotal-100);

            continue

          }
          document.getElementById("hand"+(i)+"totalscores"+j).setAttribute("bags",currenthandbags+previoustotalbags);
          document.getElementById("hand"+(i)+"totalscores"+j).setAttribute("score",newtotal);

          if (newtotal >= 0){
            document.getElementById("hand"+(i)+"totalscores"+j).value =newtotal +currenthandbags+previoustotalbags;
          }else{  
            document.getElementById("hand"+(i)+"totalscores"+j).value = newtotal -currenthandbags-previoustotalbags;
          }
        }
      }
    }
  //after the total scores are updated, add another row of input boxes.
  i = document.getElementsByClassName("scores").length/2 

  if (document.getElementById("hand"+i+"take1").value && document.getElementById("hand"+i+"take3").value && document.getElementById("hand"+i+"take2").value && document.getElementById("hand"+i+"take4").value ){
    i++;
    var addendum = '\
    <div class="small-12 columns">\
      <input type="text" id="hand'+i+'bid1" placecholder="hand'+i+'bid1"  onchange="update(\'hand'+i+'\')" class="bid p1 empty">\
      <input type="text" id="hand'+i+'take1" placecholder="hand'+i+'take1"  onchange="update(\'hand'+i+'\')" class="take p1 empty">\
      <input type="text" id="hand'+i+'bid2" placecholder="hand'+i+'bid2" onchange="update(\'hand'+i+'\')" class="bid p2 empty">\
      <input type="text" id="hand'+i+'take2" placecholder="hand'+i+'take2"  onchange="update(\'hand'+i+'\')" class="take p2 empty">\
      <input type="text" id="hand'+i+'scores1" class="scores" >\
      <input type="text" id="hand'+i+'totalscores1" class="totalscores" >\
      <input type="text" id="hand'+i+'bid3" placecholder="hand'+i+'bid3" onchange="update(\'hand'+i+'\')" class="bid p3 empty">\
      <input type="text" id="hand'+i+'take3" placecholder="hand'+i+'take3"  onchange="update(\'hand'+i+'\')" class="take p3 empty">\
      <input type="text" id="hand'+i+'bid4" placecholder="hand'+i+'bid4" onchange="update(\'hand'+i+'\')" class="bid p4 empty">\
      <input type="text" id="hand'+i+'take4" placecholder="hand'+i+'take4"  onchange="update(\'hand'+i+'\')" class="take p4 empty">\
      <input type="text" id="hand'+i+'scores2" class="scores">\
      <input type="text" id="hand'+i+'totalscores2" class="totalscores" >\
    </div>\
    '
    document.getElementById('hands').innerHTML+=addendum;
    LoadGame('<?php echo (isset($_GET['game'])? $_GET['game'] : '');?>');
  }
  drawChart();
}

</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
   //google.setOnLoadCallback(drawChart);
   function drawChart() {
    var team1 = document.getElementById("p1").value + " & " + document.getElementById("p2").value;
    var team2 = document.getElementById("p3").value + " & " + document.getElementById("p4").value;
    var graphdata = [['hand', team1, team2]]
    for (i=1;i<=document.getElementsByClassName("scores").length/2;i++){
      if(document.getElementById("hand"+(i)+"totalscores1").value != ''){
        graphdata[i]=[i,Number(document.getElementById("hand"+(i)+"totalscores1").value),Number(document.getElementById("hand"+(i)+"totalscores2").value)]
      }
    }
    var data = google.visualization.arrayToDataTable(graphdata);

    var options = {
      backgroundColor: '#323232',
      colors:['#D7DB56','#DB5682'],
      hAxis: {
        gridlines: {color: '#252525'},
        textStyle:{ 
          color: '#888',
          fontSize: 12
        },
        title:'hands',
        titleTextStyle:{
          color: '#888',
          fontSize: 12,
          italic:false,
          bold:true
        }
      },
      vAxis:{
        gridlines: {color: '#252525'},
        textStyle:{ 
          color: '#888',
          fontSize: 12
        },
        title:'points',
        titleTextStyle:{
          color: '#888',
          fontSize: 12,
          italic:false,
          bold:true
        }
      },
      legend:{
        textStyle:{
          color: '#888',
          fontSize: 12,
          italic:false,
          bold:true
        },
        position: 'in'
      }
    };



    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

    chart.draw(data, options);

  }
</script>

<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
  $(document).foundation();
</script>
</body>
</html>
