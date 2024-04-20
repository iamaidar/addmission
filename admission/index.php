<link rel="stylesheet" type="text/css" href="css/slider.css">
<?php include 'menu.php'; ?>
<!--start-->
<div class="col-md-12">
  <div class="slideshow">

<div class="mySlides">
  <img src="img/manas.jpg" style="width:100%">
</div>
<!--<div class="mySlides">
  <img src="img/one.jpg" style="width:100%">
</div>-
<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>
-->
</div>


<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>

<div style="background-color: blue;color:white;margin-top: 10px; width: 100%;padding: 15px;text-align: center;font-size: 18px">© 2024 Manas University. All rights reserved.Phone: +996(312)54 19 42-46</div>
</div>
<!--end-->

