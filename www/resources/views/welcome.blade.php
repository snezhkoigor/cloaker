<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <title>Landings</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Welcome to our beautiful landing page.
                </div>
            </div>
        </div>
        <div class="ui-page ui-page-active" id="main">
            <header class="ui-header">
                <h2 class="ui-title">Accelerometer </h2>
            </header>
            <div class="ui-content content-padding">
                <ul class="ui-listview">
                    <li id="rotx" style="font-size: 20px "> Rot X </li>
                    <li id="roty" style="font-size: 20px "> Rot Y </li>
                    <li id="rotz" style="font-size: 20px "> Rot Z</li>
                    <li id="xaccel" style="font-size: 20px "> X </li>
                    <li id="yaccel" style="font-size: 20px "> Y </li>
                    <li id="zaccel" style="font-size: 20px "> Z </li>
                </ul>
            </div>
        </div>
    </body>
    <script>
        //Gear 2 Swipe Gesture Tutorial
        //----------------------------------

        //Copyright (c)2014 Dibia Victor, Denvycom
        //Distributed under MIT license

        //https://github.com/chuvidi2003/AcceleroMeterGear
        $(window).load(function(){
            //This listens for the back button press
            document.addEventListener('tizenhwkey', function(e) {
                if(e.keyName == "back")
                    tizen.application.getCurrentApplication().exit();
            });

            window.addEventListener('devicemotion', function(e) {
                ax = e.accelerationIncludingGravity.x;
                ay = -e.accelerationIncludingGravity.y;
                az = -e.accelerationIncludingGravity.z;
                rotx = e.rotationRate.alpha ;
                roty = e.rotationRate.beta ;
                rotz = e.rotationRate.gamma ;

                document.getElementById("xaccel").innerHTML =  'AccX : ' +  ax;
                document.getElementById("yaccel").innerHTML = 'AccY : ' + ay;
                document.getElementById("zaccel").innerHTML = 'AccZ : ' + az;

                document.getElementById("rotx").innerHTML = 'Rot X : ' + rotx ;
                document.getElementById("roty").innerHTML = 'Rot Y : ' + roty ;
                document.getElementById("rotz").innerHTML = 'Rot Z : ' + rotz ;
            });

            window.addEventListener("deviceorientation", function(e){
                //document.getElementById("rotx").innerHTML ='alpha value '+ Math.round(e.alpha);
                /*betaElem.innerHTML = 'beta value '+ Math.round(e.beta);
                gammaElem.innerHTML = 'gamma value '+ Math.round(e.gamma);*/
            }, true);
        });
    </script>
</html>
