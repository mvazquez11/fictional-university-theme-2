import $ from 'jquery';

class Search {
  //---Describe our object
  constructor(){
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    //---Helps to get the methods loaded right away
    this.events();
  }

  //---2. different events
  //---Depending of the event a method could be called
  events(){
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    //---Checks if key is pressed
    $(document).on("keyup", this.keyPressedDispatcher.bind(this));
  }

  //---3. Methods (function, action...)

  keyPressedDispatcher(){
    console.log("this is a test");
  }

  openOverlay(){
    this.searchOverlay.addClass("search-overlay--active");
    //---Remove the ability to scroll
    $("body").addClass("body-no-scroll");
  }

  closeOverlay(){
    this.searchOverlay.removeClass("search-overlay--active");
    //---Prevents the website being stuck
    $("body").removeClass("body-no-scroll");
  }

}

export default Search;

