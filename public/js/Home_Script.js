 // Fetch images from the data attribute
 const dataImages = document.getElementById('slideshow-image').getAttribute('data-images');
 const images = dataImages.split(',');

 let currentImageIndex = 0;

 function showNextImage() {
     currentImageIndex = (currentImageIndex + 1) % images.length;
     document.getElementById('slideshow-image').src = images[currentImageIndex];
 }

 function showPreviousImage() {
     currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
     document.getElementById('slideshow-image').src = images[currentImageIndex];
 }