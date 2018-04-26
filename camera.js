(function() {

  var streaming = false,
      video        = document.querySelector('#video'),
      canvas       = document.querySelector('#canvas'),
      startbutton  = document.querySelector('#startbutton'),
      width = 640,
      height = 0;

    navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);
    navigator.getMedia(
    {
      video: true,
      audio: false
    },
     function(stream) {
          if (navigator.mozGetUserMedia) {
            video.mozSrcObject = stream;
          }
          else {
            var vendorURL = window.URL || window.webkitURL;
            video.srcObject = stream;
       }
          video.play();
    },
    function(err) {
        console.log("An error occured! " + err);
    }
  );
    video.addEventListener('canplay', function(ev){
        if (!streaming) {
            height = video.videoHeight / (video.videoWidth/width);
            video.setAttribute('width', width);
            video.setAttribute('height', height);
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);
               streaming = true;
           }
     }, false);
     startbutton.addEventListener('click', function(ev){
         takepicture();
        ev.preventDefault();
    }, false);

    function takepicture() 
    {
      canvas.width = width;
      canvas.height = height;
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      var data = canvas.toDataURL('image/png');
      if (document.getElementById("fire").checked == true)
        i = 'img/fire.png';
      else if (document.getElementById("vador").checked == true)
        i = 'img/vador.png';
      else if (document.getElementById("bat").checked == true)
        i = 'img/bat.png';
      else 
        i = 'none';
      var httpPost = new XMLHttpRequest();
      httpPost.onreadystatechange = function() {
        if (httpPost.readyState === 4) {
          if (httpPost.response === "error1") {
            window.location.replace("montage.php?error=1");
          }
          else if (httpPost.response === "error2") {
            window.location.replace("montage.php?error=2");
          }
           else if (httpPost.response === "error3") {
            window.location.replace("montage.php?error=3");
          }
          else {
            window.location.replace("montage.php");
          }
        }
      }
      httpPost.open("POST", "/create_photo.php");
      httpPost.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      httpPost.send('img=' +  encodeURIComponent(i) + '&photo=' + encodeURIComponent(data));
    }
})();