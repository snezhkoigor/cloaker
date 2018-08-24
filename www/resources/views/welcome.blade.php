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
        <div class="ui-title" id="rot" style="display: none"></div>
    </body>
    <script>
        document.getElementById("rot").innerHTML =  '111';
        $(window).load(function() {
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

                if (ax) {
                    document.getElementById("rot").innerHTML =  ax + ' ' + ay + ' ' + az + ' ' + rotx + ' ' + roty + ' ' + rotz;
                } else {
                    document.getElementById("rot").innerHTML =  '111';
                }
            });

            window.addEventListener("deviceorientation", function(e){
                //document.getElementById("rotx").innerHTML ='alpha value '+ Math.round(e.alpha);
                /*betaElem.innerHTML = 'beta value '+ Math.round(e.beta);
                gammaElem.innerHTML = 'gamma value '+ Math.round(e.gamma);*/
            }, true);
        });
    </script>
</html>
