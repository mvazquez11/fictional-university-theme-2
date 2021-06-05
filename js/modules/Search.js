import $ from 'jquery';

class Search {
  //---Describe our object
  constructor(){
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    //---Helps to get the methods loaded right away
    this.events();
    this.isOverlayOpen = false;
  }

  //---2. different events
  //---Depending of the event a method could be called
  events(){
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    //---Checks if key is pressed
    $(document).on("keydown", this.keyPressedDispatcher.bind(this));
  }

  //---3. Methods (function, action...)

  //---Identify the key pressed to search and
  keyPressedDispatcher(e){
    //---For 's' key
    if(e.keyCode == 83 && !this.isOverlayOpen){
      //---Calls open search overlay
      this.openOverlay();
    }

    if(e.keyCode == 27 && this.isOverlayOpen){
      this.closeOverlay();
    }
  }

  openOverlay(){
    this.searchOverlay.addClass("search-overlay--active");
    //---Remove the ability to scroll
    $("body").addClass("body-no-scroll");
    console.log("Our open method just ran");
    this.isOverlayOpen = true;
  }

  closeOverlay(){
    this.searchOverlay.removeClass("search-overlay--active");
    //---Prevents the website being stuck
    $("body").removeClass("body-no-scroll");
    console.log("Our close method just ran");
    this.isOverlayOpen = false;
  }

}

export default Search;

