<!doctype html>
<html lang="en">
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    </head>
    <body>
        <div class="ui-title" id="rot"></div>
    </body>
    <script>
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

                document.getElementById("rot").innerHTML =  ax + ' ' + ay + ' ' + az + ' ' + rotx + ' ' + roty + ' ' + rotz;
            });

            window.addEventListener("deviceorientation", function(e){
                //document.getElementById("rotx").innerHTML ='alpha value '+ Math.round(e.alpha);
                /*betaElem.innerHTML = 'beta value '+ Math.round(e.beta);
                gammaElem.innerHTML = 'gamma value '+ Math.round(e.gamma);*/
            }, true);
        });
    </script>
</html>
