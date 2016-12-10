function setPropertyCounter() {
  return document.getElementById("count").innerHTML = RENTALS.length +
    " Great Locations to Choose from";
}

function renderPropertyCollection() {
  setPropertyCounter();
  RENTALS.map(function(rental, i) {
    new PropertyPreview(rental, i).appendToPage();
  });
}

function setPropertyContent(id) {
  return "<h3>" + RENTALS[id].addr + "</h3>" +
    "<p>" + RENTALS[id].category + "<br><br>" +
    "<p>" + RENTALS[id].description + "<br><br>" +
    RENTALS[id].features.replace(/, /g, "<br>&bull; ") + "</p>";
}

function appendPropertyPageToDOM(property) {
  property.div.appendChild(property.thumbnail);
  property.div.appendChild(property.description);
  property.section.appendChild(property.floorplan);
  property.section.appendChild(property.map);
}

function renderNewRentalPage(newLargeRental) {
  var slideshow = document.getElementById("slideshow");
  slideshow.innerHTML = "";
  slideshow.appendChild(newLargeRental.div);
  slideshow.appendChild(newLargeRental.section);
  slideshow.style.display = "block";
}

function showDetailedPropertyListing(id) {
  var listing = new DetailedPropertyListing(id);

  appendPropertyPageToDOM(listing);
  renderNewRentalPage(listing);
}

// expandImage() enlarges an image when a user clicks it's thumbnail.
function expandImage() {
  // links stores an array of all anchor tags in the main index.
  var links = Array.prototype.slice.call(document.querySelectorAll("#index a"));

  // links.map adds a showDetailedPropertyListing() event listener to each link.
  links.map(function(l) {
    google.maps.event.addDomListener(l, 'click', function() {
      showDetailedPropertyListing(l.id);
      return new GoogleMap(l.id).render();
    });
  });
}
