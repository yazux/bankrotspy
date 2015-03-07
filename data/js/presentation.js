function nextSlide(id, n) {
  changeSlide(id, +1, n);
}
function prevSlide(id, n) {
  changeSlide(id, -1, n);
}

function changeSlide(id, next, n) {
  var root = document.getElementById(id);
  var slides = root.getElementsByClassName('slide');
  var maxSlide = slides.length - 1;
  for (var i = 0; i <= maxSlide; i++) {
    if (slides[i].style.display == "block") {
      // нашли текущий слайд
      var nextId = i + next;
      if (nextId < 0) nextId = maxSlide;
      else if (nextId > maxSlide) nextId = 0;
      // прячем текущий слайд
      slides[i].style.display = "none";
      // показываем следующий
      slides[nextId].style.display = "block";
      document.getElementById(n).innerHTML = nextId + 1;
      break;
    }
  }
}