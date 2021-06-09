import $ from 'jquery';

class Search {
  //---Describe our object
  constructor(){
    //---Search layout loaded first
    this.addSearchHTML();
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    //---Helps to get the methods loaded right away
    this.events();
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.typingTimer;
    //---Keeps track of the search value
    this.previousValue;
  }

  //---2. different events
  //---Depending of the event a method could be called
  events(){
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    //---Checks if key is pressed
    $(document).on("keydown", this.keyPressedDispatcher.bind(this));
    //---Fix to prevent the timeout happens
    this.searchField.on("keyup", this.typingLogic.bind(this) );
  }

  //---3. Methods (function, action...)

  typingLogic(){
    //alert("Hello from typing logic");
    //---Checks if the keystroke changes the value typed
    if(this.searchField.val() != this.previousValue){
      //---Wait for the next
      //---reset timer
      clearTimeout(this.typingTimer);
      //---if search input is empty
      if(this.searchField.val()){
        if(!this.isSpinnerVisible){
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
      this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        //---Prevents the spinner to be visible
        this.resultsDiv.html('');
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchField.val();
  }

  //---Obtain search data
  getResults(){
    $.when(
      $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()),
      $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())).then((posts, pages) => {
      var combinedResults = posts[0].concat(pages[0]);
        this.resultsDiv.html(`
        <h2 class="search-overlay__section-title">General Information</h2>
        ${combinedResults.length ? '<ul class="link-list min-list>' : '<p>No general information matches that search.</p>'  }
          ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
          ${combinedResults.length ? '</ul>' : ''}
        </ul>
      `);
      this.isSpinnerVisible = false;


    }, () => {
      this.resultsDiv.html('<p>Unexpected Error. Try again!</p>');
    });
    //this.resultsDiv.html("Imagine Real Search Results");
    //this.isSpinnerVisible = false;
  }

  //---Identify the key pressed to search and
  keyPressedDispatcher(e){
    //---For 's' key
    if(e.keyCode == 83 && !this.isOverlayOpen && $("input, textarea").is(':focus')){
      //---Calls open search overlay
      this.openOverlay();
    }

    if(e.keyCode == 27 && this.isOverlayOpen){
      this.closeOverlay();
    }
  }
  //---Open search overlay
  openOverlay(){
    this.searchOverlay.addClass("search-overlay--active");
    //---Remove the ability to scroll
    $("body").addClass("body-no-scroll");
    this.searchField.val('');
    setTimeout(() => this.searchField.focus() ,301);
    console.log("Our open method just ran");
    this.isOverlayOpen = true;
  }
  //---Close search overlay
  closeOverlay(){
    this.searchOverlay.removeClass("search-overlay--active");
    //---Prevents the website being stuck
    $("body").removeClass("body-no-scroll");
    console.log("Our close method just ran");
    this.isOverlayOpen = false;
  }

  addSearchHTML(){
    $("body").append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
    `);

  }

}

export default Search;

