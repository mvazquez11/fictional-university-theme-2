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
    $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
      this.resultsDiv.html(`
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'  }
            ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
            ${results.generalInfo.length ? '</ul>' : ''}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`  }
            ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
            ${results.programs.length ? '</ul>' : ''}
            <h2 class="search-overlay__section-title">Professors</h2>
            ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match that search. `  }
            ${results.professors.map(item => `
            <li class="professor-card__list-item">
              <a class="professor-card" href="${item.permalink}">
                <img class="professor-card__image" src="${item.image}" />
                <span class="professor-card__name">${item.title}</span>
              </a>
            </li>
            `).join('')}
            ${results.professors.length ? '</ul>' : ''}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View all campuses</a></p>`  }
            ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
            ${results.campuses.length ? '</ul>' : ''}
            <h2 class="search-overlay__section-title">Events</h2>
            ${results.events.length ? '' : `<p>No events match that search. <a href="${universityData.root_url}/events">View all events</a></p>`  }
            ${results.events.map(item => `
            <div class="event-summary">
              <a class="event-summary__date t-center" href="${item.permalink}">
                <span class="event-summary__month">
                  ${item.month}
                </span>
                <span class="event-summary__day">
                  ${item.day}
                </span>
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                <p>${item.description}
                  <a href="${item.permalink}" class="nu gray">Learn more</a>
                </p>
              </div>
            </div>


            `).join('')}
          </div>
        </div>
      `);
      this.isSpinnerVisible = false;
    });
    //this.resultsDiv.html("Imagine Real Search Results");
    //this.isSpinnerVisible = false;
  }

  //---Identify the key pressed to search and
  keyPressedDispatcher(e){
    //---For 's' key
    if(e.keyCode == 83 && document.activeElement.tagName != "INPUT" && document.activeElement.tagName != "TEXTAREA"){
      e.preventDefault();
      //---Calls open search overlay
      this.openOverlay();
    }

    if(e.keyCode == 27 && this.isOverlayOpen){
      e.preventDefault();
      this.closeOverlay();
    }
  }
  //---Open search  overlay
  openOverlay(){
    this.searchOverlay.addClass("search-overlay--active");
    //---Remove the ability to scroll
    $("body").addClass("body-no-scroll");
    this.searchField.val('');
    setTimeout(() => this.searchField.focus() ,301);
    console.log("Our open method just ran");
    this.isOverlayOpen = true;
    //---Prevent default behaviour of a tag
    return false;
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

